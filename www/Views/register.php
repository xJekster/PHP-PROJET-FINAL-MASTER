<?php
// Variables reçues depuis Render
$errors  = $errors  ?? [];
$success = $success ?? null;
$old     = $old     ?? [];
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Inscription
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

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success">
                            <?= htmlspecialchars($success) ?>
                        </div>
                    <?php endif; ?>

                    <form action="/register" method="POST">
                        
                        <div class="mb-3">
                            <label for="firstname" class="form-label">Prénom</label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="firstname" 
                                name="firstname" 
                                required
                                value="<?= htmlspecialchars($old['firstname'] ?? '') ?>"
                            >
                        </div>

                        <div class="mb-3">
                            <label for="lastname" class="form-label">Nom</label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="lastname" 
                                name="lastname" 
                                required
                                value="<?= htmlspecialchars($old['lastname'] ?? '') ?>"
                            >
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse Email</label>
                            <input 
                                type="email" 
                                class="form-control" 
                                id="email" 
                                name="email" 
                                required
                                value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                            >
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input 
                                type="password" 
                                class="form-control" 
                                id="password" 
                                name="password" 
                                required
                            >
                            <small class="form-text text-muted">
                                8 caractères minimum, incluant une majuscule, une minuscule, un chiffre et un caractère spécial.
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirm" class="form-label">Confirmer le mot de passe</label>
                            <input 
                                type="password" 
                                class="form-control" 
                                id="password_confirm" 
                                name="password_confirm" 
                                required
                            >
                        </div>

                        <button type="submit" class="btn btn-primary">S'inscrire</button>
                    </form>

                    <p class="mt-3">Déjà un compte ? <a href="/login">Connectez-vous ici</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
