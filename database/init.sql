-- ================================================
-- 0. Create the Database (if it doesn't exist)
-- ================================================
CREATE DATABASE IF NOT EXISTS isla_otaku
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;

-- Use the database
USE isla_otaku;

-- ================================================
-- 1. Users Table
-- ================================================
CREATE TABLE IF NOT EXISTS Users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(100) NOT NULL,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB 
  DEFAULT CHARSET=utf8mb4 
  COLLATE=utf8mb4_general_ci;

-- ================================================
-- 2. Animes Table
-- ================================================
CREATE TABLE IF NOT EXISTS Animes (
    anime_id INT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    synopsis TEXT,
    image_url VARCHAR(300),
    release_date DATE,
    status VARCHAR(50),
    num_episodes INT DEFAULT 0,
    type VARCHAR(50)
) ENGINE=InnoDB 
  DEFAULT CHARSET=utf8mb4 
  COLLATE=utf8mb4_general_ci;

-- ================================================
-- 3. Genres Table
-- ================================================
CREATE TABLE IF NOT EXISTS Genres (
    genre_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB 
  DEFAULT CHARSET=utf8mb4 
  COLLATE=utf8mb4_general_ci;

-- ================================================
-- 4. Anime-Genre Relationship Table (AnimeGenres)
-- ================================================
CREATE TABLE IF NOT EXISTS AnimeGenres (
    anime_id INT,
    genre_id INT,
    PRIMARY KEY (anime_id, genre_id),
    FOREIGN KEY (anime_id) REFERENCES Animes(anime_id) ON DELETE CASCADE,
    FOREIGN KEY (genre_id) REFERENCES Genres(genre_id) ON DELETE CASCADE
) ENGINE=InnoDB 
  DEFAULT CHARSET=utf8mb4 
  COLLATE=utf8mb4_general_ci;

-- ================================================
-- 5. Lists Table
-- ================================================
CREATE TABLE IF NOT EXISTS Lists (
    list_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    anime_id INT,
    status VARCHAR(50) NOT NULL,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (anime_id) REFERENCES Animes(anime_id) ON DELETE CASCADE
) ENGINE=InnoDB 
  DEFAULT CHARSET=utf8mb4 
  COLLATE=utf8mb4_general_ci;

-- ================================================
-- 6. Reviews Table
-- ================================================
CREATE TABLE IF NOT EXISTS Reviews (
    review_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    anime_id INT,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    publication_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (anime_id) REFERENCES Animes(anime_id) ON DELETE CASCADE
) ENGINE=InnoDB 
  DEFAULT CHARSET=utf8mb4 
  COLLATE=utf8mb4_general_ci;
