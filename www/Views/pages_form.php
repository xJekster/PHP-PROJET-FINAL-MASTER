<h2><?= isset($page) ? "Modifier la page" : "Créer une page" ?></h2>

<form method="POST">
    <label>Titre</label>
    <input type="text" name="title" class="form-control" value="<?= $page['title'] ?? '' ?>" required>
    
    <label>Slug (adresse URL)</label>
    <input type="text" name="slug" class="form-control" value="<?= $page['slug'] ?? '' ?>" required>
    <small>Exemple : <strong>bonjour</strong> → URL : /bonjour</small>


    <label>Contenu</label>
    <textarea name="content" class="form-control" rows="10"><?= $page['content'] ?? '' ?></textarea>
    

    <button class="btn btn-primary mt-2">Valider</button>
</form>

<?php if (!empty($success)) echo "<p class='text-success'>$success</p>"; ?>
