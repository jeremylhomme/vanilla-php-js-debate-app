<?php
require_once 'db.php';

// Récupération des débats les plus votés
$stmt = $pdo->query("
    SELECT *, (votes_pour + votes_contre) as total_votes 
    FROM debates 
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 48 HOUR)
    ORDER BY total_votes DESC, created_at DESC 
    LIMIT 3
");
$debates = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lait'Xpress Débat - Accueil</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
</head>
<body class="home">
    <main class="fullscreen-hero">
        <div class="overlay"></div>
        <div class="hero-content">
            <h1>Lait'Xpress Débat</h1>
            <p class="hero-description">Partagez vos idées lactées et confrontez vos opinions.</br> 100% anonyme.</p>
            <a href="debate.php" class="cta-button">Découvrir les débats</a>
        </div>
        <img src="public/images/main-image.jpg" alt="Lait'Xpress Débat" class="hero-background">
    </main>
    <script src="script.js"></script>
</body>
</html>
