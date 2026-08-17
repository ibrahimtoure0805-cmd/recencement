<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

// Ce code sert à représenter un canton du système coutumier en base de données.
// Il fonctionne avec Eloquent ORM et les modèles SousPrefecture et Tribu.
// Dans le but de définir les colonnes modifiables et les relations parente et enfant d'un canton.
// Pour régler la modélisation du niveau supérieur de la hiérarchie coutumière.
class Canton extends Model
{
    protected $fillable = [
        'nom',
        'sous_prefecture_id',
        'is_defaut',
    ];

    protected $casts = [
        'is_defaut' => 'boolean',
    ];

    // Ce code sert à définir la sous-préfecture administrative parente du canton.
    // Il fonctionne avec la clé étrangère sous_prefecture_id.
    // Dans le but d'établir la passerelle entre l'organisation administrative et coutumière.
    // Pour régler le besoin de pontage entre ANStat et la chefferie coutumière.
    public function sousPrefecture(): BelongsTo
    {
        return $this->belongsTo(SousPrefecture::class);
    }

    // Ce code sert à restituer la liste des tribus membres du canton.
    // Il fonctionne avec la relation un-à-plusieurs de la table tribus.
    // Dans le but d'accéder aux tribus d'un canton donné.
    // Pour régler l'exploration descendante de la structure coutumière.
    public function tribus(): HasMany
    {
        return $this->hasMany(Tribu::class);
    }
}
