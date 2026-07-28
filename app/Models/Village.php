<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Village extends Model
{
    protected $fillable = [
        'nom',
        'tribu_id',
    ];

    // La tribu parente (Structure coutumière)
    public function tribu(): BelongsTo
    {
        return $this->belongsTo(Tribu::class);
    }

    /**
     * Les ressortissants originaires de ce village coutumier.
     */
    public function ressortissants(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Ressortissant::class);
    }
}
