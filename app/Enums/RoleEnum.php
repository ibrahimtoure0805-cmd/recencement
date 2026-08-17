<?php

declare(strict_types=1);

namespace App\Enums;

enum RoleEnum: string
{
    case SUPER_ADMIN = 'super_admin';
    case RESSORTISSANT = 'ressortissant';

    /**
     * Obtenir le nom lisible du rôle en français.
     */
    public function label(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'Super Administrateur Système',
            self::RESSORTISSANT => 'Ressortissant Citoyen',
        };
    }

    /**
     * Obtenir la liste des permissions attribuées par défaut à ce rôle.
     *
     * @return array<PermissionEnum>
     */
    public function permissions(): array
    {
        return match ($this) {
            self::SUPER_ADMIN => PermissionEnum::cases(),
            self::RESSORTISSANT => [
                PermissionEnum::RESSORTISSANT_CREATE,
                PermissionEnum::RESSORTISSANT_VIEW,
                PermissionEnum::RESSORTISSANT_UPDATE,
                PermissionEnum::COUTUMIER_VIEW,
                PermissionEnum::ANSTAT_VIEW,
            ],
        };
    }
}
