const RESPETAR_MOVIMIENTO_REDUCIDO = false;

const reducirMovimiento = RESPETAR_MOVIMIENTO_REDUCIDO &&
    window.matchMedia("(prefers-reduced-motion: reduce)").matches;
if (reducirMovimiento) document.documentElement.classList.add("sin-movimiento");

const LLUVIA_MUERTOS = {
    flores: 8,   
    petalos: 5,  
    granos: 12   
};
const LLUVIA_REYES = {
    copos: 26 
};

let toastTimer;
let contactToastTimer;

document.addEventListener("DOMContentLoaded", () => {
    iniciarMenu();
    iniciarHeader();
    iniciarCarrito();
    iniciarEliminar();
    iniciarBusqueda();
    iniciarTaza();
    iniciarVoltea();
    iniciarMapa();
    iniciarMaterias();
    iniciarCambioTemporada();

    if (!reducirMovimiento) {
        iniciarLluvia();
        iniciarReveal();
    }
});

function iniciarMenu() {
    const menuToggle = document.getElementById("menuToggle");
    const mainNav = document.getElementById("mainNav");
    if (!menuToggle || !mainNav) return;

    menuToggle.addEventListener("click", () => {
        const abierto = mainNav.classList.toggle("open");
        menuToggle.textContent = abierto ? "✕" : "☰";
        menuToggle.setAttribute("aria-expanded", abierto ? "true" : "false");
    });
}

function iniciarHeader() {
    const header = document.querySelector(".site-header");
    if (!header) return;

    const actualizar = () => header.classList.toggle("scrolled", window.scrollY > 10);
    actualizar();
    window.addEventListener("scroll", actualizar, { passive: true });
}

function florSVG() {
    let capas = "";
    const anillo = (n, cy, rx, ry, clase, giro) => {
        for (let i = 0; i < n; i++) {
            capas += `<ellipse class="${clase}" cx="50" cy="${cy}" rx="${rx}" ry="${ry}" transform="rotate(${giro + (i * 360) / n} 50 50)"/>`;
        }
    };
    anillo(12, 22, 11, 20, "fl-a", 0);
    anillo(10, 30, 9, 16, "fl-b", 18);
    anillo(8, 38, 7, 11, "fl-a", 0);
    return `<svg viewBox="0 0 100 100" aria-hidden="true">${capas}<circle class="fl-c" cx="50" cy="50" r="7"/></svg>`;
}

function iniciarTaza() {
    const contenido = document.querySelector(".hero-content");
    if (!contenido) return;

    const taza = document.createElement("div");
    taza.className = "hero-taza";
    taza.setAttribute("aria-hidden", "true");
    taza.innerHTML = `
        <svg viewBox="0 0 120 124">
            <ellipse class="taza-plato" cx="60" cy="114" rx="46" ry="8"/>
            <path class="taza-asa" d="M92 72 C112 70 112 98 88 98"/>
            <path class="taza-cuerpo" d="M26 64 H94 V80 C94 100 80 114 60 114 C40 114 26 100 26 80 Z"/>
            <path class="taza-franja" d="M31 84 H89"/>
            <ellipse class="taza-borde" cx="60" cy="64" rx="34" ry="8"/>
            <ellipse class="taza-cafe" cx="60" cy="65" rx="29" ry="5.5"/>
            <g class="vapor">
                <path pathLength="100" d="M46 56 C36 46 56 38 46 28 C38 20 54 14 46 4"/>
                <path pathLength="100" d="M60 54 C50 44 70 36 60 26 C52 18 68 12 60 2"/>
                <path pathLength="100" d="M74 56 C64 46 84 38 74 28 C66 20 82 14 74 4"/>
            </g>
        </svg>`;
    contenido.prepend(taza);
}

function iniciarCarrito() {
    document.querySelectorAll(".add-cart").forEach(button => {
        button.addEventListener("click", async (evento) => {
            evento.stopPropagation();
            const id = button.dataset.id;
            const etiqueta = button.dataset.label = button.dataset.label || button.textContent;
            button.disabled = true;

            try {
                const response = await fetch("agregar_carrito.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: "id=" + encodeURIComponent(id)
                });
                const data = await response.json();

                if (data.ok) {
                    volarAlCarrito(button, () => actualizarContador(data.cantidad));

                    button.classList.add("added");
                    button.textContent = "✓ Agregado";
                    clearTimeout(button._timer);
                    button._timer = setTimeout(() => {
                        button.classList.remove("added");
                        button.textContent = etiqueta;
                    }, 1400);

                    showToast("Producto agregado al carrito");
                } else {
                    showToast("No se pudo agregar el producto", "error");
                }
            } catch (error) {
                showToast("Error de conexión", "error");
            } finally {
                button.disabled = false;
            }
        });
    });
}

function actualizarContador(cantidad) {
    const count = document.getElementById("cartCount");
    if (!count) return;

    count.textContent = cantidad;
    count.classList.remove("bump");
    void count.offsetWidth;
    count.classList.add("bump");
}
function volarAlCarrito(origen, alTerminar) {
    const destino = document.getElementById("cartCount");
    const a = origen.getBoundingClientRect();
    const b = destino ? destino.getBoundingClientRect() : null;
    if (reducirMovimiento || !b || b.width === 0) {
        alTerminar();
        return;
    }

    const flor = document.createElement("span");
    flor.className = "fly-dot";
    flor.innerHTML = florSVG();
    document.body.appendChild(flor);

    const x1 = a.left + a.width / 2 - 17;
    const y1 = a.top + a.height / 2 - 17;
    const x2 = b.left + b.width / 2 - 17;
    const y2 = b.top + b.height / 2 - 17;

    const vuelo = flor.animate([
        { transform: `translate(${x1}px, ${y1}px) scale(1.3) rotate(0deg)`, opacity: 1 },
        { transform: `translate(${(x1 + x2) / 2}px, ${Math.min(y1, y2) - 110}px) scale(1.6) rotate(200deg)`, opacity: 1, offset: 0.5 },
        { transform: `translate(${x2}px, ${y2}px) scale(.35) rotate(420deg)`, opacity: 0.7 }
    ], { duration: 900, easing: "cubic-bezier(.5, 0, .7, .4)" });

    vuelo.onfinish = () => {
        flor.remove();
        alTerminar();
    };
}

function iniciarVoltea() {
    document.querySelectorAll(".ofrenda-card").forEach(tarjeta => {
        const voltear = () => tarjeta.classList.toggle("flipped");

        tarjeta.addEventListener("click", voltear);
        tarjeta.addEventListener("keydown", evento => {
            if (evento.key === "Enter" || evento.key === " ") {
                evento.preventDefault();
                voltear();
            }
        });
    });
}
function iniciarEliminar() {
    document.querySelectorAll(".remove-form").forEach(form => {
        form.addEventListener("submit", evento => {
            evento.preventDefault();
            if (form.dataset.enviando) return;
            form.dataset.enviando = "1";

            const item = form.closest(".cart-item");
            if (item && !reducirMovimiento) {
                item.classList.remove("reveal", "visible");
                item.classList.add("removing");
                setTimeout(() => form.submit(), 420);
            } else {
                form.submit();
            }
        });
    });

    const vaciar = document.querySelector(".clear-form");
    if (vaciar) {
        vaciar.addEventListener("submit", evento => {
            if (!confirm("¿Vaciar todo el carrito?")) evento.preventDefault();
        });
    }
}

function iniciarBusqueda() {
    const searchBtn = document.getElementById("searchBtn");
    if (!searchBtn) return;

    searchBtn.addEventListener("click", () => {
        const text = prompt("¿Qué producto buscas?");
        if (!text) return;
        window.location.href = "menu.php?buscar=" + encodeURIComponent(text);
    });
}

function granoSVG() {
    return `<svg viewBox="0 0 100 100" aria-hidden="true">
        <g transform="rotate(25 50 50)">
            <ellipse class="gr-c" cx="50" cy="50" rx="27" ry="40"/>
            <path class="gr-l" d="M50 14 C36 38 64 62 50 86"/>
        </g>
    </svg>`;
}

function iniciarLluvia() {
    construirLluvia();
}

function construirLluvia() {
    const anterior = document.querySelector(".lluvia");
    if (anterior) anterior.remove();

    const esReyes = document.documentElement.getAttribute("data-tema") === "reyes";
    const factor = window.innerWidth < 760 ? 0.55 : 1;
    const alto = () => window.innerHeight + 90;

    const capa = document.createElement("div");
    capa.className = "lluvia";
    capa.setAttribute("aria-hidden", "true");
    capa.style.setProperty("--h", alto() + "px");
    window.addEventListener("resize", () => capa.style.setProperty("--h", alto() + "px"));

    const piezas = esReyes
        ? [["copo", LLUVIA_REYES.copos]]
        : [["flor", LLUVIA_MUERTOS.flores], ["petalo", LLUVIA_MUERTOS.petalos], ["grano", LLUVIA_MUERTOS.granos]];

    piezas.forEach(([tipo, cantidad]) => {
        const total = Math.round(cantidad * factor);
        for (let i = 0; i < total; i++) {
            capa.appendChild(crearPiezaDeLluvia(tipo, i, alto()));
        }
    });

    document.body.appendChild(capa);
}

function crearPiezaDeLluvia(tipo, i, alto) {
    const el = document.createElement("span");
    let tam, velocidad;

    if (tipo === "flor") {
        el.className = "petal flor";
        el.innerHTML = florSVG();
        tam = 30 + Math.random() * 28;
        velocidad = 70 + Math.random() * 40;
    } else if (tipo === "grano") {
        el.className = "petal grano";
        el.innerHTML = granoSVG();
        tam = 20 + Math.random() * 14;
        velocidad = 110 + Math.random() * 60;
    } else if (tipo === "copo") {
        el.className = "petal copo";
        tam = 5 + Math.random() * 9;
        velocidad = 40 + Math.random() * 35;
    } else {
        el.className = "petal petal-hoja " + (i % 2 ? "petal-a" : "petal-b");
        tam = 14 + Math.random() * 12;
        velocidad = 80 + Math.random() * 40;
    }

    const duracion = alto / velocidad;
    el.style.setProperty("--x", (Math.random() * 96).toFixed(1) + "%");
    el.style.setProperty("--s", tam.toFixed(1) + "px");
    el.style.setProperty("--d", duracion.toFixed(1) + "s");
    el.style.setProperty("--delay", (-Math.random() * duracion).toFixed(1) + "s");
    el.style.setProperty("--drift", Math.round((Math.random() - 0.5) * 240) + "px");
    return el;
}

function iniciarReveal() {
    if (!("IntersectionObserver" in window)) return;

    const selectores = [
        ".essence-image", ".essence-text", ".features > div",
        ".category-card", ".product-card",
        ".story > *", ".location > *", ".contact > *",
        ".cart-item", ".empty-cart"
    ].join(",");

    const elementos = document.querySelectorAll(selectores);
    if (!elementos.length) return;

    const observador = new IntersectionObserver(entradas => {
        entradas
            .filter(e => e.isIntersecting)
            .forEach((entrada, i) => {
                const el = entrada.target;
                const retraso = Math.min(i, 6) * 110;
                el.style.setProperty("--rd", retraso + "ms");
                el.classList.add("visible");
                observador.unobserve(el);

                
                setTimeout(() => {
                    el.classList.remove("reveal", "visible");
                    el.style.removeProperty("--rd");
                }, 1000 + retraso);
            });
    }, { threshold: 0.12, rootMargin: "0px 0px -6% 0px" });

    elementos.forEach(el => {
        el.classList.add("reveal");
        observador.observe(el);
    });
}

function iniciarMapa() {
    const datosEl = document.getElementById("datosEstados");
    if (!datosEl) return;

    let datos = {};
    try {
        datos = JSON.parse(datosEl.textContent);
    } catch (error) {
        return;
    }

    const titulo = document.getElementById("mapaPanelTitulo");
    const texto = document.getElementById("mapaPanelTexto");
    let actual = null;

    const mostrar = id => {
        const info = datos[id];
        if (!info) return;
        titulo.textContent = info.nombre;
        texto.textContent = info.texto;
    };

    document.querySelectorAll(".estado").forEach(estado => {
        estado.addEventListener("mouseenter", () => mostrar(estado.id));
        estado.addEventListener("focus", () => mostrar(estado.id));

        estado.addEventListener("click", () => {
            if (actual) actual.classList.remove("activo");
            estado.classList.add("activo");
            actual = estado;
            mostrar(estado.id);
        });
    });
}
function iniciarMaterias() {
    const boton = document.getElementById("materiasBtn");
    const overlay = document.getElementById("materiasOverlay");
    const panel = document.getElementById("materiasPanel");
    const cerrar = document.getElementById("materiasCerrar");
    const lista = document.getElementById("materiasLista");
    if (!boton || !panel) return;

    let cargado = false;

    const abrir = () => {
        overlay.classList.add("abierto");
        panel.classList.add("abierto");
        panel.setAttribute("aria-hidden", "false");
        boton.setAttribute("aria-expanded", "true");
        if (!cargado) cargarMaterias();
    };

    const cerrarPanel = () => {
        overlay.classList.remove("abierto");
        panel.classList.remove("abierto");
        panel.setAttribute("aria-hidden", "true");
        boton.setAttribute("aria-expanded", "false");
    };

    boton.addEventListener("click", () => {
        panel.classList.contains("abierto") ? cerrarPanel() : abrir();
    });
    overlay.addEventListener("click", cerrarPanel);
    cerrar.addEventListener("click", cerrarPanel);
    document.addEventListener("keydown", evento => {
        if (evento.key === "Escape") cerrarPanel();
    });

    async function cargarMaterias() {
        try {
            const respuesta = await fetch("obtener_materias.php");
            const datos = await respuesta.json();
            if (!datos.ok) {
                lista.innerHTML = '<p class="materias-error">' + (datos.error || "No se pudieron cargar las materias.") + '</p>';
                return;
            }
            cargado = true;
            renderizarMaterias(datos.materias);
        } catch (error) {
            lista.innerHTML = '<p class="materias-error">No se pudieron cargar las materias. Revisa que hayas importado database.sql de nuevo.</p>';
        }
    }

    function renderizarMaterias(materias) {
        lista.innerHTML = "";
        materias.forEach(materia => lista.appendChild(crearMateriaItem(materia)));
    }

    function crearMateriaItem(materia) {
        const item = document.createElement("div");
        item.className = "materia-item";

        const titulo = document.createElement("button");
        titulo.type = "button";
        titulo.className = "materia-titulo";
        titulo.textContent = materia.nombre;
        titulo.addEventListener("click", () => item.classList.toggle("abierta"));

        const cuerpo = document.createElement("div");
        cuerpo.className = "materia-cuerpo";

        const contenidoLista = document.createElement("div");
        contenidoLista.className = "materia-contenido-lista";
        pintarContenido(contenidoLista, materia.contenido);

        const form = document.createElement("form");
        form.className = "materia-form";
        form.innerHTML = `
            <input type="file" accept=".pdf,.doc,.docx" hidden>
            <div class="materia-form-fila">
                <textarea placeholder="Agregar tarea, aviso o material..." maxlength="1000"></textarea>
                <button type="button" class="materia-adjuntar-btn" title="Adjuntar Word o PDF">📎</button>
                <button type="submit">Agregar</button>
            </div>
            <span class="materia-archivo-elegido"></span>
        `;

        const inputArchivo = form.querySelector('input[type="file"]');
        const botonAdjuntar = form.querySelector(".materia-adjuntar-btn");
        const nombreElegido = form.querySelector(".materia-archivo-elegido");

        botonAdjuntar.addEventListener("click", () => inputArchivo.click());
        inputArchivo.addEventListener("change", () => {
            const archivo = inputArchivo.files[0];
            botonAdjuntar.classList.toggle("con-archivo", !!archivo);
            nombreElegido.textContent = archivo ? "📎 " + archivo.name + " — clic en 📎 para quitarlo" : "";
            if (archivo) botonAdjuntar.onclick = () => { inputArchivo.value = ""; inputArchivo.dispatchEvent(new Event("change")); };
            else botonAdjuntar.onclick = () => inputArchivo.click();
        });

        form.addEventListener("submit", async evento => {
            evento.preventDefault();
            const textarea = form.querySelector("textarea");
            const boton = form.querySelector('button[type="submit"]');
            const texto = textarea.value.trim();
            const archivo = inputArchivo.files[0];
            if (!texto && !archivo) {
                showToast("Escribe algo o adjunta un archivo", "error");
                return;
            }

            const datosForm = new FormData();
            datosForm.append("materia_id", materia.id);
            datosForm.append("texto", texto);
            if (archivo) datosForm.append("archivo", archivo);

            boton.disabled = true;
            try {
                const respuesta = await fetch("agregar_contenido_materia.php", {
                    method: "POST",
                    body: datosForm
                });
                const datos = await respuesta.json();

                if (datos.ok) {
                    quitarVacio(contenidoLista);
                    contenidoLista.prepend(crearNota(datos.contenido));
                    textarea.value = "";
                    inputArchivo.value = "";
                    botonAdjuntar.classList.remove("con-archivo");
                    nombreElegido.textContent = "";
                    showToast("Contenido agregado a " + materia.nombre);
                } else {
                    showToast(datos.error || "No se pudo agregar", "error");
                }
            } catch (error) {
                showToast("Error de conexión", "error");
            } finally {
                boton.disabled = false;
            }
        });

        cuerpo.appendChild(contenidoLista);
        cuerpo.appendChild(form);
        item.appendChild(titulo);
        item.appendChild(cuerpo);
        return item;
    }

    function pintarContenido(contenedor, contenido) {
        if (!contenido.length) {
            contenedor.appendChild(crearVacio());
            return;
        }
        contenido.forEach(c => contenedor.appendChild(crearNota(c)));
    }

    function crearVacio() {
        const vacio = document.createElement("p");
        vacio.className = "materia-vacio";
        vacio.textContent = "Aún no hay contenido en esta materia.";
        return vacio;
    }

    function quitarVacio(contenedor) {
        const vacio = contenedor.querySelector(".materia-vacio");
        if (vacio) vacio.remove();
    }

    function crearNota(c) {
        const nota = document.createElement("div");
        nota.className = "materia-nota";
        nota.dataset.id = c.id;

        if (c.archivo) {
            const link = document.createElement("a");
            link.className = "materia-archivo";
            link.href = c.archivo;
            link.target = "_blank";
            link.rel = "noopener";
            const extension = (c.archivo_nombre || "").split(".").pop().toLowerCase();
            link.innerHTML = '<span class="icono">' + (extension === "pdf" ? "📕" : "📄") + '</span>';
            link.append(c.archivo_nombre || "Archivo adjunto");
            nota.appendChild(link);
        }

        if (c.texto) {
            const p = document.createElement("p");
            p.className = "materia-texto";
            p.textContent = c.texto;
            nota.appendChild(p);
        }

        const pie = document.createElement("div");
        pie.className = "materia-nota-pie";

        const time = document.createElement("time");
        time.textContent = c.fecha + (c.editado ? " " : "");
        if (c.editado) {
            const tag = document.createElement("span");
            tag.className = "editado-tag";
            tag.textContent = "(editado)";
            time.appendChild(tag);
        }

        const editarBtn = document.createElement("button");
        editarBtn.type = "button";
        editarBtn.className = "materia-editar-btn";
        editarBtn.textContent = "✏️ Editar";
        editarBtn.addEventListener("click", () => activarEdicion(nota, c));

        pie.appendChild(time);
        pie.appendChild(editarBtn);
        nota.appendChild(pie);

        return nota;
    }

    function activarEdicion(nota, c) {
        const anterior = nota.querySelector(".materia-texto");
        const textoActual = anterior ? anterior.textContent : "";

        const caja = document.createElement("div");
        caja.className = "materia-editar-caja";
        caja.innerHTML = `
            <textarea maxlength="1000">${textoActual}</textarea>
            <div class="materia-editar-acciones">
                <button type="button" class="materia-cancelar-btn">Cancelar</button>
                <button type="button" class="materia-guardar-btn">Guardar</button>
            </div>
        `;

        if (anterior) anterior.replaceWith(caja);
        else nota.insertBefore(caja, nota.querySelector(".materia-nota-pie"));

        const textarea = caja.querySelector("textarea");
        textarea.focus();

        caja.querySelector(".materia-cancelar-btn").addEventListener("click", () => {
            const p = document.createElement("p");
            p.className = "materia-texto";
            p.textContent = textoActual;
            if (textoActual) caja.replaceWith(p);
            else caja.remove();
        });

        caja.querySelector(".materia-guardar-btn").addEventListener("click", async () => {
            const nuevoTexto = textarea.value.trim();
            if (!nuevoTexto) {
                showToast("Escribe algo antes de guardar", "error");
                return;
            }

            const boton = caja.querySelector(".materia-guardar-btn");
            boton.disabled = true;
            try {
                const respuesta = await fetch("editar_contenido_materia.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: "id=" + encodeURIComponent(c.id) + "&texto=" + encodeURIComponent(nuevoTexto)
                });
                const datos = await respuesta.json();

                if (datos.ok) {
                    c.texto = nuevoTexto;
                    c.editado = true;
                    const p = document.createElement("p");
                    p.className = "materia-texto";
                    p.textContent = nuevoTexto;
                    caja.replaceWith(p);

                    let time = nota.querySelector("time");
                    if (!time.querySelector(".editado-tag")) {
                        const tag = document.createElement("span");
                        tag.className = "editado-tag";
                        tag.textContent = " (editado)";
                        time.appendChild(tag);
                    }
                    showToast("Contenido actualizado");
                } else {
                    showToast(datos.error || "No se pudo guardar", "error");
                }
            } catch (error) {
                showToast("Error de conexión", "error");
            } finally {
                boton.disabled = false;
            }
        });
    }
}
function iniciarCambioTemporada() {
    const boton = document.getElementById("cambioTemporadaBtn");
    if (!boton) return;

    boton.addEventListener("click", () => {
        const ahoraActivo = document.documentElement.getAttribute("data-tema") !== "reyes";
        document.documentElement.setAttribute("data-tema", ahoraActivo ? "reyes" : "muertos");
        localStorage.setItem("temaReyes", ahoraActivo ? "1" : "0");

        if (!reducirMovimiento) construirLluvia();

        showToast(ahoraActivo
            ? "👑 ¡Feliz Día de Reyes! Prueba una de nuestras 7 roscas."
            : "🕯️ De vuelta al Día de Muertos"
        );
    });
}
function showToast(message, type = "ok") {
    let toast = document.getElementById("toast");
    if (!toast) {
        toast = document.createElement("div");
        toast.id = "toast";
        toast.className = "toast";
        document.body.appendChild(toast);
    }
    toast.dataset.type = type;
    toast.textContent = message;
    toast.classList.add("show");
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => toast.classList.remove("show"), 2200);
}

function mostrarMensajeContacto() {
    const toast = document.getElementById("contactToast");
    if (!toast) return;
    toast.classList.add("show");
    clearTimeout(contactToastTimer);
    contactToastTimer = setTimeout(() => toast.classList.remove("show"), 3500);
}