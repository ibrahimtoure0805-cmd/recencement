<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    /**
     * Les tribus rattachées à ce canton.
     */
    public function tribus(): HasMany
    {
        return $this->hasMany(Tribu::class);
    }
}
