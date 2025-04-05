-- ================================================
-- 0. Creación de la Base de Datos (Si no existe)
-- ================================================
CREATE DATABASE IF NOT EXISTS isla_otaku
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;

-- Utilizar la base de datos
USE isla_otaku;

-- ================================================
-- 1. Tabla de Usuarios
-- ================================================
CREATE TABLE IF NOT EXISTS Usuarios (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(100) UNIQUE NOT NULL,
    contrasena VARCHAR(100) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB 
  DEFAULT CHARSET=utf8mb4 
  COLLATE=utf8mb4_general_ci;

-- ================================================
-- 2. Tabla de Animes
-- ================================================
CREATE TABLE IF NOT EXISTS Animes (
    id_anime INT PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    sinopsis TEXT,
    imagen_url VARCHAR(300),
    fecha_emision DATE,
    estado VARCHAR(50),
    num_episodios INT DEFAULT 0,
    tipo VARCHAR(50)
) ENGINE=InnoDB 
  DEFAULT CHARSET=utf8mb4 
  COLLATE=utf8mb4_general_ci;

-- ================================================
-- 3. Tabla de Listas
-- ================================================
CREATE TABLE IF NOT EXISTS Listas (
    id_lista INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT,
    id_anime INT,
    estado VARCHAR(50) NOT NULL,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_anime) REFERENCES Animes(id_anime) ON DELETE CASCADE
) ENGINE=InnoDB 
  DEFAULT CHARSET=utf8mb4 
  COLLATE=utf8mb4_general_ci;

-- ================================================
-- 4. Tabla de Reseñas
-- ================================================
CREATE TABLE IF NOT EXISTS Reseñas (
    id_resena INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT,
    id_anime INT,
    puntuacion INT CHECK (puntuacion BETWEEN 1 AND 5),
    comentario TEXT,
    fecha_publicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_anime) REFERENCES Animes(id_anime) ON DELETE CASCADE
) ENGINE=InnoDB 
  DEFAULT CHARSET=utf8mb4 
  COLLATE=utf8mb4_general_ci;
