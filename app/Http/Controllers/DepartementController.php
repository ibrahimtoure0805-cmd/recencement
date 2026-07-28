<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Departement;
use Illuminate\Http\JsonResponse;

// Ce code sert à répondre aux demandes de consultation des départements.
// Il fonctionne avec les données déjà importées depuis l'ANStat et stockées dans notre base.
// Dans le but de fournir ces informations à toute application qui interroge notre projet.
// Pour régler le besoin de consulter le découpage officiel sans repasser par le site de l'ANStat.

class DepartementController extends Controller
{
    // Renvoie la liste complète des 111 départements (consultation seule, aucune modification possible).
    
    public function index(): JsonResponse
    {
        return response()->json(Departement::all());
    }

    // Renvoie un seul département : celui dont le numéro est indiqué dans l'adresse de la demande.
    // Si aucun département ne porte ce numéro, une réponse "introuvable" est envoyée automatiquement.
    
    public function show(Departement $departement): JsonResponse
    {
        return response()->json($departement);
    }
}
