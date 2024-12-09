<?php $title = htmlspecialchars($title); ?>
<?php ob_start(); ?>
    <div>
       
        
        <?php if (!empty($grids)) : ?>
            <div class="grid-cards container">
                <?php foreach ($grids as $grid) : ?>
                    <div class="card">
                        <div class="card-header">
                            <h3><?= htmlspecialchars($grid['name']) ?></h3>
                            <p class="difficulty"><?= htmlspecialchars($grid['difficulty']) ?></p>
                        </div>
                        <div class="card-body">
                            <p><strong>Créé par :</strong> <?= htmlspecialchars($grid['user_id']) ?></p>
                            <p><strong>Date de création :</strong> <?= htmlspecialchars($grid['created_at']) ?></p>
                        </div>
                        <div class="card-footer">
                            <a href="/grids/view/<?= htmlspecialchars($grid['id']) ?>" class="btn btn-view">Voir</a>
                            <!-- <a href="/grids/edit/<?= htmlspecialchars($grid['id']) ?>" class="btn btn-edit">Modifier</a> -->
                            <!-- <a href="/grids/delete/<?= htmlspecialchars($grid['id']) ?>" class="btn btn-delete" onclick="return confirm('Voulez-vous vraiment supprimer cette grille ?')">Supprimer</a> -->
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <p class="no-grids">Aucune grille disponible.</p>
        <?php endif; ?>
    </div>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layout.php'; ?>
   
