DROP DATABASE IF EXISTS BFC;
CREATE DATABASE BFC CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE BFC;

-- Tabla Administradores
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla Bailarines
CREATE TABLE bailarines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    telefono VARCHAR(20),
    genero ENUM('M', 'F', 'Otro') DEFAULT 'Otro',
    password VARCHAR(255) NOT NULL,
    activo BOOLEAN DEFAULT 1,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla Vestuarios (Inventario)
CREATE TABLE vestuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    talla VARCHAR(10),
    genero ENUM('Hombre', 'Mujer', 'Unisex') NOT NULL,
    cantidad_total INT DEFAULT 1,
    cantidad_disponible INT DEFAULT 1,
    imagen VARCHAR(255), -- Ruta de la imagen
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla Funciones (Eventos/Presentaciones)
CREATE TABLE funciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    fecha DATETIME NOT NULL,
    lugar VARCHAR(150),
    descripcion TEXT,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla Préstamos
CREATE TABLE prestamos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bailarin_id INT NOT NULL,
    vestuario_id INT NOT NULL,
    funcion_id INT NOT NULL,
    fecha_solicitud TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_devolucion DATETIME,
    estado ENUM('pendiente', 'aprobado', 'rechazado', 'devuelto') DEFAULT 'pendiente',
    observaciones TEXT,
    FOREIGN KEY (bailarin_id) REFERENCES bailarines(id) ON DELETE CASCADE,
    FOREIGN KEY (vestuario_id) REFERENCES vestuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (funcion_id) REFERENCES funciones(id) ON DELETE CASCADE
);

-- Insertar Datos de Prueba (Seed)

-- Admin (password: admin123)
INSERT INTO admin (usuario, password, nombre) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador Principal');

-- Bailarines (password: bailarin123)
INSERT INTO bailarines (nombre, email, telefono, genero, password) VALUES 
('Ana García', 'ana@bfc.com', '70712345', 'F', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Juan Perez', 'juan@bfc.com', '70754321', 'M', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Vestuarios
INSERT INTO vestuarios (nombre, descripcion, talla, genero, cantidad_total, cantidad_disponible, imagen) VALUES 
('Traje de Caporal (H)', 'Traje completo con botas y sombrero', 'L', 'Hombre', 10, 10, 'caporal_h.jpg'),
('Pollera de Cueca', 'Pollera tradicional cochabambina', 'M', 'Mujer', 15, 15, 'cueca_m.jpg'),
('Poncho Tinkus', 'Poncho colorido', 'Unisex', 'Unisex', 20, 20, 'tinkus.jpg');

-- Funciones
INSERT INTO funciones (nombre, fecha, lugar, descripcion) VALUES 
('Gran Gala Anual', '2024-12-15 19:00:00', 'Teatro Achá', 'Presentación de fin de año'),
('Festival de Danza', '2024-09-21 18:00:00', 'Estadio Félix Capriles', 'Participación en festival nacional');