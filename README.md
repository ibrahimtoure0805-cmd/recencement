# 📍 Recensement API - Plateforme de Recensement des Ressortissants

**Recensement API** est un backend Laravel (API REST) conçu pour recenser, cartographier et suivre les ressortissants ainsi que leurs résidences, tout en les liant au découpage territorial officiel (ANStat) et coutumier (Cantons, Tribus, Villages) de la Côte d'Ivoire.

---

## 🎯 Objectifs Clés du Projet

1. **Recensement en ligne et à distance (sans déplacement)** :
   * Permettre aux ressortissants (résidant localement, dans d'autres régions ou au sein de la diaspora à l'étranger) de se faire recenser en ligne en toute autonomie, sans devoir se déplacer physiquement.

2. **Identification & Suivi de la population** :
   * Enregistrer l'état civil, les contacts, les informations de famille et la localisation physique exacte (pays, ville, quartier, adresse) de chaque ressortissant.

3. **Double Rattachement Territorial** :
   * **Structure Administrative Officielle (ANStat / RGPH)** : `District` ➔ `Région` ➔ `Département` ➔ `Sous-préfecture` (Données officielles sous contrôle d'importation).
   * **Structure Coutumière / Traditionnelle** : `Canton` ➔ `Tribu` ➔ `Village` (Gestion complète CRUD pour refléter l'organisation communautaire locale).

4. **Architecture Backend Découplée (API First)** :
   * Servir de moteur de données REST JSON sécurisé, réutilisable par tout client frontend (Applications web React/Vue, applications mobiles iOS/Android, guichets et tableaux de bord).

---

## 🛠️ Stack Technique

* **Framework** : Laravel 11.x (PHP 8.2+)
* **Base de données** : MySQL / PostgreSQL / SQLite
* **Authentification** : Laravel Sanctum
* **Architecture** : API REST 100 % découplée

---

## 🚀 Structure des Routes API

Toutes les routes API sont préfixées par `/api` :

| Domaine | Endpoint | Méthodes | Description |
| :--- | :--- | :--- | :--- |
| **Auth / Utilisateur** | `/api/user` | `GET` | Profil de l'utilisateur connecté (middleware Sanctum) |
| **Découpage Administratif** | `/api/districts` | `GET` | Liste / Détail des districts |
| | `/api/regions` | `GET` | Liste / Détail des régions |
| | `/api/departements` | `GET` | Liste / Détail des départements |
| | `/api/sous-prefectures` | `GET` | Liste / Détail des sous-préfectures (paginées par 50) |
| **Découpage Coutumier** | `/api/cantons` | `GET, POST, PUT, DELETE` | Gestion complète des cantons |
| | `/api/tribus` | `GET, POST, PUT, DELETE` | Gestion complète des tribus |
| | `/api/villages` | `GET, POST, PUT, DELETE` | Gestion complète des villages |
| **Recensement** | `/api/ressortissants` | `GET, POST, PUT, DELETE` | Fiches individuelles des ressortissants |
| | `/api/residences` | `GET, POST, PUT, DELETE` | Adresses et résidences physiques |

---

## 💻 Commandes d'Importation & Tests

### Importer les données administratives officielles (ANStat) :
```bash
php artisan import:anstat
```

### Exécuter les tests unitaires et d'intégration :
```bash
php artisan test
```
