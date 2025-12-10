<?php 
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
?>

<div>

    <div>
        <div>

            <?php if (!empty($_SESSION['user_id'])): ?>

                <div >
                    Vous êtes déjà connecté.
                </div>

                <div>
                    <a href="/pages">Accéder au tableau de bord</a>
                    <a href="/logout">Se déconnecter</a>
                </div>

            <?php else: ?>

                <div>
                    <div>
                        Connexion
                    </div>
                    <div>

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

                            <div>
                                <label for="email">Email :</label>
                                <input type="email" id="email" name="email" required>
                            </div>

                            <div class="mb-3">
                                <label for="password">Mot de passe :</label>
                                <input type="password" id="password" name="password" required>
                            </div>

                            <button type="submit">Se connecter</button>
                        </form>

                        <div>
                            <a href="/forgot">Mot de passe oublié ?</a>
                        </div>

                        <div>
                            Pas de compte ?
                            <a href="/register">Créer un compte</a>
                        </div>

                    </div>
                </div>

            <?php endif; ?>
