<?php $title = htmlspecialchars($title); ?>
<?php ob_start(); ?>

<h1><?= $title ?></h1>

<?php if (!empty($error)): ?>
    <div class="message error">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<form id="grid-form" class="create-grid-page" action="/grilles/store" method="POST">
    <div class="grid-form-container container">
        
        <div>
            <label for="name">Nom de la grille :</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div>
            <label for="difficulty">Difficulté :</label>
            <select id="difficulty" name="difficulty">
            <option value="easy">Débutant</option>
            <option value="medium">Intermédiaire</option>
            <option value="hard">Expert</option>
            </select>
        </div>

        <div>
            <label for="rows">Nombre de lignes :</label>
            <input type="number" id="rows" name="rows" min="1" required>
        </div>

        <div>
            <label for="columns">Nombre de colonnes :</label>
            <input type="number" id="columns" name="columns" min="1" required>
        </div>
        <div>
            <button type="button" id="generate-grid">Générer la grille</button>
        </div>
      
    </div>
    

    <div id="grid-editor" style="display: none; margin-top: 20px;">
        <h3>Complétez la grille</h3>
        <div class="grid-editor-container">
            <div id="grid-container" class="container"></div>
            <div>
                <h3>Définitions</h3>
                <div id="definitions-container" class="container">
                    <h4>Lignes</h4>
                    <div id="row-definitions"></div>

                    <h4>Colonnes</h4>
                    <div id="column-definitions"></div>
                </div>
            </div>
        </div>
        

        <button type="submit">Sauvegarder la grille</button>
    </div>
</form>

<script src="/public/js/grids.js"></script>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layout.php'; ?>
