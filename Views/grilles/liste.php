<?php $title = "Liste des grilles"; ?>
<?php ob_start(); ?>

    <section>
        Bienvenue sur la page d'accueil
        <h1>Liste des grilles</h1>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Dimensions</th>
                    <th>Niveau</th>
                    <th>Créée le</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($grilles as $grille) : ?>
                    <tr>
                        <td><?= $grille['nom'] ?></td>
                        <td><?= $grille['dimensions'] ?></td>
                        <td><?= $grille['niveau'] ?></td>
                        <td><?= $grille['date_creation'] ?></td>
                        <td>
                            <a href="/grilles/<?= $grille['id'] ?>">Voir</a>
                            <?php if ($this->isAdmin()) : ?>
                                <a href="/grilles/supprimer/<?= $grille['id'] ?>">Supprimer</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
    <main>
        autre things
    </main>
    <main>
        Compte
    </main>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layout.php'; ?>