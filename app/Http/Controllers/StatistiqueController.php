<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Canton;
use App\Models\Departement;
use App\Models\District;
use App\Models\Pays;
use App\Models\Region;
use App\Models\Ressortissant;
use App\Models\SousPrefecture;
use App\Models\Tribu;
use App\Models\User;
use App\Models\Village;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

// Ce code sert à fournir des données agrégées et statistiques calculées en temps réel sur la base de données.
// Il fonctionne avec le modèle Ressortissant, les tables de référentiels et des requêtes SQL d'agrégation.
// Dans le but de donner une vue décisionnelle exacte de la population recensée et du maillage territorial.
class StatistiqueController extends Controller
{
    // Indicateurs démographiques et de conformité globaux calculés depuis la BDD
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
            ->where('type_piece', '!=', '')
            ->select('type_piece', DB::raw('COUNT(*) as total'))
            ->groupBy('type_piece')
            ->pluck('total', 'type_piece');

        $parSituationMatrimoniale = Ressortissant::query()
            ->whereNotNull('situation_matrimoniale')
            ->where('situation_matrimoniale', '!=', '')
            ->select('situation_matrimoniale', DB::raw('COUNT(*) as total'))
            ->groupBy('situation_matrimoniale')
            ->pluck('total', 'situation_matrimoniale');

        $parNiveauEtude = Ressortissant::query()
            ->whereNotNull('niveau_etude')
            ->where('niveau_etude', '!=', '')
            ->select('niveau_etude', DB::raw('COUNT(*) as total'))
            ->groupBy('niveau_etude')
            ->pluck('total', 'niveau_etude');

        $diasporaCount = Ressortissant::diaspora()->count();
        $localCount = Ressortissant::where('pays', 'Côte d\'Ivoire')->count();

        return response()->json([
            'total_ressortissants' => $total,
            'statuts' => [
                'valide' => (int) ($parStatut['valide'] ?? 0),
                'en_attente' => (int) ($parStatut['en_attente'] ?? 0),
                'rejete' => (int) ($parStatut['rejete'] ?? 0),
            ],
            'repartition_sexe' => [
                'M' => (int) ($parSexe['M'] ?? 0),
                'F' => (int) ($parSexe['F'] ?? 0),
            ],
            'repartition_geographique' => [
                'local' => $localCount,
                'diaspora' => $diasporaCount,
            ],
            'repartition_piece_identite' => $parPiece,
            'repartition_situation_matrimoniale' => $parSituationMatrimoniale,
            'repartition_niveau_etude' => $parNiveauEtude,
            'referentiels' => [
                'districts' => District::count(),
                'regions' => Region::count(),
                'departements' => Departement::count(),
                'sous_prefectures' => SousPrefecture::count(),
                'cantons' => Canton::count(),
                'tribus' => Tribu::count(),
                'villages' => Village::count(),
                'pays' => Pays::count(),
                'users' => User::count(),
            ]
        ]);
    }

    // Répartition de la population vivant à l'étranger
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
            ->where('consulat_rattachement', '!=', '')
            ->select('consulat_rattachement', DB::raw('COUNT(*) as total'))
            ->groupBy('consulat_rattachement')
            ->orderByDesc('total')
            ->limit(20)
            ->get();

        return response()->json([
            'total_diaspora' => Ressortissant::diaspora()->count(),
            'total_pays_referentiel' => Pays::count(),
            'repartition_par_pays' => $parPays,
            'repartition_par_consulat' => $parConsulat,
        ]);
    }

    // Statistiques territoriales et coutumières (Cantons, Tribus, Villages d'origine)
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
            'total_cantons' => Canton::count(),
            'total_tribus' => Tribu::count(),
            'total_villages' => Village::count(),
            'total_sous_prefectures' => SousPrefecture::count(),
            'top_cantons' => $parCanton,
            'top_villages' => $parVillage,
        ]);
    }
}
