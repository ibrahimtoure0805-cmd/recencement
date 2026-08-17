<?php

use App\Models\Ressortissant;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});

// Ce code sert à valider la création d'un ressortissant et son statut initial.
test('peut créer un ressortissant avec sa profession et son statut par défaut', function () {
    $response = $this->postJson('/api/ressortissants', [
        'nom' => 'Kouadio',
        'prenom' => 'Aya',
        'sexe' => 'F',
        'pays' => 'France',
        'profession' => 'Ingénieure Informatique',
    ]);

    $response->assertStatus(201)
        ->assertJsonPath('ressortissant.nom', 'Kouadio')
        ->assertJsonPath('ressortissant.profession', 'Ingénieure Informatique')
        ->assertJsonPath('ressortissant.statut_validation', 'en_attente');

    $this->assertDatabaseHas('ressortissants', [
        'nom' => 'Kouadio',
        'profession' => 'Ingénieure Informatique',
        'statut_validation' => 'en_attente',
    ]);
});

// Ce code sert à tester la validation administrateur d'un dossier avec Sanctum.
test('un administrateur peut valider une fiche de recensement', function () {
    $admin = User::factory()->create();
    $admin->assignRole('super_admin');
    Sanctum::actingAs($admin);

    $ressortissant = Ressortissant::create([
        'nom' => 'Touré',
        'prenom' => 'Ibrahim',
        'sexe' => 'M',
        'pays' => 'Côte d\'Ivoire',
        'statut_validation' => 'en_attente',
    ]);

    $response = $this->patchJson("/api/ressortissants/{$ressortissant->id}/valider");

    $response->assertStatus(200)
        ->assertJsonPath('ressortissant.statut_validation', 'valide');

    $this->assertDatabaseHas('ressortissants', [
        'id' => $ressortissant->id,
        'statut_validation' => 'valide',
    ]);
});

// Ce code sert à vérifier la procédure de rejet d'une fiche avec motif par un administrateur.
test('un administrateur peut rejeter une fiche de recensement avec motif', function () {
    $admin = User::factory()->create();
    $admin->assignRole('super_admin');
    Sanctum::actingAs($admin);

    $ressortissant = Ressortissant::create([
        'nom' => 'Koffi',
        'prenom' => 'Jean',
        'sexe' => 'M',
        'pays' => 'Côte d\'Ivoire',
        'statut_validation' => 'en_attente',
    ]);

    $response = $this->patchJson("/api/ressortissants/{$ressortissant->id}/rejeter", [
        'motif_rejet' => 'Document d\'identité illisible.',
    ]);

    $response->assertStatus(200)
        ->assertJsonPath('ressortissant.statut_validation', 'rejete')
        ->assertJsonPath('ressortissant.motif_rejet', 'Document d\'identité illisible.');
});

// Ce code sert à vérifier l'exactitude des réponses de l'API des statistiques pour les décideurs.
test('les endpoints de statistiques renvoient les données d agrégation correctes', function () {
    $admin = User::factory()->create();
    $admin->assignRole('super_admin');
    Sanctum::actingAs($admin);

    // 2 ressortissants locaux validés, 1 en attente diaspora
    Ressortissant::create([
        'nom' => 'Yao', 'prenom' => 'Paul', 'sexe' => 'M', 'pays' => 'Côte d\'Ivoire', 'statut_validation' => 'valide', 'type_piece' => 'CNI', 'situation_matrimoniale' => 'marie'
    ]);
    Ressortissant::create([
        'nom' => 'Kone', 'prenom' => 'Fatou', 'sexe' => 'F', 'pays' => 'Côte d\'Ivoire', 'statut_validation' => 'valide', 'type_piece' => 'CNI', 'situation_matrimoniale' => 'celibataire'
    ]);
    Ressortissant::create([
        'nom' => 'Diallo', 'prenom' => 'Mamadou', 'sexe' => 'M', 'pays' => 'Canada', 'statut_validation' => 'en_attente', 'type_piece' => 'Carte Consulaire', 'consulat_rattachement' => 'Consulat d Ottawa'
    ]);

    $response = $this->getJson('/api/stats/globales');

    $response->assertStatus(200)
        ->assertJsonPath('total_ressortissants', 3)
        ->assertJsonPath('statuts.valide', 2)
        ->assertJsonPath('statuts.en_attente', 1)
        ->assertJsonPath('repartition_geographique.diaspora', 1)
        ->assertJsonPath('repartition_geographique.local', 2)
        ->assertJsonPath('repartition_piece_identite.CNI', 2)
        ->assertJsonPath('repartition_piece_identite.Carte Consulaire', 1);
});

// Ce code sert à tester l'envoi multipart d'une fiche avec téléversement de fichier.
test('peut créer un ressortissant de la diaspora avec pièces justificatives uploadées', function () {
    Storage::fake('public');

    $file = UploadedFile::fake()->create('cni.pdf', 500, 'application/pdf');

    $response = $this->postJson('/api/ressortissants', [
        'nom' => 'Bamba',
        'prenom' => 'Sékou',
        'sexe' => 'M',
        'pays' => 'France',
        'type_piece' => 'Carte Consulaire',
        'numero_piece' => 'CC-998877',
        'consulat_rattachement' => 'Consulat Général de Paris',
        'contact_referent_nom' => 'Bamba Bakary',
        'contact_referent_telephone' => '+2250707070707',
        'situation_matrimoniale' => 'marie',
        'niveau_etude' => 'superieur',
        'document_identite' => $file,
    ]);

    $response->assertStatus(201)
        ->assertJsonPath('ressortissant.nom', 'Bamba')
        ->assertJsonPath('ressortissant.type_piece', 'Carte Consulaire')
        ->assertJsonPath('ressortissant.consulat_rattachement', 'Consulat Général de Paris');

    $ressortissant = Ressortissant::where('numero_piece', 'CC-998877')->first();
    expect($ressortissant)->not->toBeNull();
    expect($ressortissant->document_identite_path)->not->toBeNull();

    Storage::disk('public')->assertExists($ressortissant->document_identite_path);
});
