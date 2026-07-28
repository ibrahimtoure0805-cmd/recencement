<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Canton;
use App\Models\SousPrefecture;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

#[Signature('cantons:import')]
#[Description('Importe la liste exhaustive des cantons coutumiers et les relie aux sous-préfectures ANStat.')]
class ImportCantons extends Command
{
    /**
     * Exécution de la commande d'importation des cantons.
     */
    public function handle(): int
    {
        $jsonPath = database_path('data/cantons.json');

        if (! File::exists($jsonPath)) {
            $this->error("Fichier introuvable : {$jsonPath}");
            return self::FAILURE;
        }

        $cantonsData = json_decode(File::get($jsonPath), true);

        if (! is_array($cantonsData)) {
            $this->error("Le fichier cantons.json ne contient pas un tableau JSON valide.");
            return self::FAILURE;
        }

        $this->info("Début de l'importation des cantons depuis database/data/cantons.json...");

        $count = 0;
        foreach ($cantonsData as $item) {
            $nom = trim((string) $item['nom']);
            $spNom = trim((string) ($item['sous_prefecture_nom'] ?? ''));

            $sp = null;
            if ($spNom !== '') {
                $sp = SousPrefecture::where('nom_sp', 'LIKE', "%{$spNom}%")->first();
            }

            Canton::updateOrCreate(
                ['nom' => $nom],
                ['sous_prefecture_id' => $sp?->id]
            );

            $count++;
        }

        $this->info("Importation terminée avec succès : {$count} cantons importés et reliés aux sous-préfectures.");

        return self::SUCCESS;
    }
}
