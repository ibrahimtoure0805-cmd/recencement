Chapitre V — Modélisation du système

I. Diagrammes comportementaux

La modélisation comportementale décrit la manière dont la plateforme fonctionne au quotidien lorsqu'elle est utilisée. Elle montre comment les différents utilisateurs agissent avec l'application et comment les opérations s'exécutent. Pour présenter cette logique, deux outils principaux sont utilisés : les diagrammes de cas d'utilisation, qui dressent la liste complète des fonctionnalités proposées, et les diagrammes de séquence, qui expliquent pas à pas le cheminement des informations entre les composants du système pour réaliser les actions importantes.

1. Diagrammes de cas d'utilisation

1.0 Diagramme Global des Cas d'Utilisation (Vue d'ensemble)
Ce schéma d'ensemble donne une vue globale du système. Il montre clairement les échanges entre les quatre utilisateurs (Visiteur simple, Citoyen connecté par téléphone, Administrateur connecté par email et le système externe ANStat) et l'application centrale de recensement. Il explique le lien direct entre l'inscription des citoyens par eux-mêmes, l'organisation des zones (officielles et villageoises), le contrôle des fiches par l'équipe (validation ou refus) et la présentation des résultats statistiques.

(image du diagramme : Diagramme Global des Cas d'Utilisation)

1.1 Module Portail Public et Auto-Enregistrement
Le module du portail public rassemble l'ensemble des services accessibles à toute personne arrivant sur la plateforme sans nécessiter de connexion préalable. Il sert d'interface d'accueil et d'orientation pour les ressortissants. Ce module permet notamment de consulter le référentiel officiel des 193 pays membres de l'ONU, d'explorer les découpages géographiques officiels (découpage ANStat) et traditionnels (cantons, tribus, villages), d'accéder au formulaire d'ouverture de compte pour s'auto-recenser (authentification par numéro de téléphone), ainsi que de rechercher une entité administrative ou une localité.

(image du diagramme : Module Portail Public)

1.2 Module Espace Ressortissant
Le module de l'espace ressortissant regroupe toutes les actions réservées au citoyen une fois son compte créé et authentifié via son numéro de téléphone. Il permet au ressortissant de remplir et mettre à jour sa fiche individuelle de recensement en ligne (état civil, situation familiale, profil socio-professionnel/profession, lieu de résidence locale ou au sein de la Diaspora), de lier sa fiche à son territoire administratif officiel ainsi qu'à son village d'origine (structure coutumière), et de suivre le statut de modération de son enregistrement (en attente, validé ou rejeté avec motif).

(image du diagramme : Module Espace Ressortissant)

1.3. Module Administration et Gestion des Territoires
Le module d'administration est réservé aux gestionnaires de la plateforme authentifiés par email professionnel via jetons Laravel Sanctum. Il couvre le déclenchement de l'importation automatisée des données administratives nationales en interagissant avec l'API Externe ANStat, la gestion complète (ajout, modification, suppression) du référentiel coutumier (cantons, tribus, villages), le contrôle et la modération des fiches de recensement transmises par les ressortissants (opérations de validation ou de rejet motivé), ainsi que la gestion des accès administrateurs.

(image du diagramme : Module Administration)

1.4 Module Cartographie, Diaspora et Statistiques Démographiques
Le module de cartographie et de suivi décisionnel offre une vision synthétique et géographique de la population recensée à travers des endpoints d'agrégation dédiés (`/api/stats/globales`, `/api/stats/diaspora`, `/api/stats/coutumier`). Il permet d'effectuer des croisements démographiques précis entre la répartition administrative officielle, l'ancrage coutumier d'origine (top cantons et villages), le profil socio-professionnel et la localisation réelle des ressortissants (notamment la communauté résidant à l'étranger / Diaspora). Ce module fournit aux administrateurs et décideurs un tableau de bord dynamique présentant le volume global, la répartition par sexe (M/F) et la ventilation géographique.

(image du diagramme : Module Cartographie et Statistiques)
