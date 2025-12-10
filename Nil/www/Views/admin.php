<?php 
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
?>

<div>
    <h1>Espace Administrateur</h1>

    <p>Bonjour <strong><?= htmlspecialchars($user['firstname']) ?></strong>, vous avez accès au panneau d'administration.</p>

    <div>
        <a href="/users">Gestion des utilisateurs</a>
        <a href="/pages">Gestion des pages</a>
        <a href="/logout">Déconnexion</a>
    </div>
</div>
