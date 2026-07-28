<?php


declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

// Ce code sert à vérifier les informations envoyées avant de créer ou modifier une tribu.
// Il fonctionne avec les données reçues : le nom et le canton de rattachement.
// Dans le but de refuser toute demande incomplète, en doublon, ou rattachée à un canton inexistant.
// Pour régler le mélange des vérifications et de la logique métier dans un même fichier.
class TribuRequest extends FormRequest
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

                // Refuse un nom déjà pris DANS LE MÊME canton : deux tribus du même nom
                // peuvent exister dans deux cantons différents, pas dans le même.
                // En modification, on ignore la tribu en cours.
                Rule::unique('tribus', 'nom')
                    ->where('canton_id', $this->input('canton_id'))
                    ->ignore($this->tribu?->id ?? $this->route('tribu')),
            ],
            // Le canton indiqué doit réellement exister dans notre base.
            'canton_id' => ['required', 'integer', 'exists:cantons,id'],
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