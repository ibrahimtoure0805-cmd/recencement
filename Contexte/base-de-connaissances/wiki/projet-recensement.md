# Projet — Recensement des Ressortissants Ivoiriens

> _Sources : `raw/Sujet.md`, `raw/DOC-20260504-WA0000..pdf` (spécification fonctionnelle), `raw/WhatsApp Image 2026-05-25 at 07.53.54.jpeg` (organigramme), `raw/Liste des 193 pays du monde.md`._  
> Pages liées : [architecture-moderation-et-referentiels.md](architecture-moderation-et-referentiels.md) · [reference-laravel.md](reference-laravel.md) · [methodologie-memoire.md](methodologie-memoire.md)

---

## 1. Sujet & Objectif
Concevoir une **application web et API de recensement des ressortissants ivoiriens** (résidant en Côte d'Ivoire et dans la Diaspora), reliant deux structures principales : la **structure administrative (ANStat)** et la **structure coutumière**.

---

## 2. Les Deux Hiérarchies de Données

### A. Structure Administrative (ANStat) — Officielle
Hiérarchie à 4 niveaux : **District → Région → Département → Sous-Préfecture**.
- Données importées automatiquement via la commande `php artisan anstat:import` depuis l'API publique ANStat (`https://api-public.anstat.ci/api/v1`).
- Lecture seule via l'API (`index`, `show`).

### B. Structure Coutumière — Traductrice de l'Ancrage Territorial
Hiérarchie : **Canton → Tribu → Village Coutumier**.
- Le **Village** possède une relation vers la `Tribu` (coutumière) et une relation optionnelle vers la `SousPrefecture` (ANStat), agissant ainsi comme le **pont unique** entre le coutumier et l'administratif.

---

## 3. Entité Centrale : Ressortissant & Résidence

- **Identité** : `nom`, `prenom`, `sexe` (obligatoires), `telephone`, `date_naissance`, `lieu_naissance`, `famille`, `user_id` (optionnel).
- **Rattachements Dénormalisés** : `district_id`, `region_id`, `departement_id`, `sous_prefecture_id`, `canton_id`, `tribu_id`, `village_id`.
- **Résidence (Locale & Diaspora)** : `pays_id` (référentiel ONU 193 pays), `pays` ("Côte d'Ivoire" par défaut), `ville`, `quartier`, `adresse`.

---

## 4. Modération & Optimisation Technique

- **Automatisations Observer** : Le modèle `Ressortissant` est supervisé par `RessortissantObserver` qui déduit et remplit automatiquement la hiérarchie géographique dès la sélection du village.
- **Workflow de Modération** : En cas de création de nouveau village non pré-existant, le recensement est soumis avec le statut `statut_validation = 'en_attente'` jusqu'à validation par l'Administrateur.
- **Référentiel 193 Pays** : Issu de `raw/Liste des 193 pays du monde.md`, exposé sur `GET /api/pays`.
