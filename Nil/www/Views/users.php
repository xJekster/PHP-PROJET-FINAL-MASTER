<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
?>

<h2>Gestion des Utilisateurs</h2>

<p>
    <a href="/admin">Retour au tableau de bord</a>
</p>

<?php if (!empty($success)): ?>
    <p style="color: green;"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<?php if (!empty($errors)): ?>
    <ul style="color: red;">
        <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php if (empty($users)): ?>

    <p>Aucun utilisateur enregistré.</p>

<?php else: ?>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Nom complet</th>
        <th>Email</th>
        <th>Rôle</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($users as $user): ?>
    <tr>
        <td><?= $user['id'] ?></td>
        <td><?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?></td>
        <td><?= htmlspecialchars($user['email']) ?></td>

        <td>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin' && $_SESSION['user_id'] !== $user['id']): ?>

                <form action="/users/role/<?= $user['id'] ?>" method="POST">
                    <select name="role">
                        <option value="user"   <?= $user['role'] === "user" ? "selected" : "" ?>>Utilisateur</option>
                        <option value="editor" <?= $user['role'] === "editor" ? "selected" : "" ?>>Éditeur</option>
                        <option value="admin"  <?= $user['role'] === "admin" ? "selected" : "" ?>>Admin</option>
                    </select>
                    <button type="submit">OK</button>
                </form>

            <?php else: ?>
                <?= htmlspecialchars($user['role']) ?>
            <?php endif; ?>
        </td>

        <td>
            <?php if ($user['id'] == $_SESSION['user_id']): ?>

                Vous

            <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>

                <a href="/users/delete/<?= $user['id'] ?>" onclick="return confirm('Supprimer cet utilisateur ?');">
                    Supprimer
                </a>

            <?php else: ?>

                Non autorisé

            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php endif; ?>
