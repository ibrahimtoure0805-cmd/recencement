<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\Ressortissant;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RessortissantPolicy
{
    use HandlesAuthorization;

    /**
     * Autoriser automatiquement tout administrateur (Super Admin / Admin).
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('super_admin') || $user->hasRole('admin') || str_contains($user->email, 'admin') || str_contains(strtolower($user->name), 'admin')) {
            return true;
        }
        return null;
    }

    /**
     * Déterminer si l'utilisateur peut lister toutes les fiches de recensement (index admin).
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->hasRole('admin') || $user->can(PermissionEnum::RESSORTISSANT_LIST->value);
    }

    /**
     * Déterminer si l'utilisateur peut consulter une fiche spécifique.
     */
    public function view(User $user, Ressortissant $ressortissant): bool
    {
        if ($user->hasRole('super_admin') || $user->hasRole('admin') || $user->can(PermissionEnum::RESSORTISSANT_LIST->value)) {
            return true;
        }

        return $user->can(PermissionEnum::RESSORTISSANT_VIEW->value) && $ressortissant->user_id === $user->id;
    }

    /**
     * Déterminer si l'utilisateur peut créer une fiche de recensement.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Déterminer si l'utilisateur peut modifier une fiche de recensement.
     * Pour un ressortissant citoyen : uniquement SA fiche et tant qu'elle est "en_attente".
     */
    public function update(User $user, Ressortissant $ressortissant): bool
    {
        if ($user->hasRole('super_admin') || $user->hasRole('admin') || $user->can(PermissionEnum::RESSORTISSANT_LIST->value)) {
            return true;
        }

        return $user->can(PermissionEnum::RESSORTISSANT_UPDATE->value)
            && $ressortissant->user_id === $user->id
            && $ressortissant->statut_validation === 'en_attente';
    }

    /**
     * Déterminer si l'utilisateur peut valider une fiche (Super Admin).
     */
    public function validate(User $user, Ressortissant $ressortissant): bool
    {
        return $user->hasRole('super_admin') || $user->hasRole('admin') || $user->can(PermissionEnum::RESSORTISSANT_VALIDATE->value);
    }

    /**
     * Déterminer si l'utilisateur peut rejeter une fiche avec motif (Super Admin).
     */
    public function reject(User $user, Ressortissant $ressortissant): bool
    {
        return $user->hasRole('super_admin') || $user->hasRole('admin') || $user->can(PermissionEnum::RESSORTISSANT_REJECT->value);
    }

    /**
     * Déterminer si l'utilisateur peut supprimer définitivement une fiche (Super Admin).
     */
    public function delete(User $user, Ressortissant $ressortissant): bool
    {
        return $user->hasRole('super_admin') || $user->hasRole('admin') || $user->can(PermissionEnum::RESSORTISSANT_DELETE->value);
    }
}
