<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Ressortissant;
use App\Models\Village;

/**
 * Ce code s'exécute automatiquement lorsqu'un ressortissant est enregistré ou modifié.
 * Il sert à trouver et remplir automatiquement sa région, sa sous-préfecture, sa tribu et son canton
 * dès qu'on indique son village d'origine.
 */
class RessortissantObserver
{
    /**
     * Mémoire temporaire pour garder en souvenir les villages déjà cherchés.
     * Cela évite de faire trop de recherches inutiles si on enregistre plusieurs personnes du même village d'un coup.
     * @var array<int, Village|null>
     */
    private static array $villageCache = [];

    /**
     * Vider la mémoire temporaire des villages (utile pendant les tests).
     */
    public static function clearCache(): void
    {
        self::$villageCache = [];
    }

    /**
     * Cette fonction s'exécute automatiquement juste avant d'enregistrer une fiche de ressortissant.
     */
    public function saving(Ressortissant $ressortissant): void
    {
        // 1. Si le village n'a pas été modifié ou changé, on n'a rien à recalculer.
        if (! $ressortissant->isDirty('village_id')) {
            return;
        }

        // 2. Si le village a été effacé (mis à vide), on efface aussi tous les autres rattachements.
        if (is_null($ressortissant->village_id)) {
            $this->resetHierarchy($ressortissant);
            return;
        }

        // 3. On récupère les informations du village (depuis la mémoire temporaire si disponible, sinon depuis la base).
        $villageId = (int) $ressortissant->village_id;

        if (! array_key_exists($villageId, self::$villageCache)) {
            self::$villageCache[$villageId] = Village::with([
                'tribu.canton.sousPrefecture.departement.region.district',
            ])->find($villageId);
        }

        $village = self::$villageCache[$villageId];

        // 4. Si le village est trouvé, on remplit automatiquement toute la chaîne (tribu, canton, sous-préfecture, département, région, district).
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
            // Si le village indiqué n'existe pas, on efface les rattachements par sécurité.
            $this->resetHierarchy($ressortissant);
        }
    }

    /**
     * Efface tous les champs de rattachement (tribu, canton, sous-préfecture, etc.).
     */
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
