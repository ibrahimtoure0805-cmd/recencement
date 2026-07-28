<?php

use App\Models\Pays;
use App\Models\Ressortissant;
use Database\Seeders\PaysSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PaysSeeder::class);
});

test('seeds exactly 193 UN member countries into pays table', function () {
    expect(Pays::count())->toBe(193);
});

test('sets Cote d Ivoire as default country', function () {
    $ci = Pays::where('nom', "Côte d'Ivoire")->first();

    expect($ci)->not->toBeNull();
    expect($ci->is_default)->toBeTrue();
});

test('GET /api/pays returns all 193 countries with Cote d Ivoire as first element', function () {
    $response = $this->getJson('/api/pays');

    $response->assertStatus(200);
    $data = $response->json();

    expect(count($data))->toBe(193);
    expect($data[0]['nom'])->toBe("Côte d'Ivoire");
    expect($data[0]['is_default'])->toBeTrue();
});

test('can create a ressortissant linked to a pays_id', function () {
    $france = Pays::where('nom', 'France')->first();

    $ressortissant = Ressortissant::create([
        'nom'     => 'Kouamé',
        'prenom'  => 'Michel',
        'sexe'    => 'M',
        'pays_id' => $france->id,
        'pays'    => $france->nom,
    ]);

    expect($ressortissant->pays_id)->toBe($france->id);
    expect($ressortissant->paysRelation->nom)->toBe('France');
});
