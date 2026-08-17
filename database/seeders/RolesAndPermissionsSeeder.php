<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Exécuter les seeders de rôles et de permissions.
     */
    public function run(): void
    {
        // Reinitialiser le cache des rôles et permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $guards = ['sanctum', 'web'];

        // 1. Créer toutes les permissions
        foreach (PermissionEnum::cases() as $permissionEnum) {
            foreach ($guards as $guard) {
                Permission::firstOrCreate([
                    'name' => $permissionEnum->value,
                    'guard_name' => $guard,
                ]);
            }
        }

        // 2. Créer les rôles et leur attribuer les permissions
        foreach (RoleEnum::cases() as $roleEnum) {
            foreach ($guards as $guard) {
                $role = Role::firstOrCreate([
                    'name' => $roleEnum->value,
                    'guard_name' => $guard,
                ]);

                // Synchronisation des permissions pour le rôle (converties en strings)
                $permissionStrings = array_map(
                    fn (PermissionEnum $p) => $p->value,
                    $roleEnum->permissions()
                );

                $role->syncPermissions($permissionStrings);
            }
        }

        // 3. Créer le compte Super Administrateur initial s'il n'existe pas
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@anstat.ci'],
            [
                'name' => 'Super Administrateur ANStat',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $superAdmin->assignRole(RoleEnum::SUPER_ADMIN->value);
    }
}
