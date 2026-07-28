# Architecture de Modération, Dédoublonnage et Référentiels

> _Sources : `raw/Liste des 193 pays du monde.md`, `raw/DOC-20260504-WA0000..pdf`, `raw/Laravel Expert.md`._  
> Pages liées : [projet-recensement.md](projet-recensement.md) · [reference-laravel.md](reference-laravel.md)

---

## 1. Modération et Gestion Dynamique des Villages Coutumiers

En l'absence d'une API nationale officielle pour les structures coutumières (Cantons, Tribus, Villages), l'application utilise une approche de **Modération Participative avec Validation Administrative** (Crowdsourcing modéré).

### A. Rôle du Filtre Géographique
- L'utilisateur sélectionne d'abord la **Sous-Préfecture** (qui contient entre 15 et 30 villages en moyenne).
- La recherche autocomplétée ne propose que les villages rattachés à cette sous-préfecture, évitant 95% des saisies manuelles inutiles.

### B. Normalisation du Nom (`nom_clean`)
Pour éviter la duplication due aux variations de casse, d'accents ou d'espaces :
- `nom` : conservé pour l'affichage (ex: `"Kiémou"`).
- `nom_clean` : version nettoyée en minuscules sans accents ni espaces superflus (ex: `"kiemou"`).
- **Règle de dédoublonnage** : Si un village avec le même `nom_clean` existe sous la même sous-préfecture, l'ID existant est réutilisé automatiquement.

### C. Workflow de Validation par l'Administrateur
1. **Statut de soumission** : Lorsqu'un nouveau village est proposé, le ressortissant est enregistré avec le statut `statut_validation = 'en_attente'`.
2. **Tableau de bord Admin** : L'administrateur passe en revue les propositions.
3. **Validation par lot (Batch Validation)** : La validation d'un village par l'administrateur (`is_verified = true`) valide automatiquement tous les ressortissants rattachés à ce village et ajoute définitivement ce village à la liste déroulante officielle.
4. **Gestion des fusions** : Si l'utilisateur a commis une faute de frappe (ex: `"Kiémoun"` au lieu de `"Kiémou"`), l'Admin réattribue les ressortissants au village réel en 1 clic.

---

## 2. Automatisation via `RessortissantObserver`

Pour éliminer les requêtes SQL complexes avec 5-6 jointures (`JOIN`) lors des recherches et des tableaux de bord :

1. **Dénormalisation contrôlée** : La table `ressortissants` contient les clés étrangères dénormalisées : `district_id`, `region_id`, `departement_id`, `sous_prefecture_id`, `canton_id`, `tribu_id`, `village_id`, `pays_id`.
2. **Déclenchement automatique** : L'observer `RessortissantObserver` réagit à l'événement `saving` uniquement lorsque `village_id` est modifié (`isDirty('village_id')`).
3. **Résolution des 2 branches** :
   - Branche Coutumière : `Village ➔ Tribu ➔ Canton`
   - Branche ANStat : `Village ➔ SousPrefecture ➔ Departement ➔ Region ➔ District`
4. **Cache Mémoire (`$villageCache`)** : Évite le problème N+1 lors des imports massifs ou des créations par lot dans le même village.
5. **Réinitialisation automatique** : Si `village_id` est remis à `null`, tous les champs parents dénormalisés sont réinitialisés à `null`.

---

## 3. Référentiel des 193 Pays de l'ONU

_Source : `raw/Liste des 193 pays du monde.md`._

- **Table `pays`** : Contient les 193 États membres officiels de l'ONU.
- **Seeder (`PaysSeeder.php`)** : Remplit la table en BDD et marque **"Côte d'Ivoire"** comme pays par défaut (`is_default = true`).
- **Endpoint API (`GET /api/pays`)** : Renvoie la liste complète des pays ordonnée avec la Côte d'Ivoire en 1ère position, suivie de la liste triée par ordre alphabétique (`is_default DESC, nom ASC`).
