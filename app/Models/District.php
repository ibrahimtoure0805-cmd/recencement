<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{
    protected $fillable = [
        'code_district',
        'nom_district',
        'annee',
    ];

    /**
     * Les régions rattachées à ce district (lien par code ANStat).
     */
    public function regions(): HasMany
    {
        return $this->hasMany(Region::class, 'cod_dist', 'code_district');
    }
}
