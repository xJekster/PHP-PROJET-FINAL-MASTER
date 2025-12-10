<?php
$errors  = $errors  ?? [];
$success = $success ?? null;
$old     = $old     ?? [];
?>

<div>
    <div>
        <div>
            <div>
                <div>
                    Inscription
                </div>
                <div>

                    <?php if (!empty($errors)): ?>
                        <div>
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($success)): ?>
                        <div>
                            <?= htmlspecialchars($success) ?>
                        </div>
                    <?php endif; ?>

                    <form action="/register" method="POST">
                        
                        <div>
                            <label for="firstname">Prénom</label>
                            <input 
                                type="text" 
                                id="firstname" 
                                name="firstname" 
                                required
                                value="<?= htmlspecialchars($old['firstname'] ?? '') ?>"
                            >
                        </div>

                        <div>
                            <label for="lastname">Nom</label>
                            <input 
                                type="text" 
                                id="lastname" 
                                name="lastname" 
                                required
                                value="<?= htmlspecialchars($old['lastname'] ?? '') ?>"
                            >
                        </div>

                        <div>
                            <label for="email">Adresse Email</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                required
                                value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                            >
                        </div>

                        <div>
                            <label for="password">Mot de passe</label>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                required
                            >
                            <small>
                                8 caractères minimum, incluant une majuscule, une minuscule, un chiffre et un caractère spécial.
                            </small>
                        </div>

                        <div>
                            <label for="password_confirm" >Confirmer le mot de passe</label>
                            <input 
                                type="password" 
                                id="password_confirm" 
                                name="password_confirm" 
                                required
                            >
                        </div>

                        <button type="submit">S'inscrire</button>
                    </form>

                    <p>Déjà un compte ? <a href="/login">Connectez-vous ici</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
