<?= var_dump($grid) ?>
<h1>Résolution de la Grille</h1>
<h2>Définitions</h2>

<h3>Définitions des lignes</h3>
<ul>
    <?php 
    // Filtrer les définitions pour les lignes
    $rows = array_filter($definitions, fn($item) => $item['type'] === 'row');
    if (!empty($rows)): 
        foreach ($rows as $row): ?>
            <li>Ligne <?= htmlspecialchars($row['index_num']) ?>: <?= htmlspecialchars($row['text']) ?></li>
        <?php endforeach; 
     else: ?>
        <p>Aucune définition pour les lignes.</p>
    <?php endif; ?>
</ul>

<h3>Définitions des colonnes</h3>
<ul>
    <?php 
    // Filtrer les définitions pour les colonnes
    $columns = array_filter($definitions, fn($item) => $item['type'] === 'column');
    if (!empty($columns)): 
        foreach ($columns as $column): ?>
            <li>Colonne <?= htmlspecialchars($column['index_num']) ?>: <?= htmlspecialchars($column['text']) ?></li>
        <?php endforeach; 
        else: ?>
        <p>Aucune définition pour les colonnes.</p>
    <?php endif; ?>

        <div class="grid-container">
            <h2>Grille</h2>
            <table class="grid">
    <?php for ($i = 1; $i <= $grid['num_rows']; $i++): ?>
        <tr>
            <?php for ($j = 1; $j <= $grid['num_columns']; $j++): ?>
                <?php
                    // Recherche de la cellule correspondante
                    $cellValue = '';
                    foreach ($cells as $cell) {
                        if ($cell['ligne'] == $i && $cell['colonne'] == $j) {
                            $cellValue = $cell['value'] ?? '';
                            break;
                        }
                    }
                ?>
                <td>
                    <input 
                        type="text" 
                        maxlength="1" 
                        data-row="<?= $i ?>" 
                        data-col="<?= $j ?>" 
                        value="<?= htmlspecialchars($cellValue) ?>" 
                        class="cell"
                    >
                </td>
            <?php endfor; ?>
        </tr>
    <?php endfor; ?>
</table>

        </div>

        <?php if (isset($_SESSION['user'])): ?>
            <button id="save-progress" class="btn">Sauvegarder la partie</button>
        <?php endif; ?>
    </div>
