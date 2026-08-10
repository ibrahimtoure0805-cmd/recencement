<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

// Ce code sert à vérifier les informations envoyées avant d'enregistrer ou modifier un ressortissant.
// Il fonctionne avec les données du formulaire de recensement (identité et rattachements).
// Dans le but de bloquer les informations incorrectes (ex: village inexistant, mauvaise date).
class RessortissantRequest extends FormRequest
{
    // Autorise tout le monde pour l'instant : la connexion obligatoire viendra plus tard.
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code_suivi' => ['nullable', 'string', 'max:255'],
            // Informations d'identité OBLIGATOIRES (ou parfois soumises lors d'une mise à jour partielle)
            'nom' => ['sometimes', 'required', 'string', 'max:255'],
            'prenom' => ['sometimes', 'required', 'string', 'max:255'],
            'sexe' => ['sometimes', 'required', 'string', Rule::in(['M', 'F'])],

            // Adresse de résidence (pays obligatoire)
            'pays_id' => ['nullable', 'integer', 'exists:pays,id'],
            'pays' => ['sometimes', 'required', 'string', 'max:255'],

            // Informations d'identité FACULTATIVES (NULLABLE)
            'telephone' => ['nullable', 'string', 'max:255'],
            'date_naissance' => ['nullable', 'date', 'before_or_equal:today'],
            'lieu_naissance' => ['nullable', 'string', 'max:255'],
            'famille' => ['nullable', 'string', 'max:255'],
            'profession' => ['nullable', 'string', 'max:255'],

            // Pièces justificatives et Identité (NULLABLE)
            'type_piece' => ['nullable', 'string', 'max:255'],
            'numero_piece' => ['nullable', 'string', 'max:255'],
            'document_identite' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'justificatif_domicile' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],

            // Rattachement Diaspora & Référent (NULLABLE)
            'consulat_rattachement' => ['nullable', 'string', 'max:255'],
            'contact_referent_nom' => ['nullable', 'string', 'max:255'],
            'contact_referent_telephone' => ['nullable', 'string', 'max:255'],

            // Informations sociodémographiques (NULLABLE)
            'situation_matrimoniale' => ['nullable', 'string', Rule::in(['celibataire', 'marie', 'divorce', 'veuf'])],
            'niveau_etude' => ['nullable', 'string', Rule::in(['aucun', 'primaire', 'secondaire', 'superieur'])],
            'statut_occupation' => ['nullable', 'string', Rule::in(['chef_menage', 'epoux_epouse', 'enfant', 'autre_membre', 'membre_foyer', 'resident_temporaire', 'autre'])],
            'village_nom' => ['nullable', 'string', 'max:255'],

            // Modération & Gouvernance (NULLABLE / ENUM)
            'statut_validation' => ['nullable', 'string', Rule::in(['en_attente', 'valide', 'rejete'])],
            'motif_rejet' => ['nullable', 'string', 'max:500'],

            // Rattachement administratif (FACULTATIF / NULLABLE)
            'district_id' => ['nullable', 'integer', 'exists:districts,id'],
            'region_id' => ['nullable', 'integer', 'exists:regions,id'],
            'departement_id' => ['nullable', 'integer', 'exists:departements,id'],
            'sous_prefecture_id' => ['nullable', 'integer', 'exists:sous_prefectures,id'],

            // Rattachement coutumier (FACULTATIF / NULLABLE)
            'canton_id' => ['nullable', 'integer', 'exists:cantons,id'],
            'tribu_id' => ['nullable', 'integer', 'exists:tribus,id'],
            'village_id' => ['nullable', 'integer', 'exists:villages,id'],

            // Adresse de résidence locale / détaillée (FACULTATIVE / NULLABLE)
            'ville' => ['nullable', 'string', 'max:255'],
            'quartier' => ['nullable', 'string', 'max:255'],
            'adresse' => ['nullable', 'string', 'max:255'],

            // Le lien vers le compte utilisateur est facultatif et unique
            'user_id' => [
                'nullable',
                'integer',
                'exists:users,id',
                Rule::unique('ressortissants', 'user_id')->ignore($this->route('ressortissant')),
            ],
        ];
    }

    // Nettoie les données avant la validation (convertit les chaînes vides des clés étrangères en null, etc.).
    protected function prepareForValidation(): void
    {
        // Nettoyage des clés étrangères si reçues sous forme de chaîne vide ""
        foreach (['user_id', 'district_id', 'region_id', 'departement_id', 'sous_prefecture_id', 'canton_id', 'tribu_id', 'village_id', 'pays_id'] as $fk) {
            if ($this->has($fk) && $this->input($fk) === '') {
                $this->merge([$fk => null]);
            }
        }

        foreach (['nom', 'prenom', 'telephone', 'lieu_naissance', 'famille', 'profession', 'type_piece', 'numero_piece', 'consulat_rattachement', 'contact_referent_nom', 'contact_referent_telephone', 'situation_matrimoniale', 'niveau_etude', 'statut_occupation', 'pays', 'ville', 'quartier', 'adresse', 'motif_rejet'] as $champ) {
            if ($this->has($champ) && $this->input($champ) !== null) {
                $this->merge([$champ => trim((string) $this->input($champ))]);
            }
        }

        if ($this->has('sexe') && $this->input('sexe') !== null) {
            $this->merge(['sexe' => strtoupper(trim((string) $this->input('sexe')))]);
        }

        // Nettoyage des champs de fichiers s'ils ne contiennent pas un fichier téléversé valide
        if ($this->has('document_identite') && ! $this->file('document_identite')) {
            $this->offsetUnset('document_identite');
        }
        if ($this->has('justificatif_domicile') && ! $this->file('justificatif_domicile')) {
            $this->offsetUnset('justificatif_domicile');
        }
    }
}
