<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Http\JsonResponse;

// Ce code sert à répondre aux demandes de consultation des districts.
// Il fonctionne avec les données déjà importées depuis l'ANStat et stockées dans notre base.
// Dans le but de fournir ces informations à toute application qui interroge notre projet.
// Pour régler le besoin de consulter le découpage officiel sans repasser par le site de l'ANStat.

class DistrictController extends Controller
{
    // Ce code sert à restituer l'ensemble des districts administratifs.
    // Il fonctionne avec le modèle Eloquent District.
    // Dans le but de renvoyer la liste complète des 14 districts autonomes et réguliers en JSON.
    // Pour régler le choix du district dans le formulaire d'inscription citoyenne.
    public function index(): JsonResponse
    {
        return response()->json(District::all());
    }

    // Ce code sert à afficher les informations d'un district unique.
    // Il fonctionne avec l'injection de modèle District selon l'identifiant passé dans l'URL.
    // Dans le but de transmettre le nom et le code du district demandé.
    // Pour régler l'affichage ciblé d'un district.
    public function show(District $district): JsonResponse
    {
        return response()->json($district);
    }
}
