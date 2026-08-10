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
    // Ce code sert à lister les 111 départements de la Côte d'Ivoire.
    // Il fonctionne avec le modèle Eloquent Departement.
    // Dans le but d'envoyer la liste des départements en JSON.
    // Pour régler la sélection du département de rattachement administratif.
    public function index(): JsonResponse
    {
        return response()->json(Departement::all());
    }

    // Ce code sert à afficher les informations d'un département donné.
    // Il fonctionne avec le modèle Departement résolu à partir du paramètre d'URL.
    // Dans le but de transmettre la fiche du département sollicité.
    // Pour régler la consultation ponctuelle d'un département.
    public function show(Departement $departement): JsonResponse
    {
        return response()->json($departement);
    }
}
