<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CantonRequest;
use App\Models\Canton;
use Illuminate\Http\JsonResponse;

// Ce code sert à gérer entièrement les cantons : consulter, ajouter, modifier et supprimer.
// Il fonctionne avec la table des cantons et les vérifications déléguées à CantonRequest.
// Dans le but de permettre la saisie et l'entretien du premier niveau de la structure coutumière.
// Pour régler l'absence de référentiel officiel : sans cette gestion, impossible de peupler ces données.

class CantonController extends Controller
{
    // Ce code sert à lister l'intégralité des cantons répertoriés.
    // Il fonctionne avec le modèle Eloquent Canton.
    // Dans le but de renvoyer la collection complète des cantons au format JSON.
    // Pour régler la mise à disposition du référentiel cantonais pour les selects frontend.
    public function index(): JsonResponse
    {
        return response()->json(Canton::all());
    }

    // Ce code sert à enregistrer un nouveau canton dans le référentiel coutumier.
    // Il fonctionne avec les données validées provenant de CantonRequest.
    // Dans le but d'insérer l'enregistrement et de retourner une réponse HTTP 201.
    // Pour régler l'enrichissement administrateur de la carte des cantons.
    public function store(CantonRequest $request): JsonResponse
    {
        $canton = Canton::create($request->validated());

        return response()->json([
            'message' => 'Canton créé avec succès.',
            'canton' => $canton,
        ], 201);
    }

    // Ce code sert à retourner les détails d'un canton spécifique.
    // Il fonctionne avec l'injection automatique du modèle Canton via son identifiant.
    // Dans le but de transmettre les informations du canton en JSON.
    // Pour régler la consultation unitaire d'un canton.
    public function show(Canton $canton): JsonResponse
    {
        return response()->json($canton);
    }

    // Ce code sert à mettre à jour les données d'un canton existant.
    // Il fonctionne avec le modèle Canton ciblé et les données validées par CantonRequest.
    // Dans le but d'enregistrer les modifications apportées au canton.
    // Pour régler la correction des dénominations de cantons.
    public function update(CantonRequest $request, Canton $canton): JsonResponse
    {
        $canton->update($request->validated());

        return response()->json([
            'message' => 'Canton modifié avec succès.',
            'canton' => $canton,
        ]);
    }

    // Ce code sert à supprimer un canton s'il ne possède pas de tribus liées.
    // Il fonctionne avec la relation tribus() du modèle Canton.
    // Dans le but de supprimer le canton ou d'interdire l'action avec un code HTTP 409.
    // Pour régler la protection contre la suppression orpheline d'entités dépendantes.
    public function destroy(Canton $canton): JsonResponse
    {
        if ($canton->tribus()->exists()) {
            return response()->json([
                'message' => 'Impossible de supprimer : des tribus sont encore rattachées à ce canton.',
            ], 409);
        }

        $canton->delete();

        return response()->json([
            'message' => 'Canton supprimé avec succès.',
        ]);
    }
}