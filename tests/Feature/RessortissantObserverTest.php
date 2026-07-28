<?php

use App\Models\Canton;
use App\Models\Departement;
use App\Models\District;
use App\Models\Region;
use App\Models\Ressortissant;
use App\Models\SousPrefecture;
use App\Models\Tribu;
use App\Models\Village;
use App\Observers\RessortissantObserver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

beforeEach(function () {
    RessortissantObserver::clearCache();

    // Arbre 1
    $this->district1 = District::create(['code_district' => 'DIST_01', 'nom_district' => 'District Autonome de Yamoussoukro', 'annee' => '2021']);
    $this->region1   = Region::create(['cod_reg' => 'REG_01', 'nom_reg' => 'Bélier', 'cod_dist' => 'DIST_01', 'annee' => '2021']);
    $this->dept1     = Departement::create(['cod_dep' => 'DEP_01', 'nom_dep' => 'Yamoussoukro', 'cod_reg' => 'REG_01', 'annee' => '2021']);
    $this->sp1       = SousPrefecture::create(['anstat_id' => 101, 'cod_sp' => 'SP_01', 'nom_sp' => 'Yamoussoukro SP', 'cod_dep' => 'DEP_01', 'annee' => '2021']);

    $this->canton1  = Canton::create(['nom' => 'Canton Akouè', 'sous_prefecture_id' => $this->sp1->id]);
    $this->tribu1   = Tribu::create(['nom' => 'Tribu N’Gban', 'canton_id' => $this->canton1->id]);
    $this->village1 = Village::create([
        'nom'      => 'N’Gokro',
        'tribu_id' => $this->tribu1->id,
    ]);

    // Arbre 2
    $this->district2 = District::create(['code_district' => 'DIST_02', 'nom_district' => 'District de la Sassandra-Marahoué', 'annee' => '2021']);
    $this->region2   = Region::create(['cod_reg' => 'REG_02', 'nom_reg' => 'Haut-Sassandra', 'cod_dist' => 'DIST_02', 'annee' => '2021']);
    $this->dept2     = Departement::create(['cod_dep' => 'DEP_02', 'nom_dep' => 'Daloa', 'cod_reg' => 'REG_02', 'annee' => '2021']);
    $this->sp2       = SousPrefecture::create(['anstat_id' => 102, 'cod_sp' => 'SP_02', 'nom_sp' => 'Daloa SP', 'cod_dep' => 'DEP_02', 'annee' => '2021']);

    $this->canton2  = Canton::create(['nom' => 'Canton Bété', 'sous_prefecture_id' => $this->sp2->id]);
    $this->tribu2   = Tribu::create(['nom' => 'Tribu Gboguhé', 'canton_id' => $this->canton2->id]);
    $this->village2 = Village::create([
        'nom'      => 'Zaibo',
        'tribu_id' => $this->tribu2->id,
    ]);
});

test('auto-populates hierarchy when creating a ressortissant with village_id', function () {
    $ressortissant = Ressortissant::create([
        'nom'        => 'Kouassi',
        'prenom'     => 'Jean',
        'sexe'       => 'M',
        'pays'       => 'Côte d\'Ivoire',
        'village_id' => $this->village1->id,
    ]);

    expect($ressortissant->village_id)->toBe($this->village1->id);
    expect($ressortissant->tribu_id)->toBe($this->tribu1->id);
    expect($ressortissant->canton_id)->toBe($this->canton1->id);
    expect($ressortissant->sous_prefecture_id)->toBe($this->sp1->id);
    expect($ressortissant->departement_id)->toBe($this->dept1->id);
    expect($ressortissant->region_id)->toBe($this->region1->id);
    expect($ressortissant->district_id)->toBe($this->district1->id);
});

test('updates hierarchy when village_id changes to another village', function () {
    $ressortissant = Ressortissant::create([
        'nom'        => 'Kouadio',
        'prenom'     => 'Ahou',
        'sexe'       => 'F',
        'pays'       => 'Côte d\'Ivoire',
        'village_id' => $this->village1->id,
    ]);

    // Update village to Village 2
    $ressortissant->update(['village_id' => $this->village2->id]);

    expect($ressortissant->village_id)->toBe($this->village2->id);
    expect($ressortissant->tribu_id)->toBe($this->tribu2->id);
    expect($ressortissant->canton_id)->toBe($this->canton2->id);
    expect($ressortissant->sous_prefecture_id)->toBe($this->sp2->id);
    expect($ressortissant->departement_id)->toBe($this->dept2->id);
    expect($ressortissant->region_id)->toBe($this->region2->id);
    expect($ressortissant->district_id)->toBe($this->district2->id);
});

test('resets all hierarchy fields to null when village_id is set to null', function () {
    $ressortissant = Ressortissant::create([
        'nom'        => 'Yao',
        'prenom'     => 'Koffi',
        'sexe'       => 'M',
        'pays'       => 'Côte d\'Ivoire',
        'village_id' => $this->village1->id,
    ]);

    // Unset village
    $ressortissant->update(['village_id' => null]);

    expect($ressortissant->village_id)->toBeNull();
    expect($ressortissant->tribu_id)->toBeNull();
    expect($ressortissant->canton_id)->toBeNull();
    expect($ressortissant->sous_prefecture_id)->toBeNull();
    expect($ressortissant->departement_id)->toBeNull();
    expect($ressortissant->region_id)->toBeNull();
    expect($ressortissant->district_id)->toBeNull();
});

test('rejects non-existent village_id via database foreign key constraint', function () {
    expect(fn () => Ressortissant::create([
        'nom'        => 'Konan',
        'prenom'     => 'Bertin',
        'sexe'       => 'M',
        'pays'       => 'Côte d\'Ivoire',
        'village_id' => 99999,
    ]))->toThrow(\Illuminate\Database\QueryException::class);
});

test('does not re-query or alter hierarchy when updating unrelated attributes', function () {
    $ressortissant = Ressortissant::create([
        'nom'        => 'Bamba',
        'prenom'     => 'Sali',
        'sexe'       => 'F',
        'pays'       => 'Côte d\'Ivoire',
        'village_id' => $this->village1->id,
    ]);

    DB::enableQueryLog();

    $ressortissant->update(['nom' => 'Bamba Married']);

    // Query log should not include queries for Village or SousPrefecture
    $queryLog = DB::getQueryLog();
    $villageQueries = array_filter($queryLog, fn ($q) => str_contains($q['query'], 'villages'));

    expect($villageQueries)->toBeEmpty();
    expect($ressortissant->canton_id)->toBe($this->canton1->id);
});

test('uses memory cache for bulk creation in the same village', function () {
    RessortissantObserver::clearCache();

    DB::enableQueryLog();

    for ($i = 0; $i < 5; $i++) {
        Ressortissant::create([
            'nom'        => "Person $i",
            'prenom'     => 'Test',
            'sexe'       => 'M',
            'pays'       => 'Côte d\'Ivoire',
            'village_id' => $this->village1->id,
        ]);
    }

    $queryLog = DB::getQueryLog();
    $villageQueries = array_filter($queryLog, fn ($q) => str_contains($q['query'], 'villages'));

    // Should only query the database for village 1 EXACTLY ONCE despite 5 creations
    expect(count($villageQueries))->toBe(1);
});
