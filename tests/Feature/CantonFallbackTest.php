<?php

declare(strict_types=1);

use App\Models\Canton;
use App\Models\Departement;
use App\Models\District;
use App\Models\Region;
use App\Models\Ressortissant;
use App\Models\SousPrefecture;
use Database\Seeders\CantonSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->dist = District::create(['code_district' => 'DIST_01', 'nom_district' => 'District Test', 'annee' => '2021']);
    $this->reg  = Region::create(['cod_reg' => 'REG_01', 'nom_reg' => 'Region Test', 'cod_dist' => 'DIST_01', 'annee' => '2021']);
    $this->dep  = Departement::create(['cod_dep' => 'DEP_01', 'nom_dep' => 'Departement Test', 'cod_reg' => 'REG_01', 'annee' => '2021']);
});

test('CantonSeeder importe les cantons spécifiques et génère les cantons de repli pour les sous-préfectures sans canton', function () {
    // Créer une sous-préfecture avec canton connu (BROBO) et une sans canton (KIEMOU)
    $spBrobo = SousPrefecture::create([
        'anstat_id' => 1,
        'cod_dep'   => 'DEP_01',
        'cod_sp'    => '01',
        'nom_sp'    => 'BROBO',
        'annee'     => '2021',
    ]);

    $spKiemou = SousPrefecture::create([
        'anstat_id' => 2,
        'cod_dep'   => 'DEP_01',
        'cod_sp'    => '02',
        'nom_sp'    => 'KIEMOU',
        'annee'     => '2021',
    ]);

    $this->seed(CantonSeeder::class);

    // Vérifier que le canton spécifique Ahaly est rattaché à BROBO avec is_defaut = false
    $cantonAhaly = Canton::where('nom', 'Ahaly')->first();
    expect($cantonAhaly)->not->toBeNull();
    expect($cantonAhaly->sous_prefecture_id)->toBe($spBrobo->id);
    expect($cantonAhaly->is_defaut)->toBeFalse();

    // Vérifier que KIEMOU a un canton de repli portant son nom avec is_defaut = true
    $cantonKiemou = Canton::where('nom', 'KIEMOU')->first();
    expect($cantonKiemou)->not->toBeNull();
    expect($cantonKiemou->sous_prefecture_id)->toBe($spKiemou->id);
    expect($cantonKiemou->is_defaut)->toBeTrue();
});

test('GET /api/cantons retourne la liste avec l attribut is_defaut', function () {
    $sp = SousPrefecture::create([
        'anstat_id' => 10,
        'cod_dep'   => 'DEP_01',
        'cod_sp'    => '10',
        'nom_sp'    => 'TEST_SP',
        'annee'     => '2021',
    ]);

    Canton::create([
        'nom' => 'Canton Specifique',
        'sous_prefecture_id' => $sp->id,
        'is_defaut' => false,
    ]);

    Canton::create([
        'nom' => 'TEST_SP',
        'sous_prefecture_id' => $sp->id,
        'is_defaut' => true,
    ]);

    $response = $this->getJson('/api/cantons');
    $response->assertStatus(200);

    $data = $response->json();
    $specific = collect($data)->firstWhere('nom', 'Canton Specifique');
    $fallback = collect($data)->firstWhere('nom', 'TEST_SP');

    expect($specific['is_defaut'])->toBeFalse();
    expect($fallback['is_defaut'])->toBeTrue();
});

test('peut enregistrer un ressortissant avec un canton de repli', function () {
    $sp = SousPrefecture::create([
        'anstat_id' => 20,
        'cod_dep'   => 'DEP_01',
        'cod_sp'    => '20',
        'nom_sp'    => 'KIEMOU',
        'annee'     => '2021',
    ]);

    $canton = Canton::create([
        'nom' => 'KIEMOU',
        'sous_prefecture_id' => $sp->id,
        'is_defaut' => true,
    ]);

    $response = $this->postJson('/api/ressortissants', [
        'nom'                => 'Soro',
        'prenom'             => 'Katiéné',
        'sexe'               => 'M',
        'sous_prefecture_id' => $sp->id,
        'canton_id'          => $canton->id,
    ]);

    $response->assertStatus(201);
    expect($response->json('ressortissant.canton_id'))->toBe($canton->id);
    expect($response->json('ressortissant.sous_prefecture_id'))->toBe($sp->id);
});
