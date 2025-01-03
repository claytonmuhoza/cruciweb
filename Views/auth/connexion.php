<?php $title = "Connexion"; ?>
<?php ob_start(); ?>
<div class="flex-center">
    <section class="container connexion-container">
        <h1>Connexion</h1>
            <?php if (!empty($error)): ?>
                <p class="message error"><?= $error ?></p>
            <?php endif; ?>
            <form id="connexionForm" action="/connexion" method="POST">
                <div>
                    <label for="username">Nom d'utilisateur :</label>
                    <input type="text" name="username" id="username" required>
                </div>
                <div>
                    <div id="usernameError"></div>
                </div>
                <div>
                    <label for="password">Mot de passe :</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <div>
                    <div id="passwordError"></div>
                </div>
                <div>
                    <button type="submit">Se connecter</button>
                </div>
        </form>
    </section>
    <script src="/public/js/connexion.js"></script>
</div>


<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layout.php';  ?>
