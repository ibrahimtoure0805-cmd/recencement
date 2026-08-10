<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\RessortissantObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Ce code sert à représenter la fiche individuelle d'un ressortissant en base de données.
// Il fonctionne avec le composant Eloquent ORM de Laravel et l'observateur RessortissantObserver.
// Dans le but de centraliser les attributs, accesseurs d'URL de pièces jointes, scopes et relations géographiques/coutumières.
// Pour régler la modélisation complète des citoyens recensés (locaux et diaspora).
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

    // Ce code sert à générer l'URL publique de téléchargement de la pièce d'identité.
    // Il fonctionne avec l'attribut document_identite_path et le helper asset().
    // Dans le but de renvoyer l'URL complète ou null si aucun document n'est fourni.
    // Pour régler l'accès direct aux fichiers médias hébergés.
    public function getDocumentIdentiteUrlAttribute(): ?string
    {
        return $this->document_identite_path
            ? asset('storage/' . $this->document_identite_path)
            : null;
    }

    // Ce code sert à générer l'URL publique de téléchargement du justificatif de domicile.
    // Il fonctionne avec l'attribut justificatif_domicile_path et la fonction helper asset().
    // Dans le but de fournir l'adresse web du justificatif pour la modération.
    // Pour régler la prévisualisation fluide des justificatifs dans l'interface admin.
    public function getJustificatifDomicileUrlAttribute(): ?string
    {
        return $this->justificatif_domicile_path
            ? asset('storage/' . $this->justificatif_domicile_path)
            : null;
    }

    // Ce code sert à filtrer les ressortissants dont le dossier a été validé.
    // Il fonctionne avec la colonne statut_validation égale à 'valide'.
    // Dans le but de restreindre les requêtes aux fiches certifiées.
    // Pour régler l'isolation des données validées pour les statistiques officielles.
    public function scopeValides($query)
    {
        return $query->where('statut_validation', 'valide');
    }

    // Ce code sert à isoler les dossiers de recensement en attente de vérification.
    // Il fonctionne avec la colonne statut_validation avec la valeur 'en_attente'.
    // Dans le but d'alimenter la file de travail des modérateurs.
    // Pour régler le traitement prioritaire des demandes non validées.
    public function scopeEnAttente($query)
    {
        return $query->where('statut_validation', 'en_attente');
    }

    // Ce code sert à filtrer les citoyens résidant hors de Côte d'Ivoire.
    // Il fonctionne avec la valeur de la colonne pays différente de 'Côte d'Ivoire'.
    // Dans le but d'extraire les statistiques spécifiques à la diaspora.
    // Pour régler la catégorisation géographique des résidents à l'étranger.
    public function scopeDiaspora($query)
    {
        return $query->where('pays', '!=', 'Côte d\'Ivoire');
    }

    // Ce code sert à définir la relation vers le compte utilisateur créateur.
    // Il me fonctionne avec la clé étrangère user_id.
    // Dans le but de relier la fiche de recensement au compte citoyen connecté.
    // Pour régler le rattachement de la fiche au compte d'accès.
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Ce code sert à associer la fiche au district administratif correspondant.
    // Il fonctionne avec la clé étrangère district_id.
    // Dans le but de lier la donnée géographique du district.
    // Pour régler l'agrégation territoriale par district.
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    // Ce code sert à lier le ressortissant à sa région administrative.
    // Il fonctionne avec la clé étrangère region_id.
    // Dans le but d'accéder aux métadonnées de la région.
    // Pour régler la classification par région.
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    // Ce code sert à associer le ressortissant à son département administratif.
    // Il fonctionne avec la clé étrangère departement_id.
    // Dans le but de mapper la fiche au département d'origine.
    // Pour régler la hiérarchie territoriale au niveau départemental.
    public function departement(): BelongsTo
    {
        return $this->belongsTo(Departement::class);
    }

    // Ce code sert à relier la fiche à la sous-préfecture de rattachement.
    // Il fonctionne avec la clé étrangère sous_prefecture_id.
    // Dans le but d'obtenir l'entité sous-préfectorale ANStat.
    // Pour régler le maillage administratif fin.
    public function sousPrefecture(): BelongsTo
    {
        return $this->belongsTo(SousPrefecture::class);
    }

    // Ce code sert à rattacher le ressortissant à son canton d'origine coutumier.
    // Il fonctionne avec la clé étrangère canton_id.
    // Dans le but de faire le lien avec le référentiel cantonais.
    // Pour régler le suivi de l'appartenance au canton.
    public function canton(): BelongsTo
    {
        return $this->belongsTo(Canton::class);
    }

    // Ce code sert à associer la fiche à la tribu coutumière.
    // Il fonctionne avec la clé étrangère tribu_id.
    // Dans le but de relier le ressortissant à sa tribu d'origine.
    // Pour régler la préservation du patrimoine coutumier.
    public function tribu(): BelongsTo
    {
        return $this->belongsTo(Tribu::class);
    }

    // Ce code sert à établir la relation vers le pays de résidence du référentiel.
    // Il fonctionne avec la clé étrangère pays_id.
    // Dans le but de lier l'objet Pays.
    // Pour régler le lien structuré vers le pays d'accueil.
    public function paysRelation(): BelongsTo
    {
        return $this->belongsTo(Pays::class, 'pays_id');
    }

    // Ce code sert à relier le ressortissant à son village d'origine.
    // Il fonctionne avec la clé étrangère village_id.
    // Dans le but d'associer le village de naissance/coutume.
    // Pour régler l'identification villageoise des ressortissants.
    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }
}

