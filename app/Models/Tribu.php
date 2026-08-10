<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

// Ce code sert à représenter une tribu du référentiel coutumier en base de données.
// Il fonctionne avec le framework Eloquent ORM et les modèles Canton et Village.
// Dans le but de lier la tribu à son canton parent et aux villages enfants.
// Pour régler la modélisation de l'échelon coutumier intermédiaire.
class Tribu extends Model
{
   protected $fillable = [
        'nom',
        'canton_id',
    ];

    // Ce code sert à définir le canton parent de la tribu.
    // Il fonctionne avec la clé étrangère canton_id.
    // Dans le but de remonter la hiérarchie vers le canton d'appartenance.
    // Pour régler le rattachement de la tribu.
    public function canton(): BelongsTo
    {
        return $this->belongsTo(Canton::class);
    }

    // Ce code sert à récupérer l'ensemble des villages rattachés à la tribu.
    // Il fonctionne avec la relation HasMany vers le modèle Village.
    // Dans le but de lister les villages composant cette tribu.
    // Pour régler le maillage tribus-villages.
    public function villages(): HasMany
    {
        return $this->hasMany(Village::class);
    }
}
