<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Canton;
use App\Models\Tribu;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

#[Signature('tribus:import')]
#[Description('Importe la liste des tribus coutumières et les rache aux cantons parentes.')]
class ImportTribus extends Command
{
    /**
     * Exécution de la commande d'importation des tribus.
     */
    public function handle(): int
    {
        $jsonPath = database_path('data/tribus.json');

        if (! File::exists($jsonPath)) {
            $this->error("Fichier introuvable : {$jsonPath}");
            return self::FAILURE;
        }

        $tribusData = json_decode(File::get($jsonPath), true);

        if (! is_array($tribusData)) {
            $this->error("Le fichier tribus.json ne contient pas un tableau JSON valide.");
            return self::FAILURE;
        }

        $this->info("Début de l'importation des tribus depuis database/data/tribus.json...");

        $count = 0;
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
                $count++;
            }
        }

        $this->info("Importation terminée avec succès : {$count} tribus importées et rattachées aux cantons.");

        return self::SUCCESS;
    }
}
