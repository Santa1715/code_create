-- ============================================
-- PROYECTO: CODE & CREATE
-- Taller de Desarrollo Web para Jóvenes
-- Base de Datos - Torre Guayacán
-- 
-- Autor: Leonel Rondón - CI: 32.079.527
-- Universidad Bicentenaria de Aragua
-- Servicio Comunitario - Ingeniería de Sistemas
-- ============================================

CREATE DATABASE IF NOT EXISTS code_create 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE code_create;

-- TABLA 1: MENSAJES (Formulario de Contacto)
CREATE TABLE IF NOT EXISTS mensajes (
    id INT(11) NOT NULL AUTO_INCREMENT COMMENT 'ID único del mensaje',
    nombre VARCHAR(50) NOT NULL COMMENT 'Nombre del remitente',
    email VARCHAR(150) NOT NULL COMMENT 'Correo electrónico del remitente',
    mensaje TEXT NOT NULL COMMENT 'Contenido del mensaje',
    ip VARCHAR(45) DEFAULT NULL COMMENT 'Dirección IP del remitente',
    fecha TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha y hora del mensaje',
    PRIMARY KEY (id),
    KEY idx_fecha (fecha),
    KEY idx_email (email)
) ENGINE=InnoDB 
DEFAULT CHARSET=utf8mb4 
COLLATE=utf8mb4_unicode_ci
COMMENT='Mensajes recibidos del formulario de contacto';

-- TABLA 2: PARTICIPANTES (Taller Code & Create)
CREATE TABLE IF NOT EXISTS participantes (
    id INT(11) NOT NULL AUTO_INCREMENT COMMENT 'ID único del participante',
    nombre VARCHAR(100) NOT NULL COMMENT 'Nombre completo del participante',
    edad INT(3) NOT NULL COMMENT 'Edad del participante (15-25 años)',
    email VARCHAR(150) NOT NULL COMMENT 'Correo electrónico',
    github_user VARCHAR(100) DEFAULT NULL COMMENT 'Usuario de GitHub',
    torre VARCHAR(50) DEFAULT 'Guayacán' COMMENT 'Torre del conjunto residencial',
    fecha_registro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de registro',
    PRIMARY KEY (id),
    UNIQUE KEY uk_email (email),
    KEY idx_fecha_registro (fecha_registro),
    KEY idx_torre (torre)
) ENGINE=InnoDB 
DEFAULT CHARSET=utf8mb4 
COLLATE=utf8mb4_unicode_ci
COMMENT='Participantes del taller Code & Create - Torre Guayacán';

-- DATOS DE EJEMPLO
INSERT INTO mensajes (nombre, email, mensaje, ip) VALUES 
('Juan Pérez', 'juan.perez@email.com', 'Hola, estoy muy interesado en el taller de desarrollo web. ¿Cuándo inicia?', '::1'),
('María Gómez', 'maria.gomez@email.com', 'Excelente iniciativa. Me gustaría participar para aprender a crear mi propia página web.', '::1');

INSERT INTO participantes (nombre, edad, email, github_user, torre, fecha_registro) VALUES 
('Carlos Rodríguez', 18, 'carlos.rodriguez@email.com', 'carlosr-dev', 'Guayacán', '2026-06-01 10:00:00'),
('Ana Martínez', 20, 'ana.martinez@email.com', 'anamartinez-web', 'Guayacán', '2026-06-01 10:15:00'),
('Luis Hernández', 17, 'luis.hernandez@email.com', 'luiscode', 'Guayacán', '2026-06-01 10:30:00'),
('María González', 19, 'maria.gonzalez@email.com', 'mariagonzalez', 'Guayacán', '2026-06-02 09:00:00');