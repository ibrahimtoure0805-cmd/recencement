<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Http\JsonResponse;

// Ce code sert à répondre aux demandes de consultation des régions.
// Il fonctionne avec les données déjà importées depuis l'ANStat et stockées dans notre base.
// Dans le but de fournir ces informations à toute application qui interroge notre projet.
// Pour régler le besoin de consulter le découpage officiel sans repasser par le site de l'ANStat.

class RegionController extends Controller
{
    // Renvoie la liste complète des 33 régions (consultation seule, aucune modification possible).
    public function index(): JsonResponse
    {
        return response()->json(Region::all());
    }

    // Renvoie une seule région : celle dont le numéro est indiqué dans l'adresse de la demande.
    // Si aucune région ne porte ce numéro, une réponse "introuvable" est envoyée automatiquement.
    
    public function show(Region $region): JsonResponse
    {
        return response()->json($region);
    }
}
