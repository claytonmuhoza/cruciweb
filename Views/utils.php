<?php 
//ce fichier contient des fonctions pour le formatage des donnees pour l'affichage
//prend une date au format Y-m-d H:i:s et retourne soit les minutes, les heures ou les jours
function afficherTempsPasser(DateTime $date)
{
    $now = new DateTime();
    $interval = $now->diff($date);

    if ($interval->y > 0) {
        return 'il y a '.$interval->y . ' ans';
    } elseif ($interval->m > 0) {
        return 'il y a '.$interval->m . ' mois';
    } elseif ($interval->d > 0) {
        return 'il y a '.$interval->d . ' jours';
    } elseif ($interval->h > 0) {
        return 'il y a '.$interval->h . ' heures';
    } elseif ($interval->i > 0) {
        return 'il y a '.$interval->i . ' minutes';
    } else {
        return 'il y a moins d\'une minute';
    }
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