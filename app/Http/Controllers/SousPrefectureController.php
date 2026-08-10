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
    // Ce code sert à renvoyer la liste complète ou paginée des sous-préfectures.
    // Il fonctionne avec le paramètre 'paginate' éventuel de la requête HTTP et le modèle SousPrefecture.
    // Dans le but de transmettre la liste des sous-préfectures en format JSON.
    // Pour régler l'affichage du découpage administratif dans les formulaires et filtres.
    public function index(Request $request): JsonResponse
    {
        if ($request->has('paginate')) {
            return response()->json(SousPrefecture::paginate(50));
        }

        return response()->json(SousPrefecture::all());
    }

    // Ce code sert à afficher le détail d'une sous-préfecture ciblée par son identifiant.
    // Il fonctionne avec le binding du modèle SousPrefecture injecté automatiquement par Laravel.
    // Dans le but de renvoyer la fiche détaillée de la sous-préfecture.
    // Pour régler la recherche ponctuelle d'une sous-préfecture spécifique.
    public function show(SousPrefecture $sousPrefecture): JsonResponse
    {
        return response()->json($sousPrefecture);
    }
}
