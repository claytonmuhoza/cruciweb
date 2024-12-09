<?php $title = "Accueil cruw-word"; ?>
<?php ob_start(); ?>
    <section>
        Bienvenue sur la page d'accueil
    </section>
<?php $content = ob_get_clean(); ?>
<?php include 'layout.php'; ?>