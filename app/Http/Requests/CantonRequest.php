<?php


declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

// Ce code sert à vérifier les informations envoyées avant de créer ou modifier un canton.
// Il fonctionne avec les données reçues dans la demande (le nom du canton).
// Dans le but de refuser toute demande incomplète, mal remplie ou en doublon.
// Pour régler le mélange des vérifications et de la logique métier dans un même fichier.
class CantonRequest extends FormRequest
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
                Rule::unique('cantons', 'nom')->ignore($this->canton?->id ?? $this->route('canton')),
            ],
            'sous_prefecture_id' => ['nullable', 'integer', 'exists:sous_prefectures,id'],
        ];
    }

    // Nettoie les données avant de lancer les vérifications.
    protected function prepareForValidation(): void
    {
        if ($this->has('nom')) {
            $this->merge([
                // Supprime les espaces invisibles au début et à la fin (" Akan " devient "Akan"),
                // sinon la vérification de doublon ne verrait pas que c'est le même nom.
                'nom' => trim((string) $this->input('nom')),
            ]);
        }
    }
}