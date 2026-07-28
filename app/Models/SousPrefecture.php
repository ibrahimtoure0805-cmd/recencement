<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SousPrefecture extends Model
{
    protected $fillable = [
    'anstat_id',
    'cod_sp',
    'nom_sp',
    'cod_dep',
    'annee',
];

    /**
     * Le département parent (lien par code ANStat).
     */
    public function departement(): BelongsTo
    {
        return $this->belongsTo(Departement::class, 'cod_dep', 'cod_dep');
    }

    /**
     * Les cantons coutumiers rattachés à cette sous-préfecture.
     */
    public function cantons(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Canton::class);
    }

    /**
     * Les ressortissants rattachés à cette sous-préfecture.
     */
    public function ressortissants(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Ressortissant::class);
    }
}
