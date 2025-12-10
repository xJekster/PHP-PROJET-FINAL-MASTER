<?php 
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
?>

<h2>Pages</h2>
<a href="/create">Ajouter</a>

<?php if (empty($pages)): ?>

    <div>
        Aucune page n'existe pour le moment. Cliquez sur "Ajouter" pour en créer une.
    </div>

<?php else: ?>

<table>
    <tr>
        <th>Titre</th>
        <th>Slug</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($pages as $page): ?>
        <tr>
            <td><?= htmlspecialchars($page['title']) ?></td>
            <td><?= htmlspecialchars($page['slug']) ?></td>
            <td>

                <a href="/show/<?= htmlspecialchars($page['slug']) ?>" target="_blank">
                    Voir
                </a>

                <?php 
                $isOwner = isset($_SESSION['user_id']) && $_SESSION['user_id'] === $page['user_id'];
                $isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
                ?>

                <?php if ($isOwner || $isAdmin): ?>

                    <a href="/edit/<?= $page['id'] ?>">
                        Modifier
                    </a>

                    <a href="/delete/<?= $page['id'] ?>"
                       onclick="return confirm('Voulez-vous vraiment supprimer cette page ?');">
                        Supprimer
                    </a>

                <?php else: ?>

                    <span>
                        Non autorisé
                    </span>

                <?php endif; ?>

            </td>
        </tr>
    <?php endforeach; ?>

</table>

<?php endif; ?>
