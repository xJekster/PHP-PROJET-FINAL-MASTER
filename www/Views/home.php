<?php 
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
} 
?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="text-center">

        <h1 class="mb-4">Bienvenue sur le site</h1>
        <p class="mb-4">Choisissez une action pour continuer.</p>

        <?php if (!empty($_SESSION['user_id'])): ?>
            
            <a href="/pages" class="btn btn-primary btn-lg mb-3 w-100">Accéder au tableau de bord</a>
            <a href="/logout" class="btn btn-danger btn-lg w-100">Déconnexion</a>

        <?php else: ?>

            <a href="/login" class="btn btn-primary btn-lg mb-3 w-100">Se connecter</a>
            <a href="/register" class="btn btn-success btn-lg w-100">Créer un compte</a>

        <?php endif; ?>

    </div>
</div>
