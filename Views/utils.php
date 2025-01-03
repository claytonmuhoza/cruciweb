<?php 
//ce fichier contient des fonctions pour le formatage des donnees pour l'affichage
//prend une date au format Y-m-d H:i:s et retourne soit les minutes, les heures ou les jours
function afficherTempsPasser(DateTime $date)
{
    return 'le '.$date->format('d/m/Y à H:i:s');
}

function afficherNiveau($niveau)
{
    switch ($niveau) {
        case 'easy':
            return 'facile';
        case 'medium':
            return 'moyen';
        case 'hard':
            return 'difficile';
        default:
            return 'niveau inconnu';
    }
}

function generateClassByNiveau($niveau)
{
    switch ($niveau) {
        case 'easy':
            return 'bg-success';
        case 'medium':
            return 'bg-warning';
        case 'hard':
            return 'bg-danger';
        default:
            return 'bg-secondary';
    }
}