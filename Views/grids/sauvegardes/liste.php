<?php $title = htmlspecialchars($title); ?>
<?php ob_start(); ?>
<?php require __DIR__ . '../../../utils.php'; ?>
    <div>
        <?php if (!empty($grids)) : ?>
            <div class="cards-container-grid grid-cards">
                <?php foreach ($grids as $grid) : ?>
                    <div class="card container">
                        <div class="card-header">
                            <h3><?= htmlspecialchars($grid['name']) ?></h3>
                            <p>Publié <?= afficherTempsPasser(new DateTime($grid['created_at'])) ?></p>
                            <p class="difficulty">Niveau: <span class=<?=htmlspecialchars(generateClassByNiveau($grid["difficulty"]))?>> <?= htmlspecialchars(afficherNiveau($grid['difficulty'])) ?></span></p>
                        </div>
                        <div><?= htmlspecialchars($grid['num_rows'])?> lignes | <?= htmlspecialchars($grid['num_columns'])?> colonnes</div>
                        <div class="card-footer">
                            <a class="btn-green" href="/grilles/resolve/<?= htmlspecialchars($grid['id']) ?>" class="btn btn-view">Continue</a>
                            <a href="/grilles/sauvegarde/delete/<?= htmlspecialchars($grid['id']) ?>" class="btn-red" onclick="return confirm('Voulez-vous vraiment supprimer cette grille ?')">Supprimer</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <div class="no-grids container">Aucune grille disponible.</div>
        <?php endif; ?>
    </div>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../../layout.php'; ?>
   
