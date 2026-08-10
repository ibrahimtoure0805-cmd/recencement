<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Departement;
use App\Models\District;
use App\Models\Region;
use App\Models\SousPrefecture;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

// Ce code sert à exécuter la commande Artisan d'importation des découpages officiels ANStat.
// Il fonctionne avec le client HTTP de Laravel et les API publiques de l'ANStat.
// Dans le but de peupler les tables districts, regions, departements et sous_prefectures.
// Pour régler la synchronisation du référentiel territorial national.
#[Signature('anstat:import')]
#[Description('Importe les données géographiques de l\'API ANStat dans la base de données.')]
class ImportAnstat extends Command
{
    /**
     * URL de base de l'API publique ANStat.
     */
    private const BASE_URL = 'https://api-public.anstat.ci/api/v1';

    // Ce code sert d'entrée principale pour la commande d'importation.
    // Il fonctionne en enchaînant les appels aux méthodes d'importation spécialisées.
    // Dans le but d'importer dans l'ordre strict de dépendance les entités géographiques.
    // Pour régler le respect des contraintes de clés étrangères lors de l'insertion.
    public function handle(): int
    {
        // Ordre imposé par les clés étrangères : un enfant ne peut exister sans son parent.
        $this->importDistricts();
        $this->importRegions();
        $this->importDepartements();
        $this->importSousPrefectures();

        $this->info('Import ANStat terminé.');

        return self::SUCCESS;
    }

    // Ce code sert à importer et mettre à jour les districts.
    // Il fonctionne avec l'endpoint 'districts' via la méthode GET.
    // Dans le but d'insérer ou mettre à jour chaque district dans la table districts.
    // Pour régler l'initialisation du niveau territorial le plus haut.
    private function importDistricts(): void
    {
        // L'endpoint districts n'accepte que GET (les autres n'acceptent que POST).
        $count = $this->fetchAll('districts', function (array $row): void {
            District::updateOrCreate(
                ['code_district' => $row['code_district']],
                [
                    'nom_district' => $row['nom_district'],
                    'annee' => $row['annee'],
                ],
            );
        }, 'get');

        $this->info("Districts : {$count} importés.");
    }

    // Ce code sert à importer les régions administratives.
    // Il fonctionne avec l'endpoint 'regions' et le modèle Region.
    // Dans le but d'enregistrer toutes les régions rattachées aux districts.
    // Pour régler la mise à jour des régions.
    private function importRegions(): void
    {
        $count = $this->fetchAll('regions', function (array $row): void {
            Region::updateOrCreate(
                ['cod_reg' => $row['cod_reg']],
                [
                    'nom_reg' => $row['nom_reg'],
                    'cod_dist' => $row['cod_dist'],
                    'annee' => $row['annee'],
                ],
            );
        });

        $this->info("Régions : {$count} importées.");
    }

    // Ce code sert à importer les départements.
    // Il fonctionne avec l'endpoint 'departements' de l'API ANStat.
    // Dans le but de persister chaque département en base.
    // Pour régler le maillage départemental.
    private function importDepartements(): void
    {
        $count = $this->fetchAll('departements', function (array $row): void {
            Departement::updateOrCreate(
                ['cod_dep' => $row['cod_dep']],
                [
                    'nom_dep' => $row['nom_dep'],
                    'cod_reg' => $row['cod_reg'],
                    'annee' => $row['annee'],
                ],
            );
        });

        $this->info("Départements : {$count} importés.");
    }

    // Ce code sert à importer les 526 sous-préfectures.
    // Il fonctionne avec l'endpoint 'sous-prefectures' et le modèle SousPrefecture.
    // Dans le but d'enregistrer l'ensemble des sous-préfectures avec leur anstat_id.
    // Pour régler la synchronisation du découpage sous-préfectoral.
    private function importSousPrefectures(): void
    {
        $count = $this->fetchAll('sous-prefectures', function (array $row): void {
            SousPrefecture::updateOrCreate(
                ['anstat_id' => $row['id']],
                [
                    'cod_sp' => $row['cod_sp'],
                    'nom_sp' => $row['nom_sp'],
                    'cod_dep' => $row['cod_dep'],
                    'annee' => $row['annee'],
                ],
            );
        });

        $this->info("Sous-préfectures : {$count} importées.");
    }

    // Ce code sert à effectuer le moissonnage paginé de n'importe quel endpoint ANStat.
    // Il fonctionne avec le composant HTTP Client de Laravel en gérant le lien de pagination 'next'.
    // Dans le but d'exécuter un callback sur chaque enregistrement et retourner le total importé.
    // Pour régler le chargement de volumes de données répartis sur plusieurs pages HTTP.
    private function fetchAll(string $endpoint, callable $handle, string $method = 'post'): int
    {
        $url = self::BASE_URL . '/' . $endpoint;
        $count = 0;

        // On parcourt chaque page d'API retournée par ANStat jusqu'à ce que le lien 'next' soit nul
        while ($url !== null) {
            $payload = Http::timeout(30)->retry(3, 1000)->send(strtoupper($method), $url)->throw()->json();

            // On parcourt les éléments du tableau 'results' de la page courante pour appliquer le callback $handle
            foreach ($payload['results'] as $row) {
                $handle($row);
                $count++;
            }

            // L'API renvoie l'URL complète de la page suivante, ou null à la fin.
            $url = $payload['result_info']['next'] ?? null;
        }

        return $count;
    }
}
