SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;
SET character_set_connection=utf8mb4;

-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS express_debate
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE express_debate;

-- Create debates table
CREATE TABLE IF NOT EXISTS debates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    votes_pour INT DEFAULT 0,
    votes_contre INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create comments table
CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    debate_id INT NOT NULL,
    content TEXT NOT NULL,
    author VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (debate_id) REFERENCES debates(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create user_votes table
CREATE TABLE IF NOT EXISTS user_votes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    debate_id INT NOT NULL,
    user_ip VARCHAR(45) NOT NULL,
    vote_type ENUM('pour', 'contre') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (debate_id) REFERENCES debates(id) ON DELETE CASCADE,
    UNIQUE KEY unique_vote (debate_id, user_ip)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert debates
INSERT INTO debates (title, description, votes_pour, votes_contre, created_at) VALUES
(
    'Le lait est-il l''un des aliments les plus complets ?',
    'Avec ses protéines, son calcium et ses vitamines, le lait est souvent décrit comme un super-aliment. Fait-il partie des indispensables de notre alimentation ?',
    0, 0, NOW()
),
(
    'Le lait est-il la boisson idéale pour bien commencer la journée ?',
    'Beaucoup de gens associent le lait au petit-déjeuner. Est-ce une habitude culturelle ou a-t-il vraiment des bienfaits pour l''énergie et la concentration ?',
    0, 0, NOW()
),
(
    'Le lait peut-il être une alternative naturelle aux boissons énergétiques ?',
    'Certains sportifs le considèrent comme un excellent moyen de récupérer après l''effort. Peut-il rivaliser avec les boissons enrichies du commerce ?',
    0, 0, NOW()
),
(
    'Le lait joue-t-il un rôle clé dans la gastronomie ?',
    'Beurre, crème, fromages, yaourts… Le lait est un ingrédient essentiel dans de nombreuses recettes. Peut-on imaginer une cuisine sans lait ?',
    0, 0, NOW()
),
(
    'Le lait est-il un allié pour une bonne hydratation ?',
    'On parle souvent de l''eau, mais le lait est composé à plus de 85 % d''eau. Peut-il contribuer à une bonne hydratation au quotidien ?',
    0, 0, NOW()
),
(
    'Le lait est-il un bon allié pour la croissance des enfants ?',
    'Depuis toujours, on recommande le lait pour la croissance des os et des muscles. Est-il vraiment un incontournable pour le bon développement des plus jeunes ?',
    0, 0, NOW()
),
(
    'Les bienfaits du lait chaud : mythe ou réalité ?',
    'Beaucoup de personnes boivent du lait chaud pour mieux dormir ou se détendre. Est-ce un effet placebo ou a-t-il de vraies vertus relaxantes ?',
    0, 0, NOW()
),
(
    'Le lait est-il un ingrédient clé des traditions culinaires à travers le monde ?',
    'Chai indien, dulce de leche, fromage français… Le lait est à la base de nombreuses spécialités. Quelle est son importance dans les différentes cultures ?',
    0, 0, NOW()
),
(
    'Fromage, yaourt, lait fermenté : le lait est-il l''un des aliments les plus polyvalents ?',
    'Le lait est la base de nombreux produits transformés aux saveurs variées. Cette diversité en fait-elle un incontournable de l''alimentation ?',
    0, 0, NOW()
),
(
    'Le lait peut-il jouer un rôle dans une alimentation plus durable ?',
    'Avec des innovations comme le lait bio ou les circuits courts, la filière laitière évolue. Comment peut-elle répondre aux enjeux environnementaux ?',
    0, 0, NOW()
),
(
    'Les nouvelles technologies peuvent-elles améliorer la production laitière ?',
    'Entre robots de traite et suivi des troupeaux, l''innovation permet d''optimiser la production laitière. Ces avancées sont-elles l''avenir du secteur ?',
    0, 0, NOW()
),
(
    'Le lait est-il l''ingrédient parfait pour créer des desserts gourmands ?',
    'Flan, crème brûlée, riz au lait… Le lait est un élément clé de nombreux desserts. Peut-on imaginer une pâtisserie sans lui ?',
    0, 0, NOW()
),
(
    'Peut-on redécouvrir le lait avec de nouvelles recettes et usages ?',
    'Lait infusé, boissons aromatisées, nouvelles textures… Le lait peut-il se réinventer pour séduire de nouveaux consommateurs ?',
    0, 0, NOW()
),
(
    'Le lait est-il un élément de bien-être au quotidien ?',
    'En plus de l''alimentation, le lait est utilisé en cosmétique pour ses bienfaits pour la peau. A-t-il plus d''usages que ce que l''on imagine ?',
    0, 0, NOW()
),
(
    'Le lait fait-il partie du patrimoine gastronomique français ?',
    'Beurre, fromages, crème… Le lait est à la base de nombreuses spécialités françaises. Est-il un élément clé de notre identité culinaire ?',
    0, 0, NOW()
);
