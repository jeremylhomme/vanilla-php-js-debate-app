<?php
require_once 'db.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$debate_id = filter_var($data['debate_id'] ?? null, FILTER_VALIDATE_INT);
$content = trim($data['content'] ?? '');

if (!$debate_id || empty($content)) {
    echo json_encode(['success' => false, 'message' => 'Données invalides']);
    exit;
}

try {
    // Vérifier si le débat existe et n'a pas expiré
    $stmt = $pdo->prepare("
        SELECT id FROM debates 
        WHERE id = ? AND created_at >= DATE_SUB(NOW(), INTERVAL 48 HOUR)
    ");
    $stmt->execute([$debate_id]);
    
    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Débat non trouvé ou expiré']);
        exit;
    }

    // Insérer le commentaire
    $stmt = $pdo->prepare("
        INSERT INTO comments (debate_id, content, created_at) 
        VALUES (?, ?, NOW())
    ");
    $stmt->execute([$debate_id, $content]);
    
    // Récupérer le commentaire créé
    $stmt = $pdo->prepare("
        SELECT * FROM comments 
        WHERE id = ? 
    ");
    $stmt->execute([$pdo->lastInsertId()]);
    $comment = $stmt->fetch();
    
    echo json_encode([
        'success' => true,
        'comment' => [
            'content' => $comment['content'],
            'created_at' => date('d/m/Y H:i', strtotime($comment['created_at']))
        ]
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout du commentaire']);
}
