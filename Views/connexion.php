<?php $title = "Connexion"; ?>
<?php ob_start(); ?>
<div class="flex-center">
    <section class="container connexion-container">
        <h1>Connexion</h1>
            <?php if (!empty($error)): ?>
                <p style="color: red;"><?= $error ?></p>
            <?php endif; ?>
        <form action="/connexion" method="POST">
            <label for="email">Email :</label>
            <input type="email" name="email" id="email" required>
            <br>
            <label for="password">Mot de passe :</label>
            <input type="password" name="password" id="password" required>
            <br>
            <button type="submit">Se connecter</button>
        </form>
    </section>
</div>


<?php $content = ob_get_clean(); ?>
<?php include 'layout.php'; ?>
