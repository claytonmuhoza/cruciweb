<?php $title = "Liste des grilles"; ?>
<?php ob_start(); ?>

    <section>
        <h1>Page introuvable</h1>
        <p>La page que vous cherchez n'existe pas.</p>
    </section>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layout.php'; ?>