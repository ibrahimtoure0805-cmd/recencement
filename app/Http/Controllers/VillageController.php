<?php
// app/Http/Controllers/VillageController.php — remplace tout le contenu

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\VillageRequest;
use App\Models\Village;
use Illuminate\Http\JsonResponse;

// Ce code sert à gérer entièrement les villages : consulter, ajouter, modifier et supprimer.
// Il fonctionne avec la table des villages et les vérifications déléguées à VillageRequest.
// Dans le but de permettre la saisie du troisième et dernier niveau de la structure coutumière.
// Pour régler l'absence de référentiel officiel : ces données ne peuvent être saisies qu'à la main.

class VillageController extends Controller
{
    // Renvoie la liste de tous les villages enregistrés.
    public function index(): JsonResponse
    {
        return response()->json(Village::all());
    }

    // Enregistre un nouveau village. VillageRequest a déjà vérifié le nom
    // et l'existence réelle de la tribu avant que cette méthode ne s'exécute.
    public function store(VillageRequest $request): JsonResponse
    {
        $village = Village::create($request->validated());

        return response()->json([
            'message' => 'Village créé avec succès.',
            'village' => $village,
        ], 201);
    }

    // Renvoie un seul village : celui dont le numéro est indiqué dans l'adresse de la demande.
    public function show(Village $village): JsonResponse
    {
        return response()->json($village);
    }

    // Modifie un village existant (nom et/ou tribu), mêmes vérifications qu'à la création.
    public function update(VillageRequest $request, Village $village): JsonResponse
    {
        $village->update($request->validated());

        return response()->json([
            'message' => 'Village modifié avec succès.',
            'village' => $village,
        ]);
    }

    // Supprime un village directement : rien ne dépend encore de lui dans la base.
    // ATTENTION : quand la table des ressortissants existera et pointera vers les villages,
    // il faudra ajouter ici la même vérification que dans les deux autres contrôleurs.
    
    public function destroy(Village $village): JsonResponse
    {
        $village->delete();

        return response()->json([
            'message' => 'Village supprimé avec succès.',
        ], 200);
    }
}