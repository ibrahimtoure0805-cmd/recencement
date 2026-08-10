<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
// Ce code sert à générer des données de test factices pour le modèle User.
// Il fonctionne avec le composant Factory de Laravel Eloquent et la bibliothèque Faker.
// Dans le but de pouvoir créer facilement des comptes utilisateurs en environnement de dev et de test.
// Pour régler la génération automatique de jeux de données d'utilisateurs.
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    // Ce code sert à définir l'état par défaut d'un utilisateur généré.
    // Il fonctionne avec le générateur de données aléatoires fake().
    // Dans le but de produire un tableau d'attributs utilisateur (nom, email, mot de passe hashé, jeton).
    // Pour régler la création d'instances factices prêtes à insérer.
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    // Ce code sert à basculer un compte utilisateur en état non vérifié.
    // Il fonctionne en passant la clé email_verified_at à null.
    // Dans le but d'éprouver le comportement du système pour les comptes en attente de confirmation.
    // Pour régler le test des accès restreints aux utilisateurs non vérifiés.
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
