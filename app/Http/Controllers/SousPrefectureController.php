<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\SousPrefecture;
use Illuminate\Http\JsonResponse;


// Ce code sert à répondre aux demandes de consultation des sous-préfectures.
// Il fonctionne avec les données déjà importées depuis l'ANStat et stockées dans notre base.
// Dans le but de fournir ces informations à toute application qui interroge notre projet.
// Pour régler le besoin de consulter le découpage officiel sans repasser par le site de l'ANStat.

class SousPrefectureController extends Controller
{
    // Renvoie les sous-préfectures par paquets de 50 au lieu de tout envoyer d'un coup :
    // il y en a 526, ce serait trop lourd. Le demandeur reçoit aussi le nombre total
    // et peut demander la suite page par page (?page=2, ?page=3...).
    
    public function index(): JsonResponse
    {
        return response()->json(SousPrefecture::paginate(50));
    }

    // Renvoie une seule sous-préfecture : celle dont le numéro est indiqué dans l'adresse de la demande.
    // Si aucune ne porte ce numéro, une réponse "introuvable" est envoyée automatiquement.
    
    public function show(SousPrefecture $sousPrefecture): JsonResponse
    {
        return response()->json($sousPrefecture);
    }
}
