# TROISIÈME PARTIE : MISE EN ŒUVRE, RÉSULTATS ET ÉVALUATION

---

# CHAPITRE VI : ENVIRONNEMENT DE TRAVAIL ET IMPLÉMENTATION

La deuxième partie de ce mémoire a permis de poser les fondations conceptuelles et méthodologiques de la plateforme de recensement unifiée à travers une modélisation UML rigoureuse. Cette troisième partie amorce la phase de réalisation concrète. Le présent chapitre est consacré à la description de l'architecture technique retenue, à la présentation des ressources matérielles et logicielles ayant constitué notre environnement de travail, ainsi qu'à l'explicitation des choix d'implémentation ayant guidé le développement des fonctionnalités maîtresses du système.

---

## I. Architecture globale du système

Pour garantir une séparation nette des responsabilités, une maintenance aisée et une grande évolutivité, la plateforme de recensement s'appuie sur une **architecture à trois niveaux (3-Tier) découplée**, articulée autour d'une **API REST backend sous Laravel** (exposée sur PHP 8.3+) et d'une application client **Single Page Application (SPA) développée en React**.

### 1. La couche de présentation (Frontend SPA React)

La couche de présentation constitue l'interface dynamique accessible par les utilisateurs (citoyens recensés, administrateurs et modérateurs). Exécutée côté client dans le navigateur web, elle fonctionne de manière totalement autonome en tant qu'application à page unique (Single Page Application - SPA).
- **Technologies de rendu** : Conçue avec la bibliothèque réactive **React** (en exploitant le modèle par composants fonctionnels et les crochets de gestion d'état - *React Hooks* tels que `useState`, `useEffect` et `useCallback`) et stylisée via le framework utilitaire de feuilles de style (Tailwind CSS), l'interface s'appuie sur l'outil d'assemblage moderne **Vite** (associé au plugin officiel `@vitejs/plugin-react`).
- **Consommation d'API et réactivité** : Le frontend React interroge les points d'accès de l'interface de programmation d'application (API REST - *Application Programming Interface*) via un client de requêtes HTTP (Axios). Grâce à la gestion d'état locale et à l'asynchronisme de React, l'expérience utilisateur est fluide et instantanée : les sélecteurs de territoires en cascade se mettent à jour sans rechargement de page, et les formulaires réagissent immédiatement aux actions du citoyen.
- **Ergonomie et adaptabilité** : L'interface adopte une approche d'affichage adaptatif (*responsive design*) garantissant une navigation optimisée sur ordinateurs, tablettes et smartphones.


### 2. La couche logique métier (Backend API REST Laravel)

Placée au cœur du système, la couche logique métier s'exécute côté serveur sous forme d'une **API REST découplée**. Elle reçoit les requêtes JSON en provenance du client React, contrôle la légitimité des accès, applique les règles de validation et pilote les opérations de persistance.
- **Architecture API & Modèles Eloquent** :
  - **Contrôleurs d'API (`API Controllers`)** : Réceptionnent les requêtes HTTP/JSON, valident les données saisies via des requêtes de formulaires dédiées (notamment `RessortissantRequest`, `CantonRequest`, `TribuRequest`, `VillageRequest`) et retournent des réponses JSON structurées (notamment `RessortissantController`, `DistrictController`, `RegionController`, `StatistiqueController`, `AuthController`).
  - **Modèles Eloquent (`Models`)** : Représentent les 10 entités métier (`District`, `Region`, `Departement`, `SousPrefecture`, `Canton`, `Tribu`, `Village`, `Ressortissant`, `Pays`, `User`) et encapsulent les règles d'accès à la base de données relationnelle.
  - **Commandes Console (`Artisan Commands`)** : Exécutent les tâches d'arrière-plan et d'importation automatisée, à l'image de la commande `anstat:import` (`App\Console\Commands\ImportAnstat`).
- **Sécurité et authentification découplée** : L'accès aux fonctionnalités restreintes (espace citoyen connecté et console de modération) est strictement sécurisé par le middleware **Laravel Sanctum**, qui vérifie les jetons d'authentification (*Bearer Tokens*) transmis dans le header HTTP `Authorization` à chaque requête d'écriture ou de modération.

### 3. La couche de données (Persistance SQL)

La couche de données prend en charge le stockage permanent et l'intégrité des informations de la plateforme. En phase de développement local et de prototypage, la solution s'appuie sur le moteur de base de données relationnelle embarqué **SQLite** (intégré nativement dans l'environnement Laravel). L'architecture et le schéma ORM sont conçus pour migrer de manière transparente vers le système de gestion de base de données relationnelle **MySQL** lors du déploiement en environnement de production à grande échelle.
- **Périmètre des données** : La base assure la persistance du référentiel territorial national (526 sous-préfectures et découpages englobants), du maillage coutumier (cantons, tribus, villages), des fiches citoyennes enregistrées et des comptes utilisateurs.
- **Intégrité et sécurité SQL** : L'intégrité relationnelle est garantie par des contraintes formelles de clés étrangères (`FOREIGN KEY`) et par l'utilisation de transactions SQL lors des écritures complexes. Les interactions entre le backend API et la base s'effectuent exclusivement via des requêtes préparées gérées par le cartographe objet-relationnel (ORM - *Object-Relational Mapping*) Eloquent, immunisant le système contre les injections SQL.

### 4. Flux global de communication découplé

La circulation des informations entre le client React et l'API Laravel suit un itinéraire RESTful chiffré et sécurisé :
1. L'usager interagit avec l'interface React (par exemple : sélection d'un District ou soumission de sa fiche citoyenne).
2. Le composant React émet une requête asynchrone HTTP/JSON (via Axios et chiffrée en HTTPS) vers le backend Laravel API.
3. Le contrôleur d'API Laravel vérifie le jeton Sanctum (pour les routes protégées), traite la demande et sollicite le moteur de base de données (SQLite en développement local, MySQL en production) via l'ORM Eloquent.
4. La base de données exécute la requête, applique les contraintes et retourne le jeu de données.
5. Le backend formate le résultat au format JSON et le renvoie à l'application React, qui met à jour l'état du composant et réassocie l'interface de manière instantanée.

```
+-----------------------------------------------------------------------+
|                COUCHE 1 : PRÉSENTATION (FRONTEND SPA REACT)           |
|    Application Single Page (React / Vite / Tailwind CSS / Axios)      |
+-----------------------------------------------------------------------+
                                  |   ^
                    Requêtes JSON |   | Réponses JSON
                           HTTPS  v   |  RESTful
+-----------------------------------------------------------------------+
|             COUCHE 2 : LOGIQUE MÉTIER (BACKEND API LARAVEL)           |
|         API REST Découplée (Contrôleurs API, Sanctum & Eloquent)      |
+-----------------------------------------------------------------------+
                     |   ^                         |   ^
        Requêtes SQL |   | Données         Import  |   | Réponse JSON
                     v   | ANStat          ANStat  v   | ANStat
+------------------------------------+   +------------------------------+
| COUCHE 3 : PERSISTANCE (SQLITE/MYSQL)|   |   SERVICE EXTERNE (ANSTAT)   |
| Base de données relationnelle      |   | API Distante api-public.anstat|
+------------------------------------+   +------------------------------+
```

*Figure VI.1 — Architecture 3-Tier découplée (React SPA / API REST Laravel) — [diagramme_architecture_3_tier.drawio](file:///c:/Users/tieco/Desktop/herd%20Laravel/recensement/Contexte/base-de-connaissances/outputs/diagramme_architecture_3_tier.drawio)*

### 5. Diagramme de déploiement

Le diagramme de déploiement UML traduit la répartition physique des composants logiciels sur les équipements d'infrastructure. Le système s'articule autour de quatre nœuds principaux :

1. **Nœud Client / Frontend (Serveur Web Client - React SPA)** : Héberge le bundle SPA React compilé par Vite et l'exécute directement dans le navigateur usager.
2. **Nœud Serveur Web & API Backend (Laravel API / Nginx-Apache)** : Héberge l'API REST Laravel sur un environnement PHP 8.3+, gère la logique de validation, vérifie les jetons Sanctum et traite les requêtes JSON.
3. **Nœud Serveur de Base de Données (SQLite / MySQL)** : Héberge le moteur de persistance relationnel (SQLite pour l'environnement de développement et MySQL pour l'environnement de production), exécute les requêtes préparées et garantit l'intégrité des enregistrements.
4. **Nœud Service Externe (API ANStat)** : Fournit de façon distante le référentiel cartographique et territorial officiel via une interface de services REST/JSON.

*Note : La modélisation de l'architecture de déploiement est détaillée dans le diagramme UML de déploiement (`diagramme_deploiment_recensement.drawio`).*

*Figure VI.2 — Diagramme de déploiement de la plateforme de recensement*

---

## II. Environnement de développement

La concrétisation de la plateforme a mobilisé des outils matériels et logiciels sélectionnés pour garantir un environnement de travail performant et conforme aux exigences techniques du projet.

### 1. Ressources matérielles

Le développement, les tests locaux et la modélisation de la solution ont été réalisés sur un poste de travail individuel dont les caractéristiques techniques sont récapitulées ci-dessous :

*Tableau VI.1 — Spécifications techniques du poste de développement — [tableau_specifications_materielles.drawio](file:///c:/Users/tieco/Desktop/herd%20Laravel/recensement/Contexte/base-de-connaissances/outputs/tableau_specifications_materielles.drawio)*

| Composant | Caractéristiques & Spécifications |
| :--- | :--- |
| **Processeur (CPU)** | Intel® Core™ i5 (12ᵉ génération) à 1.30 GHz (jusqu'à 4.40 GHz Turbo) |
| **Mémoire vive (RAM)** | 12 Go DDR4 |
| **Stockage principal** | SSD NVMe de 512 Go |
| **Système d'exploitation** | Microsoft Windows 11 Édition 64 bits |
| **Navigateur de test** | Google Chrome (version stable) avec Chrome DevTools / React Developer Tools |

### 2. Ressources logicielles

L'environnement logiciel regroupe d'une part la pile technologique ayant servi à bâtir l'application, et d'autre part la suite d'outils ayant accompagné le cycle de développement.

#### 2.1 Langages et Frameworks

- **React** : Bibliothèque et framework JavaScript réactif pour la construction de l'interface Single Page Application (SPA). Il assure la gestion fluide de l'état local (`useState`), des effets de bord (`useEffect`) et la réutilisation de composants d'interface modulaires.
- **PHP 8.3+** : Langage de programmation côté serveur dédié au traitement de la logique applicative et à l'exposition sécurisée de l'API REST.
- **Laravel** : Framework PHP structurant l'API REST découplée. Il met à disposition l'ORM Eloquent, le système de migrations de base de données, les commandes Artisan personnalisées et l'authentification par jeton avec **Laravel Sanctum**.
- **Tailwind CSS** : Framework CSS utilitaire permettant de concevoir des interfaces modernes, épurées et adaptables (*responsive design*) directement au sein des composants React.

#### 2.2 Logiciels et outils d'ingénierie

- **Visual Studio Code** : Éditeur de code source principal enrichi d'extensions d'assistance pour React (JSX/TSX, ESLint), PHP, Laravel et Tailwind CSS.
- **Vite** : Outil de build frontend de nouvelle génération offrant un rechargement à chaud (*Hot Module Replacement*) instantané lors du développement en React (`@vitejs/plugin-react`).
- **Laravel Herd** : Environnement de développement serveur local fournissant PHP 8.3+, le serveur Nginx et la gestion de base de données (SQLite en dev / MySQL).
- **Git & GitHub** : Système de contrôle de version et plateforme d'hébergement distant assurant le suivi de l'historique du code source et la sauvegarde du projet.
- **Postman** : Outil de test d'API REST utilisé pour concevoir, tester et documenter les endpoints JSON du backend et de l'API ANStat.
- **Vercel** : Plateforme d'hébergement et de déploiement continu (*Continuous Deployment*) dédiée à la mise en ligne du client Single Page Application (SPA React).
- **Google / Google Chrome** : Navigateur et suite d'outils d'inspection (Chrome DevTools) utilisés pour les tests d'interface, la mesure de performance et le débogage réactif.
- **Draw.io** : Outil de modélisation graphique utilisé pour élaborer les diagrammes UML (cas d'utilisation, séquence, classes et déploiement).

---

## III. Implémentation des fonctionnalités clés

Cette section détaille la mise en œuvre technique de trois mécanismes fondamentaux au cœur de la plateforme de recensement.

### 1. Synchronisation automatique du découpage territorial (API ANStat)

Afin d'éviter toute saisie manuelle erronée et de disposer d'un référentiel territorial officiel irréprochable, l'application intègre une commande d'importation automatisée d'ANStat (`php artisan anstat:import`).
- **Fonctionnement** : La classe Artisan `ImportAnstat` (`App\Console\Commands\ImportAnstat`) interroge successivement les points d'accès distants de l'API ANStat (`https://api-public.anstat.ci/api/v1`) selon une séquence hiérarchique descendante immuable : **Districts ➔ Régions ➔ Départements ➔ Sous-préfectures**.
- **Persistance intelligente** : Pour chaque entité récupérée au format JSON, le système recourt à la méthode Eloquent `updateOrCreate()`. Ce mécanisme met à jour les informations existantes ou insère les nouvelles entités sans risquer de créer des doublons. Le traitement alimente ainsi en quelques secondes l'ensemble des **526 sous-préfectures** et leurs territoires parents sur tout le territoire ivoirien.

### 2. Chargement dynamique en cascade dans l'interface React

Pour garantir une expérience de saisie rapide et éviter qu'un citoyen n'associe une sous-préfecture à une région inappropriée, l'interface React applique un filtrage réactif en cascade.
- **Réactivité React & Axios** : Lorsque l'utilisateur sélectionne un District dans le composant React de recensement, un hook `useEffect` écoute la modification de l'état `selectedDistrictId` et déclenche un appel Axios filtré vers le backend (`/api/regions?cod_dist={code}`). Le composant React met à jour son état `regionsList` et réaffiche immédiatement la liste des Régions disponibles.
- **Cascade complète** : Le processus s'enchaîne de manière réactive pour le Département, puis la Sous-préfecture. Il s'applique à l'identique sur le versant coutumier pour la hiérarchie Canton ➔ Tribu ➔ Village. Cette approche SPA garantit une réactivité instantanée sans aucun rechargement de page.

### 3. Gestion du double ancrage et workflow de modération

Le modèle d'enregistrement concilie les exigences administratives et les réalités traditionnelles :
- **Double ancrage** : La liaison à la Sous-préfecture est définie comme obligatoire (ancrage administratif officiel), tandis que le rattachement au Village est configuré comme optionnel ($0..1$). Cette flexibilité permet d'enregistrer la déclaration même si l'usager méconnaît son village d'origine exact.
- **Modération des déclarations via l'API sécurisée** : Chaque fiche de recensement créée par un citoyen est enregistrée avec le statut initial `en_attente`. La console d'administration React permet aux modérateurs d'examiner les fiches et de soumettre une décision aux endpoints sécurisés de l'API backend (`PATCH /api/ressortissants/{id}/valider` et `PATCH /api/ressortissants/{id}/rejeter`) :
  - **Validation** : Le statut passe à `valide`, intégrant officiellement la fiche dans les comptages nationaux.
  - **Rejet** : Le statut passe à `rejete` avec l'enregistrement obligatoire d'un **motif de rejet**, permettant au citoyen de corriger sa demande.
