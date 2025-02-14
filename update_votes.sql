-- Modification de la table debates
ALTER TABLE debates 
DROP COLUMN votes,
ADD COLUMN votes_pour INT DEFAULT 0,
ADD COLUMN votes_contre INT DEFAULT 0;

-- Création de la table pour stocker les votes des utilisateurs
CREATE TABLE user_votes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    debate_id INT NOT NULL,
    user_ip VARCHAR(45) NOT NULL,
    vote_type ENUM('pour', 'contre') NOT NULL,
    created_at DATETIME NOT NULL,
    FOREIGN KEY (debate_id) REFERENCES debates(id) ON DELETE CASCADE,
    UNIQUE KEY unique_vote (debate_id, user_ip)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
