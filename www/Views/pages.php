<h2>Pages</h2>
<a href="/create" class="btn btn-success mb-3">Ajouter</a>

<?php if (empty($pages)): ?>

    <div class="alert alert-warning">
        Aucune page n'existe pour le moment. Cliquez sur "Ajouter" pour en créer une.
    </div>

<?php else: ?>

<table class="table">
    <tr>
        <th>Titre</th>
        <th>Slug</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($pages as $page): ?>
        <tr>
            <td><?= htmlspecialchars($page['title']) ?></td>
            <td><?= htmlspecialchars($page['slug']) ?></td>
            <td class="d-flex gap-2 align-items-center">

                <a href="/show/<?= htmlspecialchars($page['slug']) ?>" 
                   target="_blank"
                   class="btn btn-info">
                    Voir
                </a>

                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] === $page['user_id']): ?>

                    <a href="/edit/<?= $page['id'] ?>" class="btn btn-warning">
                        Modifier
                    </a>

                    <a href="/delete/<?= $page['id'] ?>" 
                       class="btn btn-danger"
                       onclick="return confirm('Voulez-vous vraiment supprimer cette page ?');">
                        Supprimer
                    </a>

                <?php else: ?>

                    <span class="badge bg-secondary">
                        Non autorisé
                    </span>

                <?php endif; ?>

            </td>
        </tr>
    <?php endforeach; ?>

</table>

<?php endif; 
?>
