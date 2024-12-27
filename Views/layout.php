<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Plateforme de Mots Croisés' ?></title>
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
    <header class="top-menu">
        <h1>Cruciweb</h1>
        <nav>
            <ul>
                <li><a href="/">Accueil</a></li>
                <?php if ($this->isAdmin()): ?>
                    <li><a href="/admin/utilisateurs">Gérer les utilisateurs</a></li>
                    <li><a href="/admin/grilles">Gérer les grilles</a></li>
                <?php endif; ?>
                <?php if (!$this->isAdmin()): ?>
                <li><a href="/grilles">Grilles</a></li>
                <?php endif; ?>
                <?php if ($this->isAuthenticated() && !$this->isAdmin()): ?>
                    <li><a href="/grilles/sauvegarde">Mes grilles sauvegardées</a></li>
                    <li><a class="menu-btn-connexion" href="/grilles/creation">Créer une grille</a></li>
                <?php else: ?>
                    <?php if (!$this->isAdmin()): ?>
                        <li><a class="menu-btn-connexion" href="/connexion">Connexion</a></li>
                        <li><a class="menu-btn-inscription" href="/inscription">Inscription</a></li>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if ($this->isAuthenticated() || $this->isAdmin()): ?>
                    <li><a href="/deconnexion">Déconnexion</a></li>
                <?php endif; ?>
                
            </ul>
        </nav>
    </header>
    <main>
        <?= $content ?? '' ?>
    </main>
    <!-- <footer>
        <p>&copy; <?= date('Y') ?> Plateforme de Mots Croisés</p>
    </footer> -->
</body>
</html>
