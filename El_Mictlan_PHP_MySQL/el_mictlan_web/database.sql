CREATE DATABASE IF NOT EXISTS elmictlan CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE elmictlan;

DROP TABLE IF EXISTS detalle_pedido;
DROP TABLE IF EXISTS pedidos;
DROP TABLE IF EXISTS productos;

CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    categoria ENUM('Bebidas calientes','Comida','Bebidas frías') NOT NULL,
    descripcion VARCHAR(255) DEFAULT '',
    precio DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    imagen VARCHAR(255) DEFAULT 'imn/cafe-granos.jpeg',
    disponible TINYINT(1) NOT NULL DEFAULT 1,
    temporada TINYINT(1) NOT NULL DEFAULT 0 COMMENT '1 = platillo especial de Día de Muertos',
    dato_curioso VARCHAR(255) DEFAULT NULL COMMENT 'Solo se usa cuando temporada=1',
    es_reyes TINYINT(1) NOT NULL DEFAULT 0 COMMENT '1 = solo se muestra con el tema de Rosca de Reyes activado'
);

CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_confirmacion VARCHAR(30) NOT NULL UNIQUE,
    nombre_cliente VARCHAR(120) NOT NULL,
    telefono VARCHAR(30) NOT NULL,
    direccion VARCHAR(255) NOT NULL,
    notas TEXT,
    total DECIMAL(10,2) NOT NULL,
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE detalle_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    producto_id INT NOT NULL,
    nombre_producto VARCHAR(150) NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);

INSERT INTO productos (nombre,categoria,descripcion,precio,imagen,temporada,dato_curioso,es_reyes) VALUES

-- ---------- Bebidas calientes ----------
('Chocolate','Bebidas calientes','Chocolate mexicano caliente.','45.00','imn/chcolate2.jpg',1,'El chocolate se usaba como ofrenda a los dioses mucho antes de la llegada de los españoles a México.',0),
('Champurrado','Bebidas calientes','Bebida tradicional de maíz y chocolate.','45.00','imn/champurrado.webp',1,'El champurrado mezcla el maíz prehispánico con el cacao, una combinación que sigue viva desde la época mesoamericana.',0),
('Café de olla','Bebidas calientes','Café preparado al estilo tradicional mexicano.','40.00','imn/cafe de olla.jpg',0,NULL,0),
('Café americano','Bebidas calientes','Café de sabor intenso y tradicional.','35.00','imn/cafe-granos.jpeg',0,NULL,0),
('Café con leche','Bebidas calientes','Café suave acompañado con leche.','45.00','imn/cafe con leche.jpeg',0,NULL,0),
('Capuchino','Bebidas calientes','Café cremoso con espuma de leche.','55.00','imn/capuchino.jpeg',0,NULL,0),
('Ponche','Bebidas calientes','Bebida de frutas tradicional mexicana.','45.00','imn/ponche.jpg',1,'El ponche de temporada se prepara con frutas como tejocote, guayaba y caña, típicas de los meses fríos.',0),
('Atole','Bebidas calientes','Bebida caliente tradicional de maíz.','40.00','imn/atole.jpg',1,'En muchas ofrendas se deja atole caliente porque se cree que su aroma es lo que alimenta a las almas visitantes.',0),

-- ---------- Comida ----------
('Tamales oaxaqueños','Comida','Tamales de estilo oaxaqueño.','35.00','imn/tamales normales.jpg',1,'Los tamales se colocan en la ofrenda porque, según la tradición, son uno de los platillos favoritos de los difuntos.',0),
('Tamales normales','Comida','Tamales tradicionales mexicanos.','30.00','imn/tamales normales.jpg',1,'Envolver los tamales en hoja de maíz es una técnica de cocción que viene directamente de las culturas prehispánicas.',0),
('Pan de elote','Comida','Pan dulce de elote.','40.00','imn/pan de elote.jpg',0,NULL,0),
('Churros','Comida','Churros tradicionales con azúcar.','35.00','imn/churros.webp',0,NULL,0),
('Conchas','Comida','Pan dulce mexicano tradicional.','25.00','imn/conchas.jpg',0,NULL,0),
('Pan de muerto','Comida','Pan tradicional de temporada.','35.00','imn/pan de morido.jpg',1,'El pan de muerto representa un cráneo y cuatro huesos, que simbolizan las cuatro direcciones del universo.',0),
('Arroz con leche','Comida','Postre tradicional con canela.','40.00','imn/arroz con leche.jpg',0,NULL,0),
('Gelatinas','Comida','Gelatinas de sabores.','30.00','imn/gelatina.jpeg',0,NULL,0),
('Buñuelos','Comida','Buñuelos tradicionales mexicanos.','35.00','imn/churros.webp',1,'Se dice que romper el plato después de comer un buñuelo aleja las malas energías del año que sigue.',0),
('Bocadillos típicos de México','Comida','Selección de bocadillos tradicionales.','55.00','imn/dia-muertos.jpeg',1,'Cada bocadillo de la ofrenda suele representar un platillo favorito de la persona que se está recordando.',0),

-- ---------- Bebidas frías ----------
('Horchata','Bebidas frías','Bebida fresca de arroz con canela.','40.00','imn/horchata.webp',0,NULL,0),
('Pozol','Bebidas frías','Bebida tradicional mexicana de maíz.','45.00','imn/pozol.jpg',0,NULL,0),
('Pozol de Nambimba','Bebidas frías','Preparación tradicional de Nambimba.','50.00','imn/pozol.jpg',0,NULL,0),
('Raspados','Bebidas frías','Hielo raspado con sabor a elegir.','35.00','imn/raspados.webp',0,NULL,0),
('Frapes','Bebidas frías','Bebida fría de café estilo frappé.','60.00','imn/frapes.jpg',0,NULL,0);

INSERT INTO productos (nombre,categoria,descripcion,precio,imagen,temporada,dato_curioso,es_reyes) VALUES
('Rosca Tradicional','Comida','Pan de mantequilla con esencia de azahar y naranja, decorado con costra de azúcar, ate de sabores y cerezas confitadas.','150.00','imn/Rosca tradicional.png',0,
'La forma circular u ovalada de la rosca representa el amor infinito a Dios y la corona de los Reyes Magos, simbolizando un ciclo que no tiene principio ni fin.',1),

('Rosca Rellena de Nata','Comida','Nuestra masa tradicional rellena de auténtica nata fresca montada artesanalmente, suave y cremosa.','180.00','imn/Rosca de nata.jpg',0,
'El muñequito escondido dentro del pan representa al Niño Jesús, quien tuvo que ser ocultado por María y José para protegerlo de la orden del rey Herodes.',1),

('Rosca Rellena de Chocolate con Avellana','Comida','Pan esponjoso relleno de generosa crema de cacao con avellanas y trocitos de nuez tostada.','195.00','imn/Rosca de chocolate.jpg',0,
'Las tiras de ate de colores, higos y frutos secos no son solo decoración: representan las joyas incrustadas en las coronas de Melchor, Gaspar y Baltasar.',1),

('Rosca Rellena de Crema de Chocolate Turín','Comida','Relleno cremoso de auténtico chocolate con leche Turín, coronada con costra crujiente de chocolate.','200.00','imn/Rosca de chocolate.jpg',0,
'En México, a quien le sale el "muñequito" se convierte en su padrino y adquiere el compromiso de invitar los tamales y el atole el 2 de febrero, Día de la Candelaria.',1),

('Rosca Rellena de Queso Crema y Zarzamora','Comida','Suave relleno de queso crema Philadelphia combinado con mermelada artesanal de zarzamora.','190.00','imn/Rosca con zarzamora.jpg',0,
'Antiguamente se decoraba con acitrón, pero al provenir de la biznaga dulce (una cactácea mexicana en peligro de extinción), hoy se sustituye por ate de frutas o jícama cristalizada.',1),

('Rosca Rellena de Cajeta y Nuez','Comida','Rellena de dulce de leche/cajeta estilo Sayula con trozos crujientes de nuez pecana.','195.00','imn/Rosca con cajeta y nuez.jpg',0,
'La forma circular u ovalada de la rosca representa el amor infinito a Dios y la corona de los Reyes Magos, simbolizando un ciclo que no tiene principio ni fin.',1),

('Rosca Rellena de Crema Pastelera','Comida','Clásica crema pastelera con notas de vainilla de Papantla y canela.','180.00','imn/Rosca de crema pastelera.jpg',0,
'El muñequito escondido dentro del pan representa al Niño Jesús, quien tuvo que ser ocultado por María y José para protegerlo de la orden del rey Herodes.',1),

('Rosca de Galleta Lotus Biscoff','Comida','Relleno y cubierta de crema caramelizada de galleta Lotus Biscoff con migas de galleta crujiente.','210.00','imn/Rosca de galleta.jpg',0,
'Las tiras de ate de colores, higos y frutos secos no son solo decoración: representan las joyas incrustadas en las coronas de Melchor, Gaspar y Baltasar.',1);



DROP TABLE IF EXISTS materia_contenido;
DROP TABLE IF EXISTS materias;

CREATE TABLE materias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    orden INT NOT NULL DEFAULT 0
);

CREATE TABLE materia_contenido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    materia_id INT NOT NULL,
    texto TEXT NULL,
    archivo VARCHAR(255) DEFAULT NULL COMMENT 'Ruta del archivo dentro de materiales/',
    archivo_nombre VARCHAR(255) DEFAULT NULL COMMENT 'Nombre original del archivo (Word o PDF)',
    editado TINYINT(1) NOT NULL DEFAULT 0,
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (materia_id) REFERENCES materias(id) ON DELETE CASCADE
);

INSERT INTO materias (nombre,orden) VALUES
('Métodos Numéricos',1),
('Programación para Realidad Virtual',2),
('Seminario de Tesis I',3),
('Multimedia III',4),
('Administrador de Servidores Web',5),
('Proyectos de Negocios Electrónicos',6),
('Producción de Audio Digital',7),
('Inglés VI',8);