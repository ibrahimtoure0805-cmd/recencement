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
    // Ce code sert à restituer l'ensemble des 31 régions de la Côte d'Ivoire.
    // Il fonctionne avec le modèle Eloquent Region.
    // Dans le but de transmettre la liste complète des régions au format JSON.
    // Pour régler le filtrage territorial dans l'application web.
    public function index(): JsonResponse
    {
        return response()->json(Region::all());
    }

    // Ce code sert à retourner le détail d'une région administrative.
    // Il fonctionne avec l'instance de modèle Region ciblée par l'URL.
    // Dans le but de renvoyer les attributs de la région sélectionnée.
    // Pour régler la consultation unitaire d'une région.
    public function show(Region $region): JsonResponse
    {
        return response()->json($region);
    }
}
