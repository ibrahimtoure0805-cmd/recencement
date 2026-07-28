<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    /**
     * Les ressortissants résidant dans ce pays.
     */
    public function ressortissants(): HasMany
    {
        return $this->hasMany(Ressortissant::class, 'pays_id');
    }
}
