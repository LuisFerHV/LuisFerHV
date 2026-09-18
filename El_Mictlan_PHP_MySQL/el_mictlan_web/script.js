document.addEventListener("DOMContentLoaded", () => {
    const menuToggle = document.getElementById("menuToggle");
    const mainNav = document.getElementById("mainNav");

    if (menuToggle && mainNav) {
        menuToggle.addEventListener("click", () => mainNav.classList.toggle("open"));
    }

    document.querySelectorAll(".add-cart").forEach(button => {
        button.addEventListener("click", async () => {
            const id = button.dataset.id;
            button.disabled = true;
            try {
                const response = await fetch("agregar_carrito.php", {
                    method: "POST",
                    headers: {"Content-Type":"application/x-www-form-urlencoded"},
                    body: "id=" + encodeURIComponent(id)
                });
                const data = await response.json();

                if (data.ok) {
                    const count = document.getElementById("cartCount");
                    if (count) count.textContent = data.cantidad;
                    showToast("Producto agregado al carrito");
                } else {
                    showToast("No se pudo agregar el producto");
                }
            } catch (error) {
                showToast("Error de conexión");
            } finally {
                button.disabled = false;
            }
        });
    });

    const searchBtn = document.getElementById("searchBtn");
    if (searchBtn) {
        searchBtn.addEventListener("click", () => {
            const text = prompt("¿Qué producto buscas?");
            if (!text) return;
            window.location.href = "menu.php?buscar=" + encodeURIComponent(text);
        });
    }
});

function showToast(message) {
    let toast = document.getElementById("toast");
    if (!toast) {
        toast = document.createElement("div");
        toast.id = "toast";
        toast.className = "toast";
        document.body.appendChild(toast);
    }
    toast.textContent = message;
    toast.classList.add("show");
    setTimeout(() => toast.classList.remove("show"), 2200);
}

function mostrarMensajeContacto() {
    const toast = document.getElementById("contactToast");
    if (!toast) return;
    toast.classList.add("show");
    setTimeout(() => toast.classList.remove("show"), 3500);
}
