<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Pays;
use Illuminate\Http\JsonResponse;

// Ce code sert à restituer le référentiel des pays de résidence.
// Il fonctionne avec le modèle Eloquent Pays et les requêtes HTTP.
// Dans le but de transmettre la liste ordonnée des pays pour la sélection de la résidence (locaux ou diaspora).
// Pour régler la standardisation des pays de résidence des ressortissants.
class PaysController extends Controller
{
    // Ce code sert à lister les 193 pays avec la Côte d'Ivoire positionnée en tête de liste.
    // Il fonctionne avec le modèle Pays en triant par ordre décroissant de 'is_default' puis alphabétique par 'nom'.
    // Dans le but de retourner la liste ordonnée des pays au format JSON.
    // Pour régler la mise en valeur du pays hôte principal dans l'interface de sélection.
    public function index(): JsonResponse
    {
        $pays = Pays::query()
            ->orderByDesc('is_default')
            ->orderBy('nom')
            ->get();

        return response()->json($pays);
    }
}
