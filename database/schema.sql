CREATE DATABASE IF NOT EXISTS farmacia CHARACTER SET utf8mb4;
USE farmacia;

CREATE TABLE categorias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL
);

CREATE TABLE productos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  categoria_id INT NOT NULL,
  nombre VARCHAR(100) NOT NULL,
  descripcion VARCHAR(255) NOT NULL,
  precio DECIMAL(8,2) NOT NULL,
  stock INT NOT NULL DEFAULT 0,
  requiere_receta TINYINT(1) NOT NULL DEFAULT 0,
  imagen VARCHAR(255) NULL,
  FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);

CREATE TABLE pedidos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  telefono VARCHAR(30) NOT NULL,
  email VARCHAR(100) NULL,
  direccion VARCHAR(200) NOT NULL,
  total DECIMAL(10,2) NOT NULL,
  fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE detalle_pedido (
  id INT AUTO_INCREMENT PRIMARY KEY,
  pedido_id INT NOT NULL,
  producto_id INT NOT NULL,
  cantidad INT NOT NULL,
  precio_unitario DECIMAL(8,2) NOT NULL,
  FOREIGN KEY (pedido_id) REFERENCES pedidos(id),
  FOREIGN KEY (producto_id) REFERENCES productos(id)
);

CREATE TABLE mensajes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  mensaje TEXT NOT NULL,
  fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO categorias (nombre) VALUES
('Analgésicos'),('Antibióticos'),('Alergias'),('Vitaminas'),('Higiene y cuidado');

INSERT INTO productos (categoria_id, nombre, descripcion, precio, stock, requiere_receta) VALUES
(1,'Paracetamol 500 mg','Alivia el dolor leve y la fiebre. Caja de 20 tabletas.',12.50,120,0),
(1,'Ibuprofeno 400 mg','Antiinflamatorio para dolor muscular y de cabeza. Caja de 10.',18.00,8,0),
(1,'Diclofenaco gel','Gel para dolores musculares y articulares. Tubo de 50 g.',22.00,30,0),
(2,'Amoxicilina 500 mg','Antibiótico de amplio espectro. Caja de 12 cápsulas.',35.90,40,1),
(2,'Azitromicina 500 mg','Antibiótico para infecciones respiratorias. Caja de 3.',48.00,15,1),
(3,'Loratadina 10 mg','Antihistamínico para rinitis y alergias. Caja de 10.',15.00,65,0),
(3,'Suero fisiológico nasal','Limpieza nasal. Frasco de 30 ml.',11.00,50,0),
(4,'Vitamina C 1 g','Refuerza las defensas. Tubo de 10 efervescentes.',25.00,70,0),
(4,'Complejo B','Vitaminas del grupo B. Frasco de 30 grageas.',28.50,0,0),
(5,'Alcohol en gel 250 ml','Desinfectante de manos.',14.00,90,0),
(5,'Barbijos x 50','Caja de 50 barbijos descartables.',30.00,25,0),
(5,'Termómetro digital','Medición rápida de temperatura corporal.',35.00,12,0);
