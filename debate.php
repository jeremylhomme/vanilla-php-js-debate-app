<?php
require_once 'db.php';

// Récupération des débats (moins de 48h)
$stmt = $pdo->query("
    SELECT d.*, 
           (votes_pour + votes_contre) as total_votes,
           COUNT(c.id) as comment_count
    FROM debates d
    LEFT JOIN comments c ON d.id = c.debate_id
    WHERE d.created_at >= DATE_SUB(NOW(), INTERVAL 48 HOUR)
    GROUP BY d.id
    ORDER BY total_votes DESC, d.created_at DESC
");
$debates = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lait'Xpress Débat - Liste des débats</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
</head>
<body class="debates">
    <div class="page-header">
        <div class="container">
            <a href="index.php" class="nav-link">← Accueil</a>
            <h1>Débats en cours</h1>
            <a href="submit.php" class="cta-button">Proposer un débat</a>
        </div>
    </div>

    <main class="container">
        <section class="debates-list">
            <?php if (!empty($debates)): ?>
                <?php foreach ($debates as $debate): ?>
                    <article class="debate-card">
                        <h2 class="debate-title">
                            <a href="single-debate.php?id=<?= htmlspecialchars($debate['id']) ?>" class="debate-link">
                                <?= htmlspecialchars($debate['title']) ?>
                            </a>
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
                            <div class="debate-meta">
                                <small>
                                    Créé le <?= date('d/m/Y H:i', strtotime($debate['created_at'])) ?>
                                </small>
                                <small class="comment-count">
                                    <?= $debate['comment_count'] ?> commentaire<?= $debate['comment_count'] > 1 ? 's' : '' ?>
                                </small>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <p>Aucun débat en cours.</p>
                    <p>Soyez le premier à en proposer un !</p>
                    <a href="submit.php" class="cta-button">Proposer un débat</a>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <script src="script.js"></script>
</body>
</html>
