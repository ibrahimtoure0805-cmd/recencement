<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\TribuRequest;
use App\Models\Tribu;
use Illuminate\Http\JsonResponse;

// Ce code sert à gérer entièrement les tribus : consulter, ajouter, modifier et supprimer.
// Il fonctionne avec la table des tribus et les vérifications déléguées à TribuRequest.
// Dans le but de permettre la saisie du deuxième niveau de la structure coutumière.
// Pour régler l'absence de référentiel officiel : ces données ne peuvent être saisies qu'à la main.

class TribuController extends Controller
{
    // Ce code sert à lister l'ensemble des tribus recensées.
    // Il fonctionne avec le modèle Eloquent Tribu.
    // Dans le but de transmettre la liste des tribus au format JSON.
    // Pour régler l'affichage du référentiel tribus dans les sélecteurs dynamiques.
    public function index(): JsonResponse
    {
        return response()->json(Tribu::all());
    }

    // Ce code sert à insérer une nouvelle tribu dans la base de données.
    // Il fonctionne avec les données validées par TribuRequest (nom et canton_id).
    // Dans le but de créer l'enregistrement et retourner une réponse HTTP 201.
    // Pour régler la structuration du maillage coutumier intermédié.
    public function store(TribuRequest $request): JsonResponse
    {
        $tribu = Tribu::create($request->validated());

        return response()->json([
            'message' => 'Tribu créée avec succès.',
            'tribu' => $tribu,
        ], 201);
    }

    // Ce code sert à fournir les détails d'une tribu spécifique.
    // Il fonctionne avec le binding d'instance de modèle Tribu.
    // Dans le but d'envoyer l'objet tribu en réponse JSON.
    // Pour régler la consultation ciblée d'une tribu.
    public function show(Tribu $tribu): JsonResponse
    {
        return response()->json($tribu);
    }

    // Ce code sert à mettre à jour les attributs d'une tribu.
    // Il fonctionne avec l'instance de Tribu et les données validées reçues.
    // Dans le but d'actualiser le nom ou le rattachement de la tribu.
    // Pour régler la maintenance administrative des tribus.
    public function update(TribuRequest $request, Tribu $tribu): JsonResponse
    {
        $tribu->update($request->validated());

        return response()->json([
            'message' => 'Tribu modifiée avec succès.',
            'tribu' => $tribu,
        ]);
    }

    // Ce code sert à supprimer une tribu si aucun village ne lui est associé.
    // Il fonctionne avec la relation villages() du modèle Tribu.
    // Dans le but de retirer la tribu ou d'émettre une alerte de conflit HTTP 409.
    // Pour régler le maintien de l'intégrité référentielle entre tribus et villages.
    public function destroy(Tribu $tribu): JsonResponse
    {
        if ($tribu->villages()->exists()) {
            return response()->json([
                'message' => 'Impossible de supprimer : des villages sont encore rattachés à cette tribu.',
            ], 409);
        }

        $tribu->delete();

        return response()->json([
            'message' => 'Tribu supprimée avec succès.',
        ]);
    }
}