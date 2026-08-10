<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\SousPrefecture;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

// Ce code sert à répondre aux demandes de consultation des sous-préfectures.
// Il fonctionne avec les données déjà importées depuis l'ANStat et stockées dans notre base.
// Dans le but de fournir ces informations à toute application qui interroge notre projet.
// Pour régler le besoin de consulter le découpage officiel sans repasser par le site de l'ANStat.

class SousPrefectureController extends Controller
{
    // Renvoie la liste complète des 526 sous-préfectures (ou paginée si demandé)
    public function index(Request $request): JsonResponse
    {
        if ($request->has('paginate')) {
            return response()->json(SousPrefecture::paginate(50));
        }

        return response()->json(SousPrefecture::all());
    }

    // Renvoie une seule sous-préfecture : celle dont le numéro est indiqué dans l'adresse de la demande.
    public function show(SousPrefecture $sousPrefecture): JsonResponse
    {
        return response()->json($sousPrefecture);
    }
}
