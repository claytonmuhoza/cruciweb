const currentUrl = window.location.origin.endsWith('/') ? window.location.origin : `${window.location.origin}/`;
const apiUrl = `${currentUrl}api/grids`; // Construit l'URL des endpoints API
const url = window.location.href;

// Extraire la dernière partie de l'URL après le dernier "/"
const gridId = url.substring(url.lastIndexOf("/") + 1);

// Charger les données et initialiser la grille
async function loadGrid() {
    const [gridResponse, cellsResponse, definitionsResponse] = await Promise.all([
        fetch(`${apiUrl}/${gridId}`),
        fetch(`${apiUrl}/${gridId}/cells`),
        fetch(`${apiUrl}/${gridId}/definitions`)
    ]);
    const grid = await gridResponse.json();
    const cells = await cellsResponse.json();
    const definitions = await definitionsResponse.json();
    // console.log(cells);
    renderGrid(grid, cells);
    renderDefinitions(definitions);
}

// Rendre la grille sur la page
function renderGrid(grid, cells) {
    const gridContainer = document.getElementById('grid-container');
    const table = document.createElement('table'); // Créer un élément <table>
    gridContainer.appendChild(table);

    const cellsMap = {};
    cells.forEach(cell => {
        cellsMap[`${cell.ligne}-${cell.colonne}`] = cell;
    });

    // Créer la grille avec les lignes <tr> et les colonnes <td>
    for (let i = 1; i <= grid.num_rows; i++) {
        const row = document.createElement('tr'); // Créer une ligne <tr>
        table.appendChild(row);

        for (let j = 1; j <= grid.num_columns; j++) {
            const cellData = cellsMap[`${i}-${j}`] || {}; // Récupérer les données de la cellule ou un objet vide
            const cell = document.createElement('td'); // Créer une colonne <td>

            if (cellData.value) {
                const input = document.createElement('input'); // Créer un champ de saisie <input>
                input.type = 'text';
                input.maxLength = 1;
                input.className = cellData.value ? 'editable-cell' : 'black-cell';
                input.disabled = !cellData.value;
                input.dataset.row = i;
                input.dataset.col = j;
                (cellData.value && cellData.value!='#')? input.value = cellData.value : '' ; // Assigner la valeur de la cellule à l'input
                cell.appendChild(input); // Ajouter l'input à la cellule

                if (cellData.value) {
                    input.addEventListener('focus', handleCellFocus);
                }
            } else {
                cell.className = 'black-cell'; // Marquer la cellule noire si aucune valeur n'est définie
            }

            row.appendChild(cell); // Ajouter la cellule à la ligne
        }
    }
}

// Rendre les définitions sur la page
function renderDefinitions(definitions) {
    const rowDefinitions = document.getElementById('row-definitions');
    const columnDefinitions = document.getElementById('column-definitions');

    definitions.forEach(definition => {
        const li = document.createElement('li');
        li.textContent = definition.text;

        if (definition.type === 'row') {
            li.dataset.row = definition.index_num;
            rowDefinitions.appendChild(li);
        } else if (definition.type === 'column') {
            li.dataset.col = definition.index_num;
            columnDefinitions.appendChild(li);
        }
    });
}



// Gérer le focus sur une cellule
function handleCellFocus(event) {
    const cell = event.target;
    const row = cell.dataset.row;
    const col = cell.dataset.col;

    document.querySelectorAll('.definitions li').forEach(li => li.classList.remove('active'));

    document.querySelector(`.row-definitions li[data-row="${row}"]`)?.classList.add('active');
    document.querySelector(`.column-definitions li[data-col="${col}"]`)?.classList.add('active');
}


function verifierEtatGrilles() {
    const gridData = [];

    // Parcours des cellules de la grille pour récupérer les valeurs
    const rows = document.querySelectorAll('tr');
    rows.forEach((row, i) => {
        const cols = row.querySelectorAll('td');
        cols.forEach((col, j) => {
            const input = col.querySelector('input');
            gridData.push({
                id: (i * 10 + j) + 1,  // Génération d'un ID unique pour chaque cellule
                grid_id: gridId,
                ligne: i + 1,  // L'index des lignes commence à 1
                colonne: j + 1,  // L'index des colonnes commence à 1
                value: input ? input.value || null : null // Si input existe, prendre sa valeur, sinon assigner null
            });
        });
    });
    return gridData; //on retourne les données actuel de la grille
}
function verifierGrillesEtPoster() {
    const gridData = verifierEtatGrilles();
    console.log(fell = gridData);
    const requestData = {
        cells: JSON.stringify(gridData), // Les données de la grille converties en JSON
    };

    // Envoi de la requête POST
    fetch(`${apiUrl}/verification/${gridId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded', // Type de contenu pour le POST
        },
        body: new URLSearchParams(requestData), // Encodage des données
    })
        .then(response => response.json())
        .then(result => {
            // Affichage du résultat dans une boîte d'alerte
            alert(`${result}`);
        })
        .catch(error => {
            // Gestion des erreurs
            console.error('Erreur lors de la vérification des grilles :', error);
            alert('Une erreur s\'est produite lors de la vérification.');
        });
}
function sauvegarderEtatGrille()
{
    const gridData = verifierEtatGrilles();
    const requestData = {
        grid_state: JSON.stringify(gridData), // Les données de la grille converties en JSON
        grid_id: gridId //l'id de la grille
    };

    // Envoi de la requête POST
    fetch(`${apiUrl}/sauvegarder-grid`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded', // Type de contenu pour le POST
        },
        body: new URLSearchParams(requestData), // Encodage des données
    })
        .then(response => response.json())
        .then(result => {
            // Affichage du résultat dans une boîte d'alerte
            console.log(result);
            if(result?.success)
            {
                alert('L\'état de la grille a été sauvegardé avec succès.');
            }
            else
            {
                alert('Une erreur s\'est produite lors de la sauvegarde de l\'état de la grille.');
            }
        })
        .catch(error => {
            // Gestion des erreurs
            console.error('Erreur lors de la sauvegarge de la grille :', error);
            alert('Une erreur s\'est produite du sauvegarde de l\'état de la grille.');
        });
}
// Ajouter un gestionnaire d'événements au bouton
document.getElementById('verifierGrilles').addEventListener('click', verifierGrillesEtPoster);
document.getElementById('sauvegarderGrilles')?.addEventListener('click', sauvegarderEtatGrille);

// Charger la grille au démarrage
loadGrid();
