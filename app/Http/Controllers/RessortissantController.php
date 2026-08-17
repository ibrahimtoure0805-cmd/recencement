<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\RessortissantRequest;
use App\Models\Ressortissant;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

// Ce code sert à gérer l'accès et les modifications des fiches de ressortissants.
// Il fonctionne avec le modèle Ressortissant et les demandes de l'API.
// Dans le but de lister, créer, afficher, modifier ou supprimer des fiches de recensement.
// Pour régler la centralisation des actions de recensement dans des fonctions dédiées.

class RessortissantController extends Controller
{
    // Ce code sert à lister tous les ressortissants enregistrés avec filtres optionnels (Réservé Administrateurs).
    public function index(Request $request): JsonResponse
    {
        Gate::authorize('viewAny', Ressortissant::class);

        $query = Ressortissant::query()
            ->with(['user', 'district', 'region', 'departement', 'sousPrefecture', 'canton', 'tribu', 'village'])
            ->latest();

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

        // Recherche textuelle par nom, prénom, téléphone, numéro de pièce ou code unique (ID / code_suivi)
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

        $authUser = $request->user('sanctum') ?? Auth::user();
        if ($authUser && empty($data['user_id'])) {
            $data['user_id'] = $authUser->id;
        }

        // Si user_id est encore vide, tenter de lier automatiquement avec le compte utilisateur via le téléphone
        if (empty($data['user_id']) && !empty($data['telephone'])) {
            $cleanPhone = preg_replace('/\D/', '', (string) $data['telephone']);
            if (str_starts_with($cleanPhone, '225') && strlen($cleanPhone) > 8) {
                $cleanPhone = substr($cleanPhone, 3);
            }
            if (!empty($cleanPhone)) {
                $matchedUser = User::where(function ($q) use ($cleanPhone) {
                    $q->whereRaw("REPLACE(REPLACE(REPLACE(telephone, '+', ''), ' ', ''), '-', '') LIKE ?", ["%{$cleanPhone}%"])
                      ->orWhere('email', 'LIKE', "{$cleanPhone}@%")
                      ->orWhere('email', 'LIKE', "225{$cleanPhone}@%");
                })->first();
                if ($matchedUser) {
                    $data['user_id'] = $matchedUser->id;
                }
            }
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

    // Ce code sert à récupérer la fiche de recensement du citoyen actuellement connecté.
    public function monDossier(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Non authentifié.'], 401);
        }

        $ressortissant = Ressortissant::where('user_id', $user->id)
            ->with(['user', 'district', 'region', 'departement', 'sousPrefecture', 'canton', 'tribu', 'village'])
            ->latest()
            ->first();

        // Si non trouvé par user_id, tentative de rattachement automatique via le numéro de téléphone / email
        if (!$ressortissant) {
            $phone = $user->telephone ?: ($user->email ? explode('@', $user->email)[0] : '');
            $cleanPhone = preg_replace('/\D/', '', (string) $phone);
            if (str_starts_with($cleanPhone, '225') && strlen($cleanPhone) > 8) {
                $cleanPhone = substr($cleanPhone, 3);
            }

            if (!empty($cleanPhone)) {
                $ressortissant = Ressortissant::where(function ($q) use ($cleanPhone) {
                    $q->where('telephone', 'LIKE', "%{$cleanPhone}%")
                      ->orWhereRaw("REPLACE(REPLACE(REPLACE(telephone, '+', ''), ' ', ''), '-', '') LIKE ?", ["%{$cleanPhone}%"]);
                })
                ->with(['user', 'district', 'region', 'departement', 'sousPrefecture', 'canton', 'tribu', 'village'])
                ->latest()
                ->first();

                if ($ressortissant && empty($ressortissant->user_id)) {
                    $ressortissant->update(['user_id' => $user->id]);
                }
            }
        }

        if (!$ressortissant) {
            return response()->json(['message' => 'Aucune fiche de recensement trouvée pour votre compte.'], 404);
        }

        return response()->json($ressortissant);
    }

    // Ce code sert à afficher les informations détaillées d'un ressortissant.
    public function show(Ressortissant $ressortissant): JsonResponse
    {
        Gate::authorize('view', $ressortissant);
        $ressortissant->load(['user', 'district', 'region', 'departement', 'sousPrefecture', 'canton', 'tribu', 'village']);
        return response()->json($ressortissant);
    }

    // Ce code sert à modifier les informations d'un ressortissant existant.
    public function update(RessortissantRequest $request, Ressortissant $ressortissant): JsonResponse
    {
        Gate::authorize('update', $ressortissant);

        $data = $request->validated();

        if ($request->hasFile('document_identite')) {
            if ($ressortissant->document_identite_path) {
                Storage::disk('public')->delete($ressortissant->document_identite_path);
            }
            $data['document_identite_path'] = $request->file('document_identite')->store('documents_identite', 'public');
        }

        if ($request->hasFile('justificatif_domicile')) {
            if ($ressortissant->justificatif_domicile_path) {
                Storage::disk('public')->delete($ressortissant->justificatif_domicile_path);
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
        Gate::authorize('validate', $ressortissant);

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
    public function rejeter(Request $request, Ressortissant $ressortissant): JsonResponse
    {
        Gate::authorize('reject', $ressortissant);

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
        Gate::authorize('delete', $ressortissant);

        if ($ressortissant->document_identite_path) {
            Storage::disk('public')->delete($ressortissant->document_identite_path);
        }
        if ($ressortissant->justificatif_domicile_path) {
            Storage::disk('public')->delete($ressortissant->justificatif_domicile_path);
        }

        $ressortissant->delete();

        return response()->json([
            'message' => 'Fiche du ressortissant supprimée avec succès.',
        ]);
    }
}
