<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Ressortissant;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

// Ce contrôleur sert à fournir des données agrégées et statistiques pour les tableaux de bord.
// Il fonctionne avec des requêtes GROUP BY ultra-rapides en SQL brut Eloquent.
// Dans le but de donner une vue synthétique de la population recensée (locaux vs diaspora, par sexe, par canton).
class StatistiqueController extends Controller
{
    // Fournit les indicateurs globaux de la plateforme.
    public function globales(): JsonResponse
    {
        $total = Ressortissant::count();

        $parStatut = Ressortissant::query()
            ->select('statut_validation', DB::raw('COUNT(*) as total'))
            ->groupBy('statut_validation')
            ->pluck('total', 'statut_validation');

        $parSexe = Ressortissant::query()
            ->select('sexe', DB::raw('COUNT(*) as total'))
            ->groupBy('sexe')
            ->pluck('total', 'sexe');

        $parPiece = Ressortissant::query()
            ->whereNotNull('type_piece')
            ->select('type_piece', DB::raw('COUNT(*) as total'))
            ->groupBy('type_piece')
            ->pluck('total', 'type_piece');

        $parSituationMatrimoniale = Ressortissant::query()
            ->whereNotNull('situation_matrimoniale')
            ->select('situation_matrimoniale', DB::raw('COUNT(*) as total'))
            ->groupBy('situation_matrimoniale')
            ->pluck('total', 'situation_matrimoniale');

        $parNiveauEtude = Ressortissant::query()
            ->whereNotNull('niveau_etude')
            ->select('niveau_etude', DB::raw('COUNT(*) as total'))
            ->groupBy('niveau_etude')
            ->pluck('total', 'niveau_etude');

        $diasporaCount = Ressortissant::diaspora()->count();
        $localCount = Ressortissant::where('pays', 'Côte d\'Ivoire')->count();

        return response()->json([
            'total_ressortissants' => $total,
            'statuts' => [
                'valide' => $parStatut['valide'] ?? 0,
                'en_attente' => $parStatut['en_attente'] ?? 0,
                'rejete' => $parStatut['rejete'] ?? 0,
            ],
            'repartition_sexe' => [
                'M' => $parSexe['M'] ?? 0,
                'F' => $parSexe['F'] ?? 0,
            ],
            'repartition_geographique' => [
                'local' => $localCount,
                'diaspora' => $diasporaCount,
            ],
            'repartition_piece_identite' => $parPiece,
            'repartition_situation_matrimoniale' => $parSituationMatrimoniale,
            'repartition_niveau_etude' => $parNiveauEtude,
        ]);
    }

    // Fournit les statistiques relatives à la diaspora (ressortissants hors de Côte d'Ivoire).
    public function diaspora(): JsonResponse
    {
        $parPays = Ressortissant::diaspora()
            ->select('pays', DB::raw('COUNT(*) as total'))
            ->groupBy('pays')
            ->orderByDesc('total')
            ->limit(20)
            ->get();

        $parConsulat = Ressortissant::diaspora()
            ->whereNotNull('consulat_rattachement')
            ->select('consulat_rattachement', DB::raw('COUNT(*) as total'))
            ->groupBy('consulat_rattachement')
            ->orderByDesc('total')
            ->limit(20)
            ->get();

        return response()->json([
            'total_diaspora' => Ressortissant::diaspora()->count(),
            'repartition_par_pays' => $parPays,
            'repartition_par_consulat' => $parConsulat,
        ]);
    }

    // Fournit les statistiques relatives à l'ancrage coutumier (Cantons & Villages).
    public function coutumier(): JsonResponse
    {
        $parCanton = Ressortissant::query()
            ->join('cantons', 'ressortissants.canton_id', '=', 'cantons.id')
            ->select('cantons.nom as canton', DB::raw('COUNT(ressortissants.id) as total'))
            ->groupBy('cantons.id', 'cantons.nom')
            ->orderByDesc('total')
            ->limit(15)
            ->get();

        $parVillage = Ressortissant::query()
            ->join('villages', 'ressortissants.village_id', '=', 'villages.id')
            ->select('villages.nom as village', DB::raw('COUNT(ressortissants.id) as total'))
            ->groupBy('villages.id', 'villages.nom')
            ->orderByDesc('total')
            ->limit(15)
            ->get();

        return response()->json([
            'top_cantons' => $parCanton,
            'top_villages' => $parVillage,
        ]);
    }
}
