<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\RessortissantObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy(RessortissantObserver::class)]
class Ressortissant extends Model
{
    protected $fillable = [
        'user_id',
        'nom',
        'prenom',
        'telephone',
        'sexe',
        'date_naissance',
        'lieu_naissance',
        'famille',
        'district_id',
        'region_id',
        'departement_id',
        'sous_prefecture_id',
        'canton_id',
        'tribu_id',
        'village_id',
        'pays_id',
        'pays',
        'ville',
        'quartier',
        'adresse',
    ];

    protected $casts = [
        'date_naissance' => 'date',
    ];

    /**
     * Le compte utilisateur associé (optionnel).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Le district administratif.
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    /**
     * La région administrative.
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * Le département administratif.
     */
    public function departement(): BelongsTo
    {
        return $this->belongsTo(Departement::class);
    }

    /**
     * La sous-préfecture de rattachement administratif.
     */
    public function sousPrefecture(): BelongsTo
    {
        return $this->belongsTo(SousPrefecture::class);
    }

    /**
     * Le canton coutumier.
     */
    public function canton(): BelongsTo
    {
        return $this->belongsTo(Canton::class);
    }

    /**
     * La tribu coutumière.
     */
    public function tribu(): BelongsTo
    {
        return $this->belongsTo(Tribu::class);
    }

    /**
     * Le pays de résidence (référentiel).
     */
    public function paysRelation(): BelongsTo
    {
        return $this->belongsTo(Pays::class, 'pays_id');
    }

    /**
     * Le village d'origine coutumière (optionnel).
     */
    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }
}

