<h2><?= isset($page) ? "Modifier la page" : "Créer une page" ?></h2>

<form method="POST">
    <label>Titre</label>
    <input type="text" name="title" value="<?= $page['title'] ?? '' ?>" required>
    
    <label>Slug (adresse URL)</label>
    <input type="text" name="slug" value="<?= $page['slug'] ?? '' ?>" required>

    <label>Contenu</label>
    <textarea name="content" rows="10"><?= $page['content'] ?? '' ?></textarea>
    

    <button>Valider</button>
</form>

<?php if (!empty($success)) echo "<p>$success</p>"; ?>
