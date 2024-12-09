<?php $title = "Inscription"; ?>
<?php ob_start(); ?>
<h1>Inscription</h1>
<?php if (!empty($error)): ?>
    <p style="color: red;"><?= $error ?></p>
<?php endif; ?>
<form action="/inscription" method="POST">
    <label for="email">Email :</label>
    <input type="email" name="email" id="email" required>
    <br>
    <label for="password">Mot de passe :</label>
    <input type="password" name="password" id="password" required>
    <br>
    <button type="submit">S'inscrire</button>
</form>
<?php $content = ob_get_clean(); ?>
<?php include 'layout.php'; ?>
