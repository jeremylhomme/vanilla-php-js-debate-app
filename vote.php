<?php
require_once 'db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$debate_id = filter_var($data['debate_id'] ?? null, FILTER_VALIDATE_INT);
$vote_type = $data['vote_type'] ?? '';

if (!$debate_id || !in_array($vote_type, ['pour', 'contre'])) {
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

    // Vérifier si l'utilisateur a déjà voté
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $stmt = $pdo->prepare("
        SELECT vote_type FROM user_votes 
        WHERE debate_id = ? AND user_ip = ?
    ");
    $stmt->execute([$debate_id, $user_ip]);
    $existing_vote = $stmt->fetch();

    if ($existing_vote) {
        echo json_encode([
            'success' => false, 
            'message' => 'Vous avez déjà voté',
            'vote_type' => $existing_vote['vote_type']
        ]);
        exit;
    }

    // Début de la transaction
    $pdo->beginTransaction();

    // Incrémenter le compteur de votes
    $column = $vote_type === 'pour' ? 'votes_pour' : 'votes_contre';
    $stmt = $pdo->prepare("
        UPDATE debates 
        SET $column = $column + 1 
        WHERE id = ?
    ");
    $stmt->execute([$debate_id]);

    // Enregistrer le vote de l'utilisateur
    $stmt = $pdo->prepare("
        INSERT INTO user_votes (debate_id, user_ip, vote_type, created_at)
        VALUES (?, ?, ?, NOW())
    ");
    $stmt->execute([$debate_id, $user_ip, $vote_type]);

    // Valider la transaction
    $pdo->commit();

    // Récupérer les nouveaux totaux
    $stmt = $pdo->prepare("
        SELECT votes_pour, votes_contre 
        FROM debates 
        WHERE id = ?
    ");
    $stmt->execute([$debate_id]);
    $votes = $stmt->fetch();

    echo json_encode([
        'success' => true,
        'message' => 'Merci d\'avoir voté',
        'votes_pour' => $votes['votes_pour'],
        'votes_contre' => $votes['votes_contre'],
        'vote_type' => $vote_type
    ]);

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['success' => false, 'message' => 'Erreur lors du vote']);
}
