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
    // Renvoie la liste de tous les cantons enregistrés.
    public function index(): JsonResponse
    {
        return response()->json(Canton::all());
    }

    // Enregistre un nouveau canton. Les vérifications (nom présent, texte, 255 max)
    // sont faites par CantonRequest AVANT d'entrer ici : si elles échouent,
    // la demande est refusée automatiquement et cette méthode n'est jamais exécutée.
    
    public function store(CantonRequest $request): JsonResponse
    {
        $canton = Canton::create($request->validated());

        // Le code 201 signale que la création a bien eu lieu.
        return response()->json([
            'message' => 'Canton créé avec succès.',
            'canton' => $canton,
        ], 201);
    }

    // Renvoie un seul canton : celui dont le numéro est indiqué dans l'adresse de la demande.
    public function show(Canton $canton): JsonResponse
    {
        return response()->json($canton);
    }

    // Modifie le nom d'un canton existant, avec les mêmes vérifications qu'à la création.
    public function update(CantonRequest $request, Canton $canton): JsonResponse
    {
        $canton->update($request->validated());

        return response()->json([
            'message' => 'Canton modifié avec succès.',
            'canton' => $canton,
        ]);
    }

    // Supprime un canton, après avoir vérifié nous-mêmes qu'aucune tribu n'y est rattachée.
    public function destroy(Canton $canton): JsonResponse
    {
        // Vérification explicite AVANT de tenter quoi que ce soit : on compte
        // les tribus rattachées. Le refus est donc prouvé, pas déduit d'une erreur.
        if ($canton->tribus()->exists()) {
            return response()->json([
                'message' => 'Impossible de supprimer : des tribus sont encore rattachées à ce canton.',
            ], 409);
        }

        $canton->delete();

        // Code 200 avec un message de confirmation .
        return response()->json([
            'message' => 'Canton supprimé avec succès.',
        ]);
    }
}