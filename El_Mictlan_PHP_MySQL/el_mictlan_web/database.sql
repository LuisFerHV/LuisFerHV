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
    imagen VARCHAR(255) DEFAULT 'assets/cafe-pan.jpeg',
    disponible TINYINT(1) NOT NULL DEFAULT 1
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

-- PRECIOS DE EJEMPLO: reemplázalos por los precios definitivos de El Mictlán.
INSERT INTO productos (nombre,categoria,descripcion,precio,imagen) VALUES
('Chocolate','Bebidas calientes','Chocolate mexicano caliente.','45.00','assets/cafe-pan.jpeg'),
('Champurrado','Bebidas calientes','Bebida tradicional de maíz y chocolate.','45.00','assets/cafe-pan.jpeg'),
('Café de olla','Bebidas calientes','Café preparado al estilo tradicional mexicano.','40.00','assets/cafe-granos.jpeg'),
('Café americano','Bebidas calientes','Café de sabor intenso y tradicional.','35.00','assets/cafe-granos.jpeg'),
('Café con leche','Bebidas calientes','Café suave acompañado con leche.','45.00','assets/cafe-pan.jpeg'),
('Capuchino','Bebidas calientes','Café cremoso con espuma de leche.','55.00','assets/cafe-pan.jpeg'),
('Ponche','Bebidas calientes','Bebida de frutas tradicional mexicana.','45.00','assets/dia-muertos.jpeg'),
('Atole','Bebidas calientes','Bebida caliente tradicional de maíz.','40.00','assets/cafe-pan.jpeg'),

('Tamales oaxaqueños','Comida','Tamales de estilo oaxaqueño.','35.00','assets/dia-muertos.jpeg'),
('Tamales normales','Comida','Tamales tradicionales mexicanos.','30.00','assets/dia-muertos.jpeg'),
('Pan de elote','Comida','Pan dulce de elote.','40.00','assets/cafe-pan.jpeg'),
('Churros','Comida','Churros tradicionales con azúcar.','35.00','assets/cafe-pan.jpeg'),
('Conchas','Comida','Pan dulce mexicano tradicional.','25.00','assets/cafe-pan.jpeg'),
('Pan de muerto','Comida','Pan tradicional de temporada.','35.00','assets/dia-muertos.jpeg'),
('Arroz con leche','Comida','Postre tradicional con canela.','40.00','assets/cafe-pan.jpeg'),
('Gelatinas','Comida','Gelatinas de sabores.','30.00','assets/cafe-pan.jpeg'),
('Buñuelos','Comida','Buñuelos tradicionales mexicanos.','35.00','assets/cafe-pan.jpeg'),
('Bocadillos típicos de México','Comida','Selección de bocadillos tradicionales.','55.00','assets/dia-muertos.jpeg'),

('Horchata','Bebidas frías','Bebida fresca de arroz con canela.','40.00','assets/cafe-pan.jpeg'),
('Pozol','Bebidas frías','Bebida tradicional mexicana de maíz.','45.00','assets/cafe-pan.jpeg'),
('Pozol de Nambimba','Bebidas frías','Preparación tradicional de Nambimba.','50.00','assets/cafe-pan.jpeg'),
('Raspados','Bebidas frías','Hielo raspado con sabor a elegir.','35.00','assets/cafe-pan.jpeg'),
('Frapes','Bebidas frías','Bebida fría de café estilo frappé.','60.00','assets/cafe-pan.jpeg');
