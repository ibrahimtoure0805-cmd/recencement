<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tribu extends Model
{
   protected $fillable = [
        'nom',
        'canton_id',
    ];
    
     // canton parent
     
    public function canton(): BelongsTo
    {
        return $this->belongsTo(Canton::class);
    }

    //Les villages rattachés à cette tribu

    public function villages(): HasMany
    {
        return $this->hasMany(Village::class);
    }
}
