# Documentation : Importation des Cantons et Liaison avec les Sous-Préfectures

Ce document explique où sont stockés les cantons récupérés (depuis Wikimedia/Wikidata), comment ils sont importés dans la base de données, et détaille l'ensemble des preuves de code établissant la liaison entre les **sous-préfectures (structure administrative ANStat)** et les **cantons (structure coutumière)**.

---

## 1. Récupération et Importation des Cantons

### A. Emplacement du fichier de données
Les données brutes des cantons récupérés sur Wikimedia/Wikidata sont stockées dans le fichier JSON suivant :
* **Chemin** : [`database/data/cantons.json`](file:///c:/Users/tieco/Desktop/herd%20Laravel/recensement/database/data/cantons.json)

Ce fichier liste les cantons coutumiers avec leur nom et le nom de la sous-préfecture administrative associée :
```json
[
  {
    "nom": "Ahaly",
    "sous_prefecture_nom": "BROBO"
  },
  {
    "nom": "Faafouè",
    "sous_prefecture_nom": "BONOUA"
  },
  {
    "nom": "Gblo",
    "sous_prefecture_nom": "DIABO"
  }
]
```

### B. Mécanismes d'importation dans la base de données
Deux mécanismes permettent d'importer ces cantons dans la base de données :

1. **La commande Console Artisan dédiée** :
   * **Commande** : `php artisan cantons:import`
   * **Fichier source** : [`app/Console/Commands/ImportCantons.php`](file:///c:/Users/tieco/Desktop/herd%20Laravel/recensement/app/Console/Commands/ImportCantons.php)
   * **Fonctionnement** : Il lit le fichier `cantons.json`, recherche la sous-préfecture correspondante dans la table `sous_prefectures` d'après son nom (`nom_sp`), puis insère ou met à jour le canton avec la clé étrangère `sous_prefecture_id`.

2. **Le Seeder Laravel** :
   * **Fichier source** : [`database/seeders/CantonSeeder.php`](file:///c:/Users/tieco/Desktop/herd%20Laravel/recensement/database/seeders/CantonSeeder.php)
   * **Appel** : Il est exécuté lors du seeder principal via `php artisan db:seed` dans [`database/seeders/DatabaseSeeder.php`](file:///c:/Users/tieco/Desktop/herd%20Laravel/recensement/database/seeders/DatabaseSeeder.php#L20).

---

## 2. Preuves de Code : Liaison entre Sous-Préfecture et Canton

Le lien entre les sous-préfectures et les cantons est implémenté et prouvé à 5 niveaux distincts dans l'application :

### 1. La Migration Database (Clé Étrangère SQL)
* **Fichier** : [`database/migrations/2026_07_06_100341_create_cantons_table.php`](file:///c:/Users/tieco/Desktop/herd%20Laravel/recensement/database/migrations/2026_07_06_100341_create_cantons_table.php#L17)

```php
Schema::create('cantons', function (Blueprint $table) {
    $table->id();
    $table->string('nom');
    // Clé étrangère vers la table des sous-préfectures :
    $table->foreignId('sous_prefecture_id')->nullable()->constrained('sous_prefectures')->nullOnDelete();
    $table->timestamps();
});
```
> **Preuve** : La colonne `sous_prefecture_id` définit la contrainte d'intégrité référentielle en base de données.

---

### 2. Le Modèle Eloquent `Canton` (Relation `BelongsTo`)
* **Fichier** : [`app/Models/Canton.php`](file:///c:/Users/tieco/Desktop/herd%20Laravel/recensement/app/Models/Canton.php#L12-L23)

```php
class Canton extends Model
{
    protected $fillable = [
        'nom',
        'sous_prefecture_id',
    ];

    /**
     * La sous-préfecture administrative parente (Lien ANStat -> Coutumier).
     */
    public function sousPrefecture(): BelongsTo
    {
        return $this->belongsTo(SousPrefecture::class);
    }
}
```
> **Preuve** : La méthode `sousPrefecture()` permet d'accéder directement à l'objet `SousPrefecture` depuis une instance de `Canton` (`$canton->sousPrefecture`).

---

### 3. Le Modèle Eloquent `SousPrefecture` (Relation `HasMany`)
* **Fichier** : [`app/Models/SousPrefecture.php`](file:///c:/Users/tieco/Desktop/herd%20Laravel/recensement/app/Models/SousPrefecture.php#L31-L34)

```php
/**
 * Les cantons coutumiers rattachés à cette sous-préfecture.
 */
public function cantons(): \Illuminate\Database\Eloquent\Relations\HasMany
{
    return $this->hasMany(Canton::class);
}
```
> **Preuve** : La relation `cantons()` permet de récupérer l'ensemble des cantons rattachés à une sous-préfecture donnée (`$sousPrefecture->cantons`).

---

### 4. Le code d'Importation / Mapping lors du Peupleur
* **Fichier** : [`app/Console/Commands/ImportCantons.php`](file:///c:/Users/tieco/Desktop/herd%20Laravel/recensement/app/Console/Commands/ImportCantons.php#L40-L53)

```php
foreach ($cantonsData as $item) {
    $nom = trim((string) $item['nom']);
    $spNom = trim((string) ($item['sous_prefecture_nom'] ?? ''));

    $sp = null;
    if ($spNom !== '') {
        $sp = SousPrefecture::where('nom_sp', 'LIKE', "%{$spNom}%")->first();
    }

    Canton::updateOrCreate(
        ['nom' => $nom],
        ['sous_prefecture_id' => $sp?->id] // Liaison dynamique des identifiants
    );
}
```
> **Preuve** : Lors de l'exécution de l'import, le code recherche la sous-préfecture correspondante et effectue la liaison via son identifiant (`$sp->id`).

---

### 5. L'Observer du Ressortissant (Remontée Automatique)
* **Fichier** : [`app/Observers/RessortissantObserver.php`](file:///c:/Users/tieco/Desktop/herd%20Laravel/recensement/app/Observers/RessortissantObserver.php#L62-L63)

```php
$canton = $tribu?->canton;
$sp     = $canton?->sousPrefecture; // Remontée du Canton vers la Sous-Préfecture
```
> **Preuve** : Lors de l'enregistrement automatique des informations de rattachement d'un ressortissant, l'application utilise la relation entre le canton et la sous-préfecture pour déduire la localisation administrative officielle.
