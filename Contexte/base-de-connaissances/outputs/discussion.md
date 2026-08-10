# Synthèse de la Discussion & Décisions d'Architecture - Application de Recensement

Fichier récapitulatif des décisions prises lors de la session d'échange (/grill-me).

---

## 1. Acteurs du Système & Modèle d'Authentification

* **Administrateur**
  * **Authentification** : Email + Mot de passe (Laravel Sanctum).
  * **Rôle** : Supervision globale, modération des fiches de recensement transmises (Valider / Rejeter avec motif motivé), administration du référentiel coutumier (Cantons, Tribus, Villages) et contrôle des imports ANStat via CLI.

* **Ressortissant / Citoyen**
  * **Authentification** : Email / Numéro de téléphone + Mot de passe (Laravel Sanctum).
  * **Rôle** : S'inscrire en ligne sur l'espace citoyen, remplir et mettre à jour sa propre fiche de recensement individuelle, télécharger ses pièces justificatives (CNI, Passeport, Extrait, Justificatif de domicile), déclarer son adresse de résidence (nationale ou Diaspora avec Consulat) et suivre le statut de modération de sa fiche (`en_attente`, `valide`, `rejete`).

---

## 2. Lien entre Structure Administrative (ANStat) et Structure Coutumière

* **Pivot de Jonction : La Sous-Préfecture vers le Canton**
  * **Côté Administratif (ANStat)** : `Sous-Préfecture` $\rightarrow$ `Département` $\rightarrow$ `Région` $\rightarrow$ `District`.
  * **Côté Coutumier** : `Village` $\rightarrow$ `Tribu` $\rightarrow$ `Canton` $\rightarrow$ `Sous-Préfecture`.
  * **Table `cantons`** : Relie chaque canton coutumier à sa `sous_prefecture_id` (ANStat).
  * **Table `villages`** : Relie chaque village à sa `tribu_id` (coutumier).

---

## 3. Structure & Décisions sur la Table `ressortissants`

### 1. Fusion de la Table `residences` (Suppression de la table séparée)
* **Décision** : La table `residences` est **définitivement supprimée**.
* **Intégration** : Les champs d'adresse de résidence (`pays`, `ville`, `quartier`, `adresse`) sont intégrés directement dans la table `ressortissants`.

### 2. Dénormalisation de la Structure Administrative dans `ressortissants`
* **Décision** : Option B (Dénormalisée) retenue pour la table `ressortissants`.
* **Structure des clés** : La table `ressortissants` contient directement tous les niveaux d'IDs d'appartenance administrative : `district_id`, `region_id`, `departement_id`, `sous_prefecture_id` (ainsi que `village_id` pour l'origine coutumière).
* **Objectif** : Permettre des filtrages directs et des calculs statistiques ultra-rapides sur les ressortissants sans jointures sur l'arbre ANStat.

### 3. Gestion des Champs Obligatoires vs Nullables (`nullable`)
* **Champs OBLIGATOIRES (`required`)** :
  * `nom`, `prenom`, `sexe`, `pays`.
* **Champs NULLABLE (facultatifs)** :
  * `telephone`, `date_naissance`, `lieu_naissance`, `famille`.
  * `district_id`, `region_id`, `departement_id`, `sous_prefecture_id`, `village_id`.
  * `ville`, `quartier`, `adresse`.

### 4. Formularisation & Validation (`RessortissantRequest.php`)
* **Décision** : Utilisation d'une **classe unique `RessortissantRequest`** partagée pour la Création (`store`) et la Modification (`update`).

---

## 4. Synthèse des Modules Fonctionnels de l'API Backend

1. **Authentification & Gestion des Rôles (RBAC)** :
   * Connexion sécurisée Sanctum (Email pour Administrateur, Email/Téléphone pour Citoyen/Ressortissant).
   * Restreindre les droits d'accès et d'édition selon le rôle (Admin ou Citoyen).

2. **Module Référentiel Administratif (ANStat)** :
   * Importation automatique des données officielles ANStat via `php artisan import:anstat`.
   * Routes de consultation (Read-only API).

3. **Module Référentiel Coutumier** :
   * Endpoints CRUD réservés aux Administrateurs pour structurer et entretenir l'arbre coutumier (Cantons, Tribus, Villages).

4. **Module Recensement des Ressortissants (Table Unique)** :
   * Saisie et gestion des fiches d'identité, de l'adresse de résidence (`pays`, `ville`, `quartier`, `adresse`), et du rattachement administratif dénormalisé (`district_id`, `region_id`, `departement_id`, `sous_prefecture_id`) ainsi que coutumier (`village_id`).
   * Validé via `RessortissantRequest`.

5. **Module Statistiques & Dashboard** :
   * Endpoints d'agrégation et de reporting par région, sous-préfecture, canton, village et pays de résidence.
