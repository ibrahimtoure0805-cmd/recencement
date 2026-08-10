# Architecture de Modération des Villages et Référentiel des Pays

> **Synthèse des décisions d'architecture et de modération**  
> _Date : 21 Juillet 2026_

---

## 1. Contexte & Problématique

Lors du recensement d'un ressortissant ivoirien :
1. **Structures administratives (ANStat)** : Données officielles importées (Districts, Régions, Départements, Sous-Préfectures).
2. **Structures coutumières** : Pas d'API nationale existante pour les Cantons, Tribus et Villages coutumiers.
3. **Le problème de la saisie libre des villages** :
   - Un administrateur ne peut pas saisir 8 000+ villages à la main avant le lancement.
   - Si les ressortissants écrivent le nom d'un village manuellement, les variations de casse, d'espaces et d'accents (ex : `"Kiémou"`, `"kiemou "`, `"KIEMOU"`, `"Kiémoun"`) provoquent des doublons dangereux et polluent la base de données.

---

## 2. Solution d'Architecture Retenue : Modération Participative avec Validation Administrative

### A. Côté Ressortissant / Citoyen (Frontend)
- **Règles de nettoyage à la saisie (Normalisation)** :
  - Suppression des espaces au début/fin (`trim`).
  - Passage en minuscules (`strtolower`).
  - Suppression des accents (`é`, `è` ➔ `e`).
  - Génération d'un `nom_clean` (ex : `" Kiémou "` ➔ `"kiemou"`).
- **Autocomplétion intelligente (Search-or-Create)** :
  - Le champ de recherche de village filtre par la Sous-Préfecture sélectionnée (~15 à 30 villages possibles).
  - Si le village existe déjà dans la liste ➔ L'utilisateur le sélectionne (réutilisation de l'ID existant).
  - Si le village n'existe pas ➔ Message de confirmation : *"Voulez-vous demander la création du village 'Kiémou' ?"*.
- **Soumission sans blocage sur le terrain** :
  - Le formulaire de recensement est soumis avec le statut `statut_validation = 'en_attente'`.

### B. Côté Serveur & Base de Données (Backend Laravel)
- **Table `villages`** :
  - Colonne `nom_clean` pour dédoublonner automatiquement.
  - Colonne `is_verified` (booléen par défaut `false`).
- **Table `ressortissants`** :
  - Colonne `statut_validation` (`'en_attente'`, `'valide'`, `'rejete'`).
  - Colonne `motif_rejet` (nullable).
- **Observer (`RessortissantObserver.php`)** :
  - Calcule et déduit automatiquement la hiérarchie géographique (`district_id`, `region_id`, `departement_id`, `sous_prefecture_id`, `canton_id`, `tribu_id`) dès l'affectation du village.
  - Utilise un cache mémoire (`$villageCache`) pour éviter les requêtes N+1 lors des créations en masse.

### C. Côté Administrateur (Tableau de Bord de Modération)
- **Validation par lot (Batch Validation)** :
  - L'Admin vérifie le village proposé.
  - Si le village est réel ➔ L'Admin le valide (`is_verified = true`). Le village entre définitivement dans la liste déroulante officielle. Tous les ressortissants en attente rattachés à ce village passent automatiquement au statut `'valide'`.
  - Si c'est une faute de frappe (ex: *"Kiémoun"* au lieu de *"Kiémou"*) ➔ L'Admin réattribue les ressortissants au vrai village en 1 clic et valide leur dossier.
  - Si c'est un village imaginaire ➔ L'Admin rejette la demande avec motif (`statut_validation = 'rejete'`).

---

## 3. Référentiel International des 193 Pays (ONU)

- **Source de données** : `raw/Liste des 193 pays du monde.md`.
- **Table `pays`** :
  - Colonnes : `id`, `nom` (unique), `code_iso`, `is_default` (booléen).
- **Seeder (`PaysSeeder.php`)** :
  - Insère les 193 États membres de l'ONU.
  - Définit **"Côte d'Ivoire"** avec `is_default = true`.
- **Route API `GET /api/pays`** :
  - Renvoie la liste complète triée avec la Côte d'Ivoire en 1ère position, puis l'ensemble par ordre alphabétique (`is_default DESC, nom ASC`).

---

## 4. Tests Automatisés & Qualité Code

- **Suite de tests Pest / PHPUnit (14/14 passés, 51 assertions)** :
  - Tests du Seeder et de l'API `/api/pays`.
  - Tests du fonctionnement de l'Observer (création, modification, réinitialisation à null, cache mémoire, dédoublonnage).
  - Validation blindée dans `RessortissantRequest.php` et `VillageRequest.php` (conversion des chaînes vides `""` en `null`).
