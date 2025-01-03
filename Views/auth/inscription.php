<?php $title = "Inscription"; ?>
<?php ob_start(); ?>
<div class="flex-center">
    <section class="container inscription-container">
        <h1>Inscription</h1>
        <?php if (!empty($error)): ?>
            <p class="message error"><?= $error ?></p>
        <?php endif; ?>
        <form id="inscriptionForm" action="/inscription" method="POST">
            <div>
                <label for="username">Nom d'utilisateur :</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div id="usernameError"></div>
            <div>
                <label for="password">Mot de passe :</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div id="passwordError"></div>
            <div>
                <button type="submit">S'inscrire</button>
            </div>
        
    </form>

    </section>
    <script src="/public/js/inscription.js"></script>
</div>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layout.php'; ?>
