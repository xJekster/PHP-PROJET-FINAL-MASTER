<?php 
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$isLogged = isset($_SESSION['user_id']);
$isAdmin  = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
?>

<div style="min-height: 70vh;">
    <div>

        <h1>Bienvenue sur le site</h1>
        <p>Choisissez une action pour continuer.</p>

        <?php if ($isLogged): ?>

            <a href="/pages">Accéder au tableau de bord</a>

            <?php if ($isAdmin): ?>
                <a href="/admin">Accéder au dashboard administrateur</a>
            <?php endif; ?>

            <a href="/logout">Se déconnecter</a>

        <?php else: ?>

            <a href="/login">Se connecter</a>
            <a href="/register">Créer un compte</a>

        <?php endif; ?>

    </div>
</div>
