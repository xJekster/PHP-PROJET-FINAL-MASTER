<?php 
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
?>

<div>

    <h1>404</h1>
    <p>La page que vous recherchez n'existe pas.</p>

    <?php if (!empty($message)): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <div>

        <a href="/">Retour à l'accueil</a>

        <?php if (!empty($_SESSION['user_id'])): ?>
            <a href="/pages">Retour au tableau de bord</a>
        <?php else: ?>
            <a href="/login">Connexion</a>
        <?php endif; ?>

    </div>
</div>
