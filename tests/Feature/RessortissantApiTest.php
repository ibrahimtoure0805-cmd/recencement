// Ce code sert à tester les fonctionnalités de l'API de gestion des ressortissants.
// Il fonctionne avec le framework Pest / PHPUnit et le trait RefreshDatabase de Laravel.
// Dans le but de s'assurer de la validité de la création, la modération et les agrégations statistiques des ressortissants.
// Pour régler la prévention des régressions fonctionnelles sur les endpoints API.

uses(RefreshDatabase::class);

// Ce code sert à valider la création d'un ressortissant et son statut initial.
// Il fonctionne en envoyant une requête POST à '/api/ressortissants' et en contrôlant la base de données.
// Dans le but de s'assurer que le statut 'en_attente' et la profession sont correctement enregistrés.
// Pour régler la fiabilité de l'inscription citoyenne.
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

// Ce code sert à tester la validation administrateur d'un dossier.
// Il fonctionne en simulant un appel PATCH sur '/api/ressortissants/{id}/valider'.
// Dans le but de vérifier que le statut de validation passe à 'valide'.
// Pour régler le contrôle qualité du workflow de modération.
test('un administrateur peut valider une fiche de recensement', function () {
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

// Ce code sert à vérifier la procédure de rejet d'une fiche avec motif.
// Il fonctionne avec un appel PATCH sur '/api/ressortissants/{id}/rejeter' accompagné d'un motif.
// Dans le but de s'assurer que le statut devient 'rejete' et le motif sauvegardé.
// Pour régler la traçabilité des motifs de refus.
test('un administrateur peut rejeter une fiche de recensement avec motif', function () {
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

// Ce code sert à vérifier l'exactitude des réponses de l'API des statistiques.
// Il fonctionne en peuplant 3 ressortissants de test puis en interrogeant '/api/stats/globales'.
// Dans le but de contrôler la justesse des calculs et totaux retournés.
// Pour régler la fiabilité des informations statistiques fournies aux décideurs.
test('les endpoints de statistiques renvoient les données d agrégation correctes', function () {
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
// Il fonctionne avec Storage::fake() et UploadedFile::fake() de Laravel.
// Dans le but de contrôler l'enregistrement physique et la mise à jour des chemins en BDD.
// Pour régler la validation du téléversement sécurisé de pièces justificatives.
test('peut créer un ressortissant de la diaspora avec pièces justificatives uploadées', function () {
    \Illuminate\Support\Facades\Storage::fake('public');

    $file = \Illuminate\Http\UploadedFile::fake()->create('cni.pdf', 500, 'application/pdf');

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

    \Illuminate\Support\Facades\Storage::disk('public')->assertExists($ressortissant->document_identite_path);
});
