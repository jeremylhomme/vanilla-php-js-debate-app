<?php
require_once 'db.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: debate.php');
    exit;
}

// Récupération du débat
$stmt = $pdo->prepare("SELECT * FROM debates WHERE id = ? AND created_at >= DATE_SUB(NOW(), INTERVAL 48 HOUR)");
$stmt->execute([$id]);
$debate = $stmt->fetch();

if (!$debate) {
    header('Location: debate.php');
    exit;
}

// Récupération des commentaires
$stmt = $pdo->prepare("SELECT * FROM comments WHERE debate_id = ? ORDER BY created_at DESC");
$stmt->execute([$id]);
$comments = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lait'Xpress Débat - <?= htmlspecialchars($debate['title']) ?></title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
</head>
<body class="single-debate">
    <div class="page-header">
        <div class="container">
            <a href="debate.php" class="nav-link">← Retour aux débats</a>
            <h1>Débat</h1>
            <a href="submit.php" class="cta-button">Proposer un débat</a>
        </div>
    </div>

    <main class="container">
        <article class="debate-card single">
            <h2 class="debate-title">
                <?= htmlspecialchars($debate['title']) ?>
            </h2>
            <p class="debate-description">
                <?= htmlspecialchars($debate['description']) ?>
            </p>
            <div class="vote-section">
                <div class="vote-buttons">
                    <button class="vote-btn pour" data-debate-id="<?= $debate['id'] ?>" data-vote-type="pour">
                        Pour
                        <span class="vote-count" id="votes-pour-<?= $debate['id'] ?>"><?= $debate['votes_pour'] ?></span>
                    </button>
                    <button class="vote-btn contre" data-debate-id="<?= $debate['id'] ?>" data-vote-type="contre">
                        Contre
                        <span class="vote-count" id="votes-contre-<?= $debate['id'] ?>"><?= $debate['votes_contre'] ?></span>
                    </button>
                </div>
                <div class="vote-message" id="vote-message-<?= $debate['id'] ?>"></div>
                <small>
                    Créé le <?= date('d/m/Y H:i', strtotime($debate['created_at'])) ?>
                </small>
            </div>
        </article>

        <section class="comments-section">
            <h3>Commentaires</h3>
            
            <form id="comment-form" data-debate-id="<?= $debate['id'] ?>" class="comment-form">
                <textarea 
                    id="comment-input" 
                    name="content" 
                    rows="3" 
                    placeholder="Votre commentaire..."
                    required
                ></textarea>
                <button type="submit" class="cta-button">Commenter</button>
            </form>

            <div id="comments-container">
                <?php if (!empty($comments)): ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="comment">
                            <p><?= htmlspecialchars($comment['content']) ?></p>
                            <small>
                                Le <?= date('d/m/Y H:i', strtotime($comment['created_at'])) ?>
                            </small>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-comments">Aucun commentaire pour le moment. Soyez le premier à réagir !</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <script src="script.js"></script>
</body>
</html>
