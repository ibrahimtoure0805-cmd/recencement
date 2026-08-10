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
    // Ce code sert à lister tous les ressortissants enregistrés avec filtres optionnels.
    // Il fonctionne avec la base de données et charge les relations pour éviter les requêtes N+1.
    // Dans le but d'afficher une liste paginée filtrable (statut, pays, région, canton, village, profession, recherche).
    public function index(\Illuminate\Http\Request $request): JsonResponse
    {
        $query = Ressortissant::query()
            ->with(['user', 'district', 'region', 'departement', 'sousPrefecture', 'canton', 'tribu', 'village']);

        // Filtre par statut de modération
        if ($request->filled('statut_validation')) {
            $query->where('statut_validation', $request->input('statut_validation'));
        }

        // Filtre par pays de résidence (ex: Diaspora vs Côte d'Ivoire)
        if ($request->filled('pays')) {
            $query->where('pays', $request->input('pays'));
        }

        // Filtre par entités géographiques
        foreach (['region_id', 'departement_id', 'sous_prefecture_id', 'canton_id', 'tribu_id', 'village_id'] as $fk) {
            if ($request->filled($fk)) {
                $query->where($fk, $request->input($fk));
            }
        }

        // Filtre par profession
        if ($request->filled('profession')) {
            $query->where('profession', 'LIKE', '%' . $request->input('profession') . '%');
        }

        // Filtre par type de pièce, situation matrimoniale, niveau d'étude, consulat
        foreach (['type_piece', 'situation_matrimoniale', 'niveau_etude', 'consulat_rattachement'] as $champ) {
            if ($request->filled($champ)) {
                $query->where($champ, $request->input($champ));
            }
        }

        // Recherche textuelle par nom, prénom, téléphone, numéro de pièce ou code unique (ID)
        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));

            // Extraction de l'ID si le format envoyé est REC-2026-X ou REC-X ou ID numérique
            $cleanId = null;
            if (preg_match('/(?:REC-?\d*-?)?(\d+)/i', $search, $matches)) {
                $cleanId = (int) $matches[1];
            }

            $query->where(function ($q) use ($search, $cleanId) {
                $q->where('nom', 'LIKE', "%{$search}%")
                  ->orWhere('prenom', 'LIKE', "%{$search}%")
                  ->orWhere('telephone', 'LIKE', "%{$search}%")
                  ->orWhere('numero_piece', 'LIKE', "%{$search}%")
                  ->orWhere('code_suivi', 'LIKE', "%{$search}%");

                if ($cleanId !== null && $cleanId > 0) {
                    $q->orWhere('id', $cleanId);
                }
            });
        }

        return response()->json($query->paginate(50));
    }

    // Ce code sert à enregistrer un nouveau ressortissant.
    // Il fonctionne avec les données du formulaire et les pièces jointes envoyées.
    public function store(RessortissantRequest $request): JsonResponse
    {
        $data = $request->validated();

        if (\Illuminate\Support\Facades\Auth::check() && empty($data['user_id'])) {
            $data['user_id'] = \Illuminate\Support\Facades\Auth::id();
        }

        if ($request->hasFile('document_identite')) {
            $data['document_identite_path'] = $request->file('document_identite')->store('documents_identite', 'public');
        }

        if ($request->hasFile('justificatif_domicile')) {
            $data['justificatif_domicile_path'] = $request->file('justificatif_domicile')->store('justificatifs_domicile', 'public');
        }

        $ressortissant = Ressortissant::create($data);

        return response()->json([
            'message' => 'Ressortissant enregistré avec succès.',
            'ressortissant' => $ressortissant,
        ], 201);
    }

    // Ce code sert à afficher les informations détaillées d'un ressortissant.
    public function show(Ressortissant $ressortissant): JsonResponse
    {
        $ressortissant->load(['user', 'district', 'region', 'departement', 'sousPrefecture', 'canton', 'tribu', 'village']);
        return response()->json($ressortissant);
    }

    // Ce code sert à modifier les informations d'un ressortissant existant.
    public function update(RessortissantRequest $request, Ressortissant $ressortissant): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('document_identite')) {
            if ($ressortissant->document_identite_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($ressortissant->document_identite_path);
            }
            $data['document_identite_path'] = $request->file('document_identite')->store('documents_identite', 'public');
        }

        if ($request->hasFile('justificatif_domicile')) {
            if ($ressortissant->justificatif_domicile_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($ressortissant->justificatif_domicile_path);
            }
            $data['justificatif_domicile_path'] = $request->file('justificatif_domicile')->store('justificatifs_domicile', 'public');
        }

        $ressortissant->update($data);

        return response()->json([
            'message' => 'Fiche du ressortissant modifiée avec succès.',
            'ressortissant' => $ressortissant,
        ]);
    }

    // Ce code sert à valider une fiche de recensement en attente (Modération Admin).
    public function valider(Ressortissant $ressortissant): JsonResponse
    {
        $ressortissant->update([
            'statut_validation' => 'valide',
            'motif_rejet' => null,
        ]);

        return response()->json([
            'message' => 'Fiche de recensement validée avec succès.',
            'ressortissant' => $ressortissant,
        ]);
    }

    // Ce code sert à rejeter une fiche de recensement non conforme (Modération Admin).
    public function rejeter(\Illuminate\Http\Request $request, Ressortissant $ressortissant): JsonResponse
    {
        $validated = $request->validate([
            'motif_rejet' => ['nullable', 'string', 'max:500'],
        ]);

        $ressortissant->update([
            'statut_validation' => 'rejete',
            'motif_rejet' => $validated['motif_rejet'] ?? 'Fiche rejetée par l\'administration.',
        ]);

        return response()->json([
            'message' => 'Fiche de recensement rejetée.',
            'ressortissant' => $ressortissant,
        ]);
    }

    // Ce code sert à retirer un ressortissant de la base de données et supprimer ses documents.
    public function destroy(Ressortissant $ressortissant): JsonResponse
    {
        if ($ressortissant->document_identite_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($ressortissant->document_identite_path);
        }
        if ($ressortissant->justificatif_domicile_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($ressortissant->justificatif_domicile_path);
        }

        $ressortissant->delete();

        return response()->json([
            'message' => 'Fiche du ressortissant supprimée avec succès.',
        ]);
    }
}
