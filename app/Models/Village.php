<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Ce code sert à représenter un village coutumier en base de données.
// Il fonctionne avec Eloquent ORM et les modèles Tribu et Ressortissant.
// Dans le but de rattacher le village à sa tribu et de recenser ses ressortissants d'origine.
// Pour régler la modélisation du maillage coutumier de base.
class Village extends Model
{
    protected $fillable = [
        'nom',
        'tribu_id',
    ];

    // Ce code sert à définir la tribu parente du village.
    // Il fonctionne avec la clé étrangère tribu_id.
    // Dans le but d'obtenir l'entité Tribu à laquelle le village appartient.
    // Pour régler le lien hiérarchique avec la tribu.
    public function tribu(): BelongsTo
    {
        return $this->belongsTo(Tribu::class);
    }

    // Ce code sert à lister les ressortissants originaires de ce village.
    // Il fonctionne avec la relation HasMany de la table ressortissants.
    // Dans le but de récupérer la communauté des ressortissants rattachés au village.
    // Pour régler le suivi démographique par village d'origine.
    public function ressortissants(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Ressortissant::class);
    }
}
