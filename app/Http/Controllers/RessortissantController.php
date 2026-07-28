<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\RessortissantRequest;
use App\Models\Ressortissant;
use Illuminate\Http\JsonResponse;

// Ce code sert à gérer l'accès et les modifications des fiches de ressortissants.
// Il fonctionne avec le modèle Ressortissant et les demandes de l'API.
// Dans le but de lister, créer, afficher, modifier ou supprimer des fiches de recensement.
// Pour régler la centralisation des actions de recensement dans des fonctions dédiées.

class RessortissantController extends Controller
{
    // Ce code sert à lister tous les ressortissants enregistrés.
    // Il fonctionne avec la base de données et charge les relations pour éviter les requêtes en trop.
    // Dans le but d'afficher une liste paginée de 50 personnes par page.
    public function index(): JsonResponse
    {
        $ressortissants = Ressortissant::query()
            ->with(['user', 'district', 'region', 'departement', 'sousPrefecture', 'canton', 'tribu', 'village'])
            ->paginate(50);

        return response()->json($ressortissants);
    }

    // Ce code sert à enregistrer un nouveau ressortissant.
    // Il fonctionne avec les données du formulaire préalablement vérifiées par RessortissantRequest.
    // Dans le but d'insérer une nouvelle personne en base de données.
    public function store(RessortissantRequest $request): JsonResponse
    {
        $ressortissant = Ressortissant::create($request->validated());

        return response()->json([
            'message' => 'Ressortissant enregistré avec succès.',
            'ressortissant' => $ressortissant,
        ], 201);
    }

    // Ce code sert à afficher les informations détaillées d'un ressortissant.
    // Il fonctionne avec la fiche demandée, en chargeant toutes ses relations.
    // Dans le but de renvoyer l'identité complète et les rattachements de la personne.
    public function show(Ressortissant $ressortissant): JsonResponse
    {
        $ressortissant->load(['user', 'district', 'region', 'departement', 'sousPrefecture', 'canton', 'tribu', 'village']);
        return response()->json($ressortissant);
    }

    // Ce code sert à modifier les informations d'un ressortissant existant.
    // Il fonctionne avec la fiche en cours et les nouvelles données validées.
    // Dans le but de mettre à jour son identité ou ses rattachements.
    public function update(RessortissantRequest $request, Ressortissant $ressortissant): JsonResponse
    {
        $ressortissant->update($request->validated());

        return response()->json([
            'message' => 'Fiche du ressortissant modifiée avec succès.',
            'ressortissant' => $ressortissant,
        ]);
    }

    // Ce code sert à retirer un ressortissant de la base de données.
    // Il fonctionne avec la fiche à effacer.
    // Dans le but de supprimer définitivement la personne.
    public function destroy(Ressortissant $ressortissant): JsonResponse
    {
        $ressortissant->delete();

        return response()->json([
            'message' => 'Fiche du ressortissant supprimée avec succès.',
        ]);
    }
}
