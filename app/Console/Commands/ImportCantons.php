<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Canton;
use App\Models\SousPrefecture;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

// Ce code sert à exécuter la commande d'importation des cantons coutumiers depuis un fichier JSON.
// Il fonctionne avec le modèle Canton, le modèle SousPrefecture et le composant File de Laravel.
// Dans le but d'importer les cantons et de créer leurs rattachements aux sous-préfectures.
// Pour régler l'automatisation du peuplement du référentiel coutumier cantonal.
#[Signature('cantons:import')]
#[Description('Importe la liste exhaustive des cantons coutumiers et les relie aux sous-préfectures ANStat.')]
class ImportCantons extends Command
{
    // Ce code sert à lire le fichier JSON des cantons et créer chaque canton en base.
    // Il fonctionne avec le fichier 'database/data/cantons.json' et la méthode updateOrCreate().
    // Dans le but d'insérer les cantons et lier leurs sous-préfectures associées.
    // Pour régler le chargement du référentiel cantonal.
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

        $specificCount = 0;
        // On parcourt chaque élément du tableau $cantonsData pour extraire le canton et rechercher sa sous-préfecture
        foreach ($cantonsData as $item) {
            $nom = trim((string) $item['nom']);
            $spNom = trim((string) ($item['sous_prefecture_nom'] ?? ''));

            $sp = null;
            if ($spNom !== '') {
                // Recherche la sous-préfecture par correspondance partielle de nom
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

        $this->info("Importation terminée avec succès : {$specificCount} cantons spécifiques importés et {$fallbackCount} cantons de repli créés.");

        return self::SUCCESS;
    }
}
