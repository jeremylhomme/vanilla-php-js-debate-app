<?php
require_once 'db.php';

// Only process POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    
    $response = ['success' => false];
    
    if ($title && $description) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO debates (title, description, votes_pour, votes_contre, created_at) 
                VALUES (?, ?, 0, 0, NOW())
            ");
            
            $stmt->execute([$title, $description]);
            $response = ['success' => true];
        } catch (PDOException $e) {
            $response['message'] = "Erreur lors de l'enregistrement du débat";
        }
    } else {
        $response['message'] = "Veuillez remplir tous les champs";
    }
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit; // Important: stop execution here for POST requests
}

// Only show HTML for GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lait'Xpress Débat - Proposer un débat</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
</head>
<body class="submit">
    <main class="fullscreen-hero">
        <div class="overlay"></div>
        <div class="hero-content">
            <h1>Proposer un débat</h1>
            <form id="submit-debate-form" class="submit-form">
                <div class="form-group">
                    <input 
                        type="text" 
                        id="title" 
                        name="title" 
                        required 
                        maxlength="255"
                        placeholder="Titre du débat"
                    >
                </div>

                <div class="form-group">
                    <textarea 
                        id="description" 
                        name="description" 
                        required 
                        rows="5"
                        placeholder="Décrivez votre sujet de débat..."
                    ></textarea>
                </div>

                <button type="submit" class="cta-button">Publier le débat</button>
            </form>
        </div>
        <img src="public/images/debate-image.jpg" alt="Lait'Xpress Débat" class="hero-background">
    </main>

    <script src="script.js"></script>
</body>
</html>
<?php
}
?>
