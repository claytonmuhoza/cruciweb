<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Plateforme de Mots Croisés' ?></title>
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="/">Accueil</a></li>
                <li><a href="/grilles">Grilles</a></li>
                <?php if (isset($_SESSION['user'])): ?>
                    <li><a href="/grilles/creation">Créer une grille</a></li>
                    <li><a href="/deconnexion">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="/connexion">Connexion</a></li>
                    <li><a href="/inscription">Inscription</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main>
        <?= $content ?? '' ?>
    </main>
    <footer>
        <p>&copy; <?= date('Y') ?> Plateforme de Mots Croisés</p>
    </footer>
</body>
</html>
