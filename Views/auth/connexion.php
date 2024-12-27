<?php $title = "Connexion"; ?>
<?php ob_start(); ?>
<div class="flex-center">
    <section class="container connexion-container">
        <h1>Connexion</h1>
            <?php if (!empty($error)): ?>
                <p style="color: red;"><?= $error ?></p>
            <?php endif; ?>
        <form action="/connexion" method="POST">
            <label for="username">Nom d'utilisateur :</label>
            <input type="text" name="username" id="username" required>
            <br>
            <label for="password">Mot de passe :</label>
            <input type="password" name="password" id="password" required>
            <br>
            <button type="submit">Se connecter</button>
        </form>
    </section>
</div>


<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layout.php';  ?>
