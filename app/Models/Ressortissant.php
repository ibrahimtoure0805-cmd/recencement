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
        'code_suivi',
        'user_id',
        'nom',
        'prenom',
        'telephone',
        'sexe',
        'date_naissance',
        'lieu_naissance',
        'famille',
        'profession',
        'type_piece',
        'numero_piece',
        'document_identite_path',
        'justificatif_domicile_path',
        'consulat_rattachement',
        'contact_referent_nom',
        'contact_referent_telephone',
        'situation_matrimoniale',
        'niveau_etude',
        'statut_occupation',
        'statut_validation',
        'motif_rejet',
        'district_id',
        'region_id',
        'departement_id',
        'sous_prefecture_id',
        'canton_id',
        'tribu_id',
        'village_id',
        'village_nom',
        'pays_id',
        'pays',
        'ville',
        'quartier',
        'adresse',
    ];

    protected $casts = [
        'date_naissance' => 'date',
    ];

    protected $appends = [
        'document_identite_url',
        'justificatif_domicile_url',
    ];

    protected $attributes = [
        'statut_validation' => 'en_attente',
    ];

    /**
     * URL publique du document d'identité uploadé.
     */
    public function getDocumentIdentiteUrlAttribute(): ?string
    {
        return $this->document_identite_path
            ? asset('storage/' . $this->document_identite_path)
            : null;
    }

    /**
     * URL publique du justificatif de domicile uploadé.
     */
    public function getJustificatifDomicileUrlAttribute(): ?string
    {
        return $this->justificatif_domicile_path
            ? asset('storage/' . $this->justificatif_domicile_path)
            : null;
    }

    /**
     * Scope pour filtrer les fiches validées.
     */
    public function scopeValides($query)
    {
        return $query->where('statut_validation', 'valide');
    }

    /**
     * Scope pour filtrer les fiches en attente de modération.
     */
    public function scopeEnAttente($query)
    {
        return $query->where('statut_validation', 'en_attente');
    }

    /**
     * Scope pour filtrer les ressortissants résidant à l'étranger (Diaspora).
     */
    public function scopeDiaspora($query)
    {
        return $query->where('pays', '!=', 'Côte d\'Ivoire');
    }

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

