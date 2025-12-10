<?php 
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
?>

<div>

    <h1>403</h1>
    <p>Accès refusé</p>

    <?php if (!empty($message)): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php else: ?>
        <p>Vous n'avez pas la permission d'accéder à cette page.</p>
    <?php endif; ?>

    <div>

        <a href="/">Retour à l'accueil</a>

        <?php if (!empty($_SESSION['user_id'])): ?>
            <a href="/dashboard">Tableau de bord</a>
            <a href="/logout">Déconnexion</a>
        <?php else: ?>
            <a href="/login">Connexion</a>
        <?php endif; ?>

    </div>
</div>
