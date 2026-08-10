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
    // Ce code sert à répertorier l'ensemble des villages enregistrés.
    // Il fonctionne avec le modèle Eloquent Village.
    // Dans le but d'envoyer la liste complète des villages au format JSON.
    // Pour régler l'alimentation des sélecteurs de villages d'origine dans le formulaire.
    public function index(): JsonResponse
    {
        return response()->json(Village::all());
    }

    // Ce code sert à créer une nouvelle entité village en base de données.
    // Il fonctionne avec les données validées par VillageRequest (nom, tribu_id).
    // Dans le but d'insérer l'enregistrement et d'envoyer une réponse HTTP 201.
    // Pour régler l'extension locale de l'arborescence des villages.
    public function store(VillageRequest $request): JsonResponse
    {
        $village = Village::create($request->validated());

        return response()->json([
            'message' => 'Village créé avec succès.',
            'village' => $village,
        ], 201);
    }

    // Ce code sert à restituer les informations d'un village précis.
    // Il fonctionne avec le modèle Village lié par l'identifiant URL.
    // Dans le but de retourner la fiche du village au format JSON.
    // Pour régler l'affichage du détail d'un village sélectionné.
    public function show(Village $village): JsonResponse
    {
        return response()->json($village);
    }

    // Ce code sert à modifier les données d'un village existant.
    // Il fonctionne avec l'instance ciblée de Village et les données validées de la requête.
    // Dans le but de sauvegarder les changements et d'en informer le client.
    // Pour régler la mise à jour des dénominations de villages.
    public function update(VillageRequest $request, Village $village): JsonResponse
    {
        $village->update($request->validated());

        return response()->json([
            'message' => 'Village modifié avec succès.',
            'village' => $village,
        ]);
    }

    // Ce code sert à effacer un village de la base de données.
    // Il fonctionne avec l'instance sélectionnée du modèle Village.
    // Dans le but d'exécuter la suppression de la ligne et renvoyer un statut 200.
    // Pour régler le nettoyage des entités de village obsolètes.
    public function destroy(Village $village): JsonResponse
    {
        $village->delete();

        return response()->json([
            'message' => 'Village supprimé avec succès.',
        ], 200);
    }
}