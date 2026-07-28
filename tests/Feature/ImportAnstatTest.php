<?php

use App\Models\Departement;
use App\Models\District;
use App\Models\Region;
use App\Models\SousPrefecture;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

/**
 * districts.json est réutilisé tel quel (page unique réelle, 14 lignes, sans parent).
 * Les 3 autres niveaux sont synthétiques : regions.json/departements.json/sous-prefecture.json
 * ne contiennent que la page 1 de l'API réelle et référencent des codes parents absents
 * de cette page (ex. départements → régions "13"/"30"/"33" non présentes en page 1),
 * ce qui casserait les contraintes de clé étrangère si on les réutilisait bruts.
 */
function fakeAnstatApi(): void
{
    $districts = json_decode(
        file_get_contents(base_path('database/data/anstat/districts.json')),
        true,
    );

    Http::fake([
        'api-public.anstat.ci/api/v1/districts*' => Http::response($districts),
        'api-public.anstat.ci/api/v1/regions*' => Http::response([
            'result_info' => ['next' => null, 'total_count' => 2],
            'results' => [
                ['id' => 101, 'cod_reg' => 'R1', 'nom_reg' => 'REGION UN', 'cod_dist' => '01', 'annee' => '2021'],
                ['id' => 102, 'cod_reg' => 'R2', 'nom_reg' => 'REGION DEUX', 'cod_dist' => '02', 'annee' => '2021'],
            ],
        ]),
        'api-public.anstat.ci/api/v1/departements*' => Http::response([
            'result_info' => ['next' => null, 'total_count' => 2],
            'results' => [
                ['id' => 201, 'cod_dep' => 'D1', 'nom_dep' => 'DEPARTEMENT UN', 'cod_reg' => 'R1', 'annee' => '2021'],
                ['id' => 202, 'cod_dep' => 'D2', 'nom_dep' => 'DEPARTEMENT DEUX', 'cod_reg' => 'R2', 'annee' => '2021'],
            ],
        ]),
        // cod_sp "01" volontairement répété sur 2 départements différents :
        // reproduit le cas documenté où cod_sp n'est pas unique entre départements.
        'api-public.anstat.ci/api/v1/sous-prefectures*' => Http::response([
            'result_info' => ['next' => null, 'total_count' => 2],
            'results' => [
                ['id' => 301, 'cod_sp' => '01', 'nom_sp' => 'SOUS-PREF UN', 'cod_dep' => 'D1', 'annee' => '2021'],
                ['id' => 302, 'cod_sp' => '01', 'nom_sp' => 'SOUS-PREF DEUX', 'cod_dep' => 'D2', 'annee' => '2021'],
            ],
        ]),
    ]);
}

it('importe les données ANStat dans les 4 tables', function () {
    fakeAnstatApi();

    $this->artisan('anstat:import')->assertExitCode(0);

    expect(District::count())->toBe(14)
        ->and(Region::count())->toBe(2)
        ->and(Departement::count())->toBe(2)
        ->and(SousPrefecture::count())->toBe(2);

    expect(District::where('code_district', '01')->first()?->nom_district)
        ->toBe("AUTONOME D'ABIDJAN");

    Http::assertSent(fn ($request) => $request->method() === 'GET' && str_contains($request->url(), '/districts'));
    Http::assertSent(fn ($request) => $request->method() === 'POST' && str_contains($request->url(), '/regions'));
});

it('suit la pagination sur plusieurs pages', function () {
    Http::fake([
        'api-public.anstat.ci/api/v1/districts*' => Http::sequence()
            ->push([
                'result_info' => ['next' => 'https://api-public.anstat.ci/api/v1/districts?page=2', 'total_count' => 3],
                'results' => [
                    ['id' => 1, 'code_district' => '01', 'nom_district' => 'DISTRICT A', 'annee' => '2021'],
                    ['id' => 2, 'code_district' => '02', 'nom_district' => 'DISTRICT B', 'annee' => '2021'],
                ],
            ])
            ->push([
                'result_info' => ['next' => null, 'total_count' => 3],
                'results' => [
                    ['id' => 3, 'code_district' => '03', 'nom_district' => 'DISTRICT C', 'annee' => '2021'],
                ],
            ]),
        'api-public.anstat.ci/api/v1/regions*' => Http::response(['result_info' => ['next' => null, 'total_count' => 0], 'results' => []]),
        'api-public.anstat.ci/api/v1/departements*' => Http::response(['result_info' => ['next' => null, 'total_count' => 0], 'results' => []]),
        'api-public.anstat.ci/api/v1/sous-prefectures*' => Http::response(['result_info' => ['next' => null, 'total_count' => 0], 'results' => []]),
    ]);

    $this->artisan('anstat:import')->assertExitCode(0);

    // 2 lignes en page 1 + 1 ligne en page 2 : la pagination a bien été suivie jusqu'au bout.
    expect(District::count())->toBe(3);
});

it('est idempotent : relancer la commande ne crée pas de doublons', function () {
    fakeAnstatApi();

    $this->artisan('anstat:import')->assertExitCode(0);
    $this->artisan('anstat:import')->assertExitCode(0);

    expect(District::count())->toBe(14)
        ->and(Region::count())->toBe(2)
        ->and(Departement::count())->toBe(2)
        ->and(SousPrefecture::count())->toBe(2);
});
