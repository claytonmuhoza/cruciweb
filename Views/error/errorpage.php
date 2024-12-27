<?php $title = 'Erreur '.htmlspecialchars($codeErreur); ?>
<?php ob_start(); ?>
    
    <section class="container"> 
        <h1>Erreur <?php echo htmlspecialchars($codeErreur)?></h1>
        <?php if(isset($messageErreur)): ?>
            <p><?php echo htmlspecialchars($messageErreur)?></p>
        <?php endif; ?>
    </section>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layout.php'; ?>