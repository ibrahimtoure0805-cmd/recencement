<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Ressortissant;
use App\Models\Village;

// Ce code sert à observer automatiquement le cycle de vie du modèle Ressortissant.
// Il fonctionne avec les évènements d'enregistrement Eloquent et le modèle Village.
// Dans le but d'auto-compléter la hiérarchie territoriale/coutumière et d'assigner un code de suivi unique.
// Pour régler l'automatisation des liens géographiques pour éviter la saisie manuelle redondante.
class RessortissantObserver
{
    /**
     * Mémoire temporaire pour garder en souvenir les villages déjà cherchés.
     * Cela évite de faire trop de recherches inutiles si on enregistre plusieurs personnes du même village d'un coup.
     * @var array<int, Village|null>
     */
    private static array $villageCache = [];

    // Ce code sert à réinitialiser le cache mémoire statique des villages.
    // Il fonctionne avec la propriété statique $villageCache.
    // Dans le but de vider le tableau lors de l'exécution des suites de tests unitaires/fonctionnels.
    // Pour régler l'isolation des données entre deux cas de tests.
    public static function clearCache(): void
    {
        self::$villageCache = [];
    }

    // Ce code sert à intercepter l'évènement 'saving' d'un ressortissant.
    // Il fonctionne avec le modèle Ressortissant en cours d'enregistrement et la relation cascade des villages.
    // Dans le but d'affecter le code de suivi et de déduire la tribu, le canton, la sous-préfecture, le département et la région.
    // Pour régler la garantie d'intégrité et la cohérence de l'arbre territorial sans effort utilisateur.
    public function saving(Ressortissant $ressortissant): void
    {
        // Génération automatique du code de suivi unique (ex: REC-2026-X8K92)
        if (empty($ressortissant->code_suivi)) {
            $year = date('Y');
            $rand = strtoupper(\Illuminate\Support\Str::random(5));
            $ressortissant->code_suivi = "REC-{$year}-{$rand}";
        }

        // Si le village n'a pas été modifié ou changé, on n'a rien à recalculer
        if (! $ressortissant->isDirty('village_id')) {
            return;
        }

        // Si le village a été effacé (mis à vide), on efface aussi tous les autres rattachements
        if (is_null($ressortissant->village_id)) {
            $this->resetHierarchy($ressortissant);
            return;
        }

        // On récupère les informations du village (depuis la mémoire temporaire si disponible, sinon depuis la base)
        $villageId = (int) $ressortissant->village_id;

        if (! array_key_exists($villageId, self::$villageCache)) {
            self::$villageCache[$villageId] = Village::with([
                'tribu.canton.sousPrefecture.departement.region.district',
            ])->find($villageId);
        }

        $village = self::$villageCache[$villageId];

        // Si le village est trouvé, on remplit automatiquement toute la chaîne (tribu, canton, sous-préfecture, département, région, district)
        if ($village) {
            $tribu    = $village->tribu;
            $canton   = $tribu?->canton;
            $sp       = $canton?->sousPrefecture;
            $dept     = $sp?->departement;
            $region   = $dept?->region;
            $district = $region?->district;

            $ressortissant->fill([
                'tribu_id'           => $tribu?->id,
                'canton_id'          => $canton?->id,
                'sous_prefecture_id' => $sp?->id,
                'departement_id'     => $dept?->id,
                'region_id'          => $region?->id,
                'district_id'        => $district?->id,
            ]);
        } else {
            // Si le village indiqué n'existe pas, on efface les rattachements par sécurité
            $this->resetHierarchy($ressortissant);
        }
    }

    // Ce code sert à réinitialiser l'ensemble des clés étrangères de la hiérarchie territoriale.
    // Il fonctionne avec la méthode fill() sur l'instance Ressortissant.
    // Dans le but d'assigner la valeur null à toutes les clés étrangères géographiques.
    // Pour régler la remise à zéro des liaisons lorsque le village d'origine est retiré.
    private function resetHierarchy(Ressortissant $ressortissant): void
    {
        $ressortissant->fill([
            'tribu_id'           => null,
            'canton_id'          => null,
            'sous_prefecture_id' => null,
            'departement_id'    => null,
            'region_id'         => null,
            'district_id'       => null,
        ]);
    }
}
