<?php


declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

// Ce code sert à vérifier les informations envoyées avant de créer ou modifier un village.
// Il fonctionne avec les données reçues : le nom et la tribu de rattachement.
// Dans le but de refuser toute demande incomplète, en doublon, ou rattachée à une tribu inexistante.
// Pour régler le mélange des vérifications et de la logique métier dans un même fichier.
class VillageRequest extends FormRequest
{
    // Autorise tout le monde pour l'instant : la connexion obligatoire viendra plus tard.
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => [
                'required',
                'string',
                'max:255',

                // Le nom d'un village doit être unique dans sa tribu, 
                //sauf si c'est le village lui-même 
                //qu'on est en train de modifier.
                // On ignore le village en cours de modification. 
                Rule::unique('villages', 'nom')
                    ->where('tribu_id', $this->input('tribu_id'))
                    ->ignore($this->village?->id ?? $this->route('village')),
            ],
            // La tribu indiquée doit réellement exister dans notre base.
            'tribu_id' => ['required', 'integer', 'exists:tribus,id'],
        ];
    }

    // Nettoie les données avant de lancer les vérifications.
    protected function prepareForValidation(): void
    {
        if ($this->has('nom')) {
            $this->merge([
                // Supprime les espaces invisibles au début et à la fin,
                // sinon la vérification de doublon ne verrait pas que c'est le même nom.
                'nom' => trim((string) $this->input('nom')),
            ]);
        }
    }
}
