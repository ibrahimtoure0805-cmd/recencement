<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Canton;
use App\Models\SousPrefecture;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CantonSeeder extends Seeder
{
    /**
     * Exécute l'importation des cantons à partir du fichier JSON database/data/cantons.json.
     */
    public function run(): void
    {
        $jsonPath = database_path('data/cantons.json');

        if (! File::exists($jsonPath)) {
            $this->command->error("Fichier introuvable : {$jsonPath}");
            return;
        }

        $cantonsData = json_decode(File::get($jsonPath), true);

        if (! is_array($cantonsData)) {
            $this->command->error("Le fichier cantons.json ne contient pas un tableau JSON valide.");
            return;
        }

        $specificCount = 0;
        foreach ($cantonsData as $item) {
            $nom = trim($item['nom']);
            $spNom = trim($item['sous_prefecture_nom'] ?? '');

            $sp = null;
            if ($spNom !== '') {
                $sp = SousPrefecture::where('nom_sp', 'LIKE', "%{$spNom}%")->first();
            }

            Canton::updateOrCreate(
                ['nom' => $nom],
                [
                    'sous_prefecture_id' => $sp?->id,
                    'is_defaut' => false,
                ]
            );
            $specificCount++;
        }

        // Pour chaque sous-préfecture n'ayant aucun canton associé, créer un canton par défaut (is_defaut = true)
        $fallbackCount = 0;
        $allSp = SousPrefecture::all();
        foreach ($allSp as $sp) {
            if (! Canton::where('sous_prefecture_id', $sp->id)->exists()) {
                Canton::updateOrCreate(
                    [
                        'nom' => $sp->nom_sp,
                        'sous_prefecture_id' => $sp->id,
                    ],
                    [
                        'is_defaut' => true,
                    ]
                );
                $fallbackCount++;
            }
        }

        $this->command->info("Importation terminée : {$specificCount} cantons spécifiques importés et {$fallbackCount} cantons de repli créés pour les sous-préfectures sans canton.");
    }
}
