<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Canton;
use App\Models\Tribu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class TribuSeeder extends Seeder
{
    /**
     * Exécute l'importation des tribus à partir du fichier JSON database/data/tribus.json.
     */
    public function run(): void
    {
        $jsonPath = database_path('data/tribus.json');

        if (! File::exists($jsonPath)) {
            $this->command->error("Fichier introuvable : {$jsonPath}");
            return;
        }

        $tribusData = json_decode(File::get($jsonPath), true);

        if (! is_array($tribusData)) {
            $this->command->error("Le fichier tribus.json ne contient pas un tableau JSON valide.");
            return;
        }

        foreach ($tribusData as $item) {
            $nom = trim((string) $item['nom']);
            $cantonNom = trim((string) ($item['canton_nom'] ?? ''));

            $canton = null;
            if ($cantonNom !== '') {
                $canton = Canton::where('nom', 'LIKE', "%{$cantonNom}%")->first();
            }

            if ($canton) {
                Tribu::updateOrCreate(
                    ['nom' => $nom, 'canton_id' => $canton->id]
                );
            }
        }

        $this->command->info("Importation terminée : " . count($tribusData) . " tribus importées.");
    }
}
