<?php ob_start(); ?>
<div class="page-resolver-container">
    <div class="resolver-page">
    
    <div id="grid-container" class="grid-container container">
        <div>
            <?php if (isset($_SESSION['user'])): ?>
                <button id="sauvegarderGrilles" class="btn">Sauvegarder la partie</button>
             <?php endif; ?>
             <button id="verifierGrilles">Verifier la reponse</button>
        </div>

        </div>
    
    <div class="definitions">
        <div class="row-definitions">
            <h3>Définitions des lignes</h3>
            <ul id="row-definitions"></ul>
        </div>
        <div class="column-definitions">
            <h3>Définitions des colonnes</h3>
            <ul id="column-definitions"></ul>
        </div>
    </div>
    </div>
   
</div>
<script src="/public/js/resolver.js"></script>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layout.php'; ?>
   
