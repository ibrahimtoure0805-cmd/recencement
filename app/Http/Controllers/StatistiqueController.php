<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Ressortissant;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

// Ce code sert à fournir des données agrégées et statistiques pour les tableaux de bord.
// Il fonctionne avec le modèle Ressortissant et des requêtes SQL d'agrégation.
// Dans le but de donner une vue synthétique de la population recensée (locaux vs diaspora, par sexe, par canton).
// Pour régler la centralisation des calculs d'indicateurs de recensement.
class StatistiqueController extends Controller
{
    // Ce code sert à calculer les indicateurs globaux de la plateforme de recensement.
    // Il fonctionne avec la table des ressortissants en effectuant des requêtes d'agrégation groupées.
    // Dans le but de fournir les totaux par statut de validation, sexe, type de pièce, situation matrimoniale et niveau d'étude.
    // Pour régler la restitution synthétique des données au niveau national.
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

    // Ce code sert à calculer la répartition de la population vivant à l'étranger.
    // Il fonctionne avec le scope diaspora() et agrège les données par pays de résidence et par consulat.
    // Dans le but de transmettre le top des pays d'accueil et des consulats de rattachement.
    // Pour régler le suivi statistique des ressortissants hors du territoire national.
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

    // Ce code sert à mesurer le niveau de rattachement aux cantons et villages d'origine.
    // Il fonctionne avec des requêtes de jointure entre les ressortissants et les tables cantons et villages.
    // Dans le but de faire ressortir les cantons et villages comptant le plus grand nombre d'inscrits.
    // Pour régler le besoin d'analyse démographique et coutumière du territoire.
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
