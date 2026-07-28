<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    protected $fillable = [
        'cod_reg',
        'nom_reg',
        'cod_dist',
        'annee',
    ];

    /**
     * Le district parent (lien par code ANStat).
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'cod_dist', 'code_district');
    }

    /**
     * Les départements rattachés à cette région (lien par code ANStat).
     */
    public function departements(): HasMany
    {
        return $this->hasMany(Departement::class, 'cod_reg', 'cod_reg');
    }
}
