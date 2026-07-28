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

#[Signature('anstat:import')]
#[Description('Importe les données géographiques de l\'API ANStat dans la base de données.')]
class ImportAnstat extends Command
{
    /**
     * URL de base de l'API publique ANStat.
     */
    private const BASE_URL = 'https://api-public.anstat.ci/api/v1';

    /**
     * Execute the console command.
     */
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

    private function importSousPrefectures(): void
    {
        $count = $this->fetchAll('sous-prefectures', function (array $row): void {
            // Clé naturelle composite : cod_sp seul n'est pas unique entre départements.
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

    /**
     * Appelle un endpoint ANStat (GET ou POST selon l'endpoint), suit la
     * pagination (champ `next`), et applique $handle à chaque ligne de `results`.
     * Retourne le nombre total de lignes traitées.
     */
    private function fetchAll(string $endpoint, callable $handle, string $method = 'post'): int
    {
        $url = self::BASE_URL . '/' . $endpoint;
        $count = 0;

        // Tant qu'il reste une page à charger (next non nul), on continue.
        while ($url !== null) {
            $payload = Http::timeout(30)->retry(3, 1000)->send(strtoupper($method), $url)->throw()->json();

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
