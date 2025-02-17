-- Création de la base de données
CREATE DATABASE IF NOT EXISTS express_debate CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE express_debate;

-- Table des débats
CREATE TABLE IF NOT EXISTS debates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    votes_pour INT DEFAULT 0,
    votes_contre INT DEFAULT 0,
    created_at DATETIME NOT NULL,
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des commentaires
CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    debate_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME NOT NULL,
    FOREIGN KEY (debate_id) REFERENCES debates(id) ON DELETE CASCADE,
    INDEX idx_debate_id (debate_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table pour stocker les votes des utilisateurs
CREATE TABLE IF NOT EXISTS user_votes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    debate_id INT NOT NULL,
    user_ip VARCHAR(45) NOT NULL,
    vote_type ENUM('pour', 'contre') NOT NULL,
    created_at DATETIME NOT NULL,
    FOREIGN KEY (debate_id) REFERENCES debates(id) ON DELETE CASCADE,
    UNIQUE KEY unique_vote (debate_id, user_ip)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- No cleanup trigger needed since we want to keep all debates
