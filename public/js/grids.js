document.addEventListener('DOMContentLoaded', () => {
    const generateButton = document.getElementById('generate-grid');
    const gridContainer = document.getElementById('grid-container');
    const gridEditor = document.getElementById('grid-editor');
    const rowDefinitions = document.getElementById('row-definitions');
    const columnDefinitions = document.getElementById('column-definitions');

    generateButton.addEventListener('click', () => {
        const rows = parseInt(document.getElementById('rows').value, 10);
        const columns = parseInt(document.getElementById('columns').value, 10);

        if (isNaN(rows) || isNaN(columns) || rows <= 0 || columns <= 0) {
            alert('Veuillez entrer des dimensions valides.');
            return;
        }

        // Générer le tableau des cellules
        gridContainer.innerHTML = ''; // Effacer le contenu précédent

        for (let r = 0; r < rows; r++) {
            const row = document.createElement('div');
            row.classList.add('grid-row');

            for (let c = 0; c < columns; c++) {
                const cell = document.createElement('input');
                cell.type = 'text';
                cell.name = `cells[${r}][${c}]`;
                cell.maxLength = 1;
                cell.classList.add('grid-cell');
                row.appendChild(cell);
            }

            gridContainer.appendChild(row);
        }

        // Générer les champs de définition pour les lignes
        rowDefinitions.innerHTML = ''; // Effacer les définitions précédentes
        for (let r = 0; r < rows; r++) {
            const label = document.createElement('label');
            label.setAttribute('for', `definition_row_${r}`);
            label.textContent = `Définition pour la ligne ${r + 1} :`;
            rowDefinitions.appendChild(label);

            const input = document.createElement('input');
            input.type = 'text';
            input.name = `definitions[rows][${r}]`;
            input.id = `definition_row_${r}`;
            input.required = true;
            rowDefinitions.appendChild(input);
        }

        // Générer les champs de définition pour les colonnes
        columnDefinitions.innerHTML = ''; // Effacer les définitions précédentes
        for (let c = 0; c < columns; c++) {
            const label = document.createElement('label');
            label.setAttribute('for', `definition_column_${c}`);
            label.textContent = `Définition pour la colonne ${c + 1} :`;
            columnDefinitions.appendChild(label);

            const input = document.createElement('input');
            input.type = 'text';
            input.name = `definitions[columns][${c}]`;
            input.id = `definition_column_${c}`;
            input.required = true;
            columnDefinitions.appendChild(input);
        }

        // Afficher l'éditeur de grille et les définitions
        gridEditor.style.display = 'block';
    });
});
