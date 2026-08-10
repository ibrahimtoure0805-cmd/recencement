<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Canton;
use App\Models\Tribu;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

// Ce code sert à importer la liste des tribus coutumières depuis un fichier JSON.
// Il fonctionne avec le modèle Tribu, le modèle Canton et le composant File de Laravel.
// Dans le but de charger les tribus et les relier à leurs cantons respectifs.
// Pour régler le besoin d'auto-remplissage du référentiel coutumier intermédiaire.
#[Signature('tribus:import')]
#[Description('Importe la liste des tribus coutumières et les rattache aux cantons parentes.')]
class ImportTribus extends Command
{
    // Ce code sert à lire le fichier JSON des tribus et insérer chaque tribu rattachée à un canton.
    // Il fonctionne avec le fichier 'database/data/tribus.json' et la méthode updateOrCreate().
    // Dans le but de lier les tribus à leurs cantons parents.
    // Pour régler la hiérarchisation entre cantons et tribus.
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
        // On parcourt chaque entrée de $tribusData pour retrouver le canton parent et enregistrer la tribu
        foreach ($tribusData as $item) {
            $nom = trim((string) $item['nom']);
            $cantonNom = trim((string) ($item['canton_nom'] ?? ''));

            $canton = null;
            if ($cantonNom !== '') {
                // Recherche du canton parent correspondant
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
