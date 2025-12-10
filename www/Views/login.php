<?php 
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
?>

<div class="container mt-5">

    <div class="row justify-content-center">
        <div class="col-md-6">

            <?php if (!empty($_SESSION['user_id'])): ?>

                <div class="alert alert-success text-center">
                    Vous êtes déjà connecté.
                </div>

                <div class="d-grid gap-3">
                    <a href="/dashboard" class="btn btn-primary">Accéder au tableau de bord</a>
                    <a href="/logout" class="btn btn-danger">Se déconnecter</a>
                </div>

            <?php else: ?>

                <div class="card">
                    <div class="card-header text-center">
                        Connexion
                    </div>
                    <div class="card-body">

                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($message)): ?>
                            <div class="alert alert-info">
                                <?= htmlspecialchars($message) ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="/login">

                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">

                            <div class="mb-3">
                                <label for="email" class="form-label">Email :</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe :</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                        </form>

                        <div class="mt-3 text-center">
                            <a href="/forgot">Mot de passe oublié ?</a>
                        </div>

                        <div class="mt-3 text-center">
                            Pas de compte ?
                            <a href="/register">Créer un compte</a>
                        </div>

                    </div>
                </div>

            <?php endif; ?>
