<form method="POST">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

    <label>Email</label>
    <input type="email" name="email" required>

    <button type="submit">Réinitialiser</button>

    <?php if (!empty($success)) echo "<p>$success</p>"; ?>
    <?php if (!empty($errors)) foreach($errors as $e) echo "<p style='color:red'>$e</p>"; ?>
</form>
