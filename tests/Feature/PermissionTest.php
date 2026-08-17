<?php

use App\Enums\RoleEnum;
use App\Models\Ressortissant;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});

test('l inscription attribue automatiquement le rôle RESSORTISSANT', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'Koffi Paul',
        'telephone' => '+2250102030405',
        'password' => 'password123',
    ]);

    $response->assertStatus(201);
    $user = User::where('name', 'Koffi Paul')->first();
    expect($user->hasRole(RoleEnum::RESSORTISSANT))->toBeTrue();
});

test('un ressortissant ne peut voir que sa propre fiche', function () {
    $citoyen1 = User::create(['name' => 'Citoyen 1', 'email' => 'c1@test.ci', 'password' => bcrypt('password')]);
    $citoyen1->assignRole(RoleEnum::RESSORTISSANT);

    $citoyen2 = User::create(['name' => 'Citoyen 2', 'email' => 'c2@test.ci', 'password' => bcrypt('password')]);
    $citoyen2->assignRole(RoleEnum::RESSORTISSANT);

    $fiche1 = Ressortissant::create(['user_id' => $citoyen1->id, 'nom' => 'Kone', 'prenom' => 'Awa', 'sexe' => 'F', 'pays' => 'Côte d\'Ivoire']);
    $fiche2 = Ressortissant::create(['user_id' => $citoyen2->id, 'nom' => 'Yao', 'prenom' => 'Jean', 'sexe' => 'M', 'pays' => 'Côte d\'Ivoire']);

    // Citoyen 1 accède à sa fiche -> OK (200)
    $this->actingAs($citoyen1)
        ->getJson("/api/ressortissants/{$fiche1->id}")
        ->assertStatus(200);

    // Citoyen 1 tente d'accéder à la fiche du Citoyen 2 -> Interdit (403)
    $this->actingAs($citoyen1)
        ->getJson("/api/ressortissants/{$fiche2->id}")
        ->assertStatus(403);
});

test('un ressortissant ne peut modifier sa fiche que si elle est en attente', function () {
    $citoyen = User::create(['name' => 'Citoyen Test', 'email' => 'ctest@test.ci', 'password' => bcrypt('password')]);
    $citoyen->assignRole(RoleEnum::RESSORTISSANT);

    $fiche = Ressortissant::create([
        'user_id' => $citoyen->id,
        'nom' => 'Kone',
        'prenom' => 'Awa',
        'sexe' => 'F',
        'pays' => 'Côte d\'Ivoire',
        'statut_validation' => 'en_attente',
    ]);

    // Modification tant qu'en attente -> OK
    $this->actingAs($citoyen)
        ->putJson("/api/ressortissants/{$fiche->id}", [
            'nom' => 'Kone Modifié',
            'prenom' => 'Awa',
            'sexe' => 'F',
            'pays' => 'Côte d\'Ivoire',
        ])
        ->assertStatus(200);

    // Passage en 'valide'
    $fiche->update(['statut_validation' => 'valide']);

    // Tentative de modification après validation -> Interdit (403)
    $this->actingAs($citoyen)
        ->putJson("/api/ressortissants/{$fiche->id}", [
            'nom' => 'Kone Re-modifié',
            'prenom' => 'Awa',
            'sexe' => 'F',
            'pays' => 'Côte d\'Ivoire',
        ])
        ->assertStatus(403);
});

test('les statistiques sont interdites aux ressortissants et réservées au Super Admin', function () {
    $citoyen = User::create(['name' => 'Citoyen Simple', 'email' => 'simple@test.ci', 'password' => bcrypt('password')]);
    $citoyen->assignRole(RoleEnum::RESSORTISSANT);

    $admin = User::create(['name' => 'Admin Boss', 'email' => 'boss@anstat.ci', 'password' => bcrypt('password')]);
    $admin->assignRole(RoleEnum::SUPER_ADMIN);

    // Citoyen -> Refusé (403)
    $this->actingAs($citoyen)
        ->getJson('/api/stats/globales')
        ->assertStatus(403);

    // Super Admin -> Autorisé (200)
    $this->actingAs($admin)
        ->getJson('/api/stats/globales')
        ->assertStatus(200);
});
