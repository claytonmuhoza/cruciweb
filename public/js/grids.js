document.addEventListener('DOMContentLoaded', () => {
    const generateButton = document.getElementById('generate-grid');
    const gridContainer = document.getElementById('grid-container');
    const gridEditor = document.getElementById('grid-editor');
    const rowDefinitions = document.getElementById('row-definitions');
    const columnDefinitions = document.getElementById('column-definitions');
    const form = document.getElementById('grid-form');

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

    form.addEventListener('submit', (e) => {
        const rows = parseInt(document.getElementById('rows').value, 10);
        const columns = parseInt(document.getElementById('columns').value, 10);

        if (!gridEditor.style.display || gridEditor.style.display === 'none') {
            alert('Veuillez générer la grille avant de soumettre.');
            e.preventDefault();
            return;
        }

        const cells = Array.from(document.querySelectorAll('.grid-cell'));
        const grid = [];

        for (let r = 0; r < rows; r++) {
            const row = cells.slice(r * columns, (r + 1) * columns).map(cell => cell.value.trim());
            grid.push(row);
        }

        // Validation des lignes
        for (let r = 0; r < rows; r++) {
            const row = grid[r];
            let words = [];
            let word = '';

            // Parcourir chaque élément du tableau
            for (let char of row) {
                if (char === '') {
                    if (word.length >= 2) {
                        words.push(word); // Ajouter le mot accumulé si sa taille >= 2
                    }
                    word = ''; // Réinitialiser pour le prochain mot
                } else {
                    word += char; // Construire le mot
                }
            }

            // Ajouter le dernier mot s'il existe et si sa taille >= 2
            if (word.length >= 2) {
                words.push(word);
            }
            const definitions = document.getElementById(`definition_row_${r}`).value.split('-');

            if (words.length < 1 || words.length !== definitions.length) {
                alert(`La ligne ${r + 1} doit contenir autant de mots valide que de définitions. Mots valides: ${words.length}, Définitions: ${definitions.length} (les définitions doivent être séparées par un tiret '-')`);
                e.preventDefault();
                return;
            }
        }

        // Validation des colonnes
        for (let c = 0; c < columns; c++) {
            const column = grid.map(row => row[c]);
            let words = [];
            let word = '';

            // Parcourir chaque élément du tableau
            for (let char of column) {
                if (char === '') {
                    if (word.length >= 2) {
                        words.push(word); // Ajouter le mot accumulé si sa taille >= 2
                    }
                    word = ''; // Réinitialiser pour le prochain mot
                } else {
                    word += char; // Construire le mot
                }
            }

            // Ajouter le dernier mot s'il existe et si sa taille >= 2
            if (word.length >= 2) {
                words.push(word);
            }
            const definitions = document.getElementById(`definition_column_${c}`).value.split('-');
            if (words.length < 1 || words.length !== definitions.length) {
                alert(`La colonne ${c + 1} doit contenir autant de mots valide que de définitions. Mots valides: ${words.length}, Définitions: ${definitions.length} (les définitions doivent être séparées par un tiret '-')`);
                e.preventDefault();
                return;
            }
        }
    });
});

