# Note Explicative : Le Lien entre Sous-Préfecture et Canton

Ce document clarifie la relation et le rôle de pivot entre la **Sous-Préfecture** (référentiel administratif officiel ANStat) et le **Canton** (référentiel coutumier traditionnel) au sein du projet de recensement.

---

## 1. Origine du Doute : La Juxtaposition de Deux Mondes

Dans la modélisation de l'application de recensement, deux découpages territoriaux coexistent :

```
    RÉFÉRENTIEL ADMINISTRATIF (ANStat / État)
    ========================================
                   District
                      │
                   Région
                      │
                 Département
                      │
               Sous-Préfecture  ◄── (Pivot / Point d'ancrage)
                      │
                      │ 
    RÉFÉRENTIEL COUTUMIER (Tradition / Histoire)
    ===========================================
                   Canton
                      │
                    Tribu
                      │
                   Village
```

* **Le système Administratif (Officiel / État)** : Il est régi par le découpage administratif du ministère de l'Intérieur (`District` ➔ `Région` ➔ `Département` ➔ `Sous-Préfecture`).
* **Le système Coutumier (Traditionnel / Socioculturel)** : Il repose sur l'organisation historique et socioculturelle des peuples (`Canton` ➔ `Tribu` ➔ `Village`).

---

## 2. Le Pivot de Jonction : Pourquoi lier Sous-Préfecture et Canton ?

### Le rôle de la Sous-Préfecture comme "Pont"
Pour éviter que l'arbre coutumier ne flotte de manière totalement déconnectée dans la base de données, la **Sous-Préfecture** a été choisie comme **clé d'ancrage (pivot)** entre l'État et la tradition.

* En Côte d'Ivoire, un **Canton traditionnel** s'inscrit territorialement à l'intérieur d'une (ou parfois plusieurs) **Sous-Préfecture(s)** administrative(s).
* En rattachant le Canton à la Sous-Préfecture via une clé étrangère (`sous_prefecture_id`), la hiérarchie coutumière vient s'imbriquer naturellement sous l'échelon administratif le plus fin de l'État.

---

## 3. Implémentation Technologique (Laravel & Base de données)

### Modèle Eloquent (`App\Models\Canton`)
Dans la base de données, la table `cantons` contient la clé étrangère `sous_prefecture_id` :

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Canton extends Model
{
    protected $fillable = ['nom', 'sous_prefecture_id'];

    /**
     * Relation avec la sous-préfecture administrative parente (ANStat).
     */
    public function sousPrefecture(): BelongsTo
    {
        return $this->belongsTo(SousPrefecture::class);
    }

    /**
     * Relation avec les tribus coutumières rattachées à ce canton.
     */
    public function tribus(): HasMany
    {
        return $this->hasMany(Tribu::class);
    }
}
```

### Parcours dans le Formulaire de Recensement
Dans l'interface utilisateur, cette relation permet d'offrir une navigation en cascade fluide :
1. L'utilisateur sélectionne d'abord sa **Sous-Préfecture** d'origine administrative.
2. Le système filtre et affiche uniquement les **Cantons coutumiers** associés à cette Sous-Préfecture.
3. Le choix du Canton filtre les **Tribus**, qui filtrent ensuite les **Villages**.

---

## 4. Synthèse

| Notion | Découpage Administratif (ANStat) | Découpage Coutumier |
| :--- | :--- | :--- |
| **Nature** | Étatique, officielle, déconcentrée | Historique, socioculturelle, traditionnelle |
| **Maillons extrême** | Sous-Préfecture (maillon fin) | Canton (maillon haut) |
| **Rôle dans le projet** | Découpage ANStat officiel | Arbre d'origine des ressortissants |
| **Jonction** | La **Sous-Préfecture** contient les Cantons | Le **Canton** pointe vers sa **Sous-Préfecture** (`sous_prefecture_id`) |
