<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

// Ce code sert à représenter un pays du référentiel mondial en base de données.
// Il fonctionne avec Eloquent ORM et la table 'pays'.
// Dans le but de stocker le nom, le code ISO et le drapeau 'is_default' (Côte d'Ivoire).
// Pour régler la normalisation de la liste des pays de résidence.
class Pays extends Model
{
    protected $table = 'pays';

    protected $fillable = [
        'nom',
        'code_iso',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    // Ce code sert à lister tous les ressortissants résidant dans ce pays.
    // Il fonctionne avec la clé étrangère pays_id de la table ressortissants.
    // Dans le but d'obtenir les ressortissants vivant dans le pays spécifié.
    // Pour régler le regroupement des ressortissants par pays d'accueil.
    public function ressortissants(): HasMany
    {
        return $this->hasMany(Ressortissant::class, 'pays_id');
    }
}
