<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Departement extends Model
{
    protected $fillable = [
        'cod_dep',
        'nom_dep',
        'cod_reg',
        'annee',
    ];

    /**
     * La région parente (lien par code ANStat).
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'cod_reg', 'cod_reg');
    }

    /**
     * Les sous-préfectures rattachées à ce département (lien par code ANStat).
     */
    public function sousPrefectures(): HasMany
    {
        return $this->hasMany(SousPrefecture::class, 'cod_dep', 'cod_dep');
    }
}
