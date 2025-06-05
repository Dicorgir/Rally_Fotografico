

-- Tabla de Usuarios
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(150) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    fecha_nacimiento DATE,
    pais VARCHAR(100),
    genero ENUM('masculino', 'femenino', 'otro'),
    foto_perfil VARCHAR(255),
    rol ENUM('admin', 'participante') NOT NULL DEFAULT 'participante',
    estado ENUM('activo', 'inactivo', 'suspendido') NOT NULL DEFAULT 'activo',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de Configuración del Rally (multiples rallies)
CREATE TABLE rallies (
    id_rally INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    max_fotos_por_participante INT NOT NULL,
    fecha_inicio_votacion DATE NOT NULL,
    fecha_fin_votacion DATE NOT NULL
);

-- Tabla de Fotografías (relacionada a los rallies)
CREATE TABLE fotografias (
    id_fotografia INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_rally INT NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    descripcion TEXT,
    imagen_base64 LONGTEXT NOT NULL, -- Se mantiene en Base64
    estado ENUM('pendiente', 'admitida', 'rechazada') NOT NULL DEFAULT 'pendiente',
    total_votos INT DEFAULT 0,
    fecha_subida TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_rally) REFERENCES rallies(id_rally) ON DELETE CASCADE
);

-- Tabla de Votaciones
CREATE TABLE votaciones (
    id_votacion INT AUTO_INCREMENT PRIMARY KEY,
    id_fotografia INT NOT NULL,
    id_usuario INT NULL,
    ip VARCHAR(45) NOT NULL,
    fecha_voto TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_fotografia) REFERENCES fotografias(id_fotografia) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE SET NULL,
    CONSTRAINT voto_unico UNIQUE (id_fotografia, ip) -- Un voto por IP por imagen
);

-- Tabla de Comentarios
CREATE TABLE comentarios (
    id_comentario INT AUTO_INCREMENT PRIMARY KEY,
    id_fotografia INT NOT NULL,
    id_usuario INT NULL,
    comentario TEXT NOT NULL,
    fecha_comentario TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_fotografia) REFERENCES fotografias(id_fotografia) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    CONSTRAINT comentario_unico UNIQUE (id_fotografia, id_usuario, comentario(100)) -- Evitar spam de comentarios idénticos
);

-- Usuario administrador por defecto (contraseña se debe cambiar luego a bcrypt en PHP)
INSERT INTO usuarios (nombre_completo, email, contrasena, rol) VALUES 
('Admin', 'admin@rally.com', '$2y$10$G9w3MQEerNzt5drpHI6Cku5NGB/1Z1On/5O6hlF5Z7J71QGqN7W1C', 'admin');

INSERT INTO rallies (nombre, fecha_inicio, fecha_fin, max_fotos_por_participante, fecha_inicio_votacion, fecha_fin_votacion) VALUES 
(
    'Rally Primavera 2025',
    '2025-05-01',
    '2025-06-30',
    5,
    '2025-06-01',
    '2025-06-30'
),
(
    'Rally Verano 2025',
    '2025-07-01',
    '2025-08-31',
    5,
    '2025-08-01',
    '2025-08-31'
),
(
    'Rally Otoño 2025',
    '2025-09-15',
    '2025-11-15',
    5,
    '2025-11-01',
    '2025-11-15'
);