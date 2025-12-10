<form method="POST">

    <label>Nouveau mot de passe</label>
    <input type="password" name="password" required>

    <label>Confirmer</label>
    <input type="password" name="confirm" required>

    <button>Mettre à jour</button>

    <?php if (!empty($success)) echo "<p>$success</p>"; ?>
    <?php if (!empty($errors)) foreach($errors as $e) echo "<p style='color:red'>$e</p>"; ?>

</form>
