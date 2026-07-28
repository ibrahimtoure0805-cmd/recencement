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
    // Renvoie la liste de toutes les tribus enregistrées.
    public function index(): JsonResponse
    {
        return response()->json(Tribu::all());
    }

    // Enregistre une nouvelle tribu. TribuRequest a déjà vérifié le nom
    // et l'existence réelle du canton avant que cette méthode ne s'exécute.
    public function store(TribuRequest $request): JsonResponse
    {
        $tribu = Tribu::create($request->validated());

        return response()->json([
            'message' => 'Tribu créée avec succès.',
            'tribu' => $tribu,
        ], 201);
    }

    // Renvoie une seule tribu : celle dont le numéro est indiqué dans l'adresse de la demande.
    public function show(Tribu $tribu): JsonResponse
    {
        return response()->json($tribu);
    }

    // Modifie une tribu existante (nom et/ou canton), mêmes vérifications qu'à la création.
    public function update(TribuRequest $request, Tribu $tribu): JsonResponse
    {
        $tribu->update($request->validated());

        return response()->json([
            'message' => 'Tribu modifiée avec succès.',
            'tribu' => $tribu,
        ]);
    }

    // Supprime une tribu, après avoir vérifié nous-mêmes qu'aucun village n'y est rattaché.
    public function destroy(Tribu $tribu): JsonResponse
    {
        // On vérifie l'erreur.
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