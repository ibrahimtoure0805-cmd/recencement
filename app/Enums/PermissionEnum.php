<?php

declare(strict_types=1);

namespace App\Enums;

enum PermissionEnum: string
{
    // Ressortissants
    case RESSORTISSANT_LIST = 'ressortissant:list';
    case RESSORTISSANT_VIEW = 'ressortissant:view';
    case RESSORTISSANT_CREATE = 'ressortissant:create';
    case RESSORTISSANT_UPDATE = 'ressortissant:update';
    case RESSORTISSANT_VALIDATE = 'ressortissant:validate';
    case RESSORTISSANT_REJECT = 'ressortissant:reject';
    case RESSORTISSANT_DELETE = 'ressortissant:delete';

    // Structure Coutumière (Cantons, Tribus, Villages)
    case COUTUMIER_VIEW = 'coutumier:view';
    case COUTUMIER_MANAGE = 'coutumier:manage';

    // Structure Administrative ANStat
    case ANSTAT_VIEW = 'anstat:view';
    case ANSTAT_MANAGE = 'anstat:manage';

    // Statistiques & Administration Système
    case STATS_VIEW = 'stats:view';
    case USER_MANAGE = 'user:manage';
    case ROLE_MANAGE = 'role:manage';

    /**
     * Obtenir le libellé explicatif en français.
     */
    public function label(): string
    {
        return match ($this) {
            self::RESSORTISSANT_LIST => 'Consulter la liste de toutes les fiches de recensement',
            self::RESSORTISSANT_VIEW => 'Consulter le détail d\'une fiche de recensement',
            self::RESSORTISSANT_CREATE => 'Soumettre une nouvelle fiche de recensement',
            self::RESSORTISSANT_UPDATE => 'Modifier sa fiche de recensement (en attente)',
            self::RESSORTISSANT_VALIDATE => 'Valider une fiche de recensement',
            self::RESSORTISSANT_REJECT => 'Rejeter une fiche de recensement avec motif',
            self::RESSORTISSANT_DELETE => 'Supprimer définitivement une fiche de recensement',
            self::COUTUMIER_VIEW => 'Consulter la structure coutumière (cantons, tribus, villages)',
            self::COUTUMIER_MANAGE => 'Gérer la structure coutumière (création, modification, suppression)',
            self::ANSTAT_VIEW => 'Consulter le découpage administratif ANStat (districts, régions, départements)',
            self::ANSTAT_MANAGE => 'Gérer le découpage administratif ANStat',
            self::STATS_VIEW => 'Consulter les tableaux de bord et statistiques globales',
            self::USER_MANAGE => 'Gérer les comptes utilisateurs',
            self::ROLE_MANAGE => 'Attribuer les rôles et privilèges administrateurs',
        };
    }
}
