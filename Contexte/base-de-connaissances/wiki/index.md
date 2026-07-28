# Index de la base de connaissances

> Wiki organisé par l'assistant à partir du dossier `raw/` et des arbitrages du projet. Le plus récent en haut.  
> Chaque page cite ses sources (fichiers d'origine dans `raw/`).

## Sujets

1. **[Architecture de Modération, Dédoublonnage et Référentiels](architecture-moderation-et-referentiels.md)**  
   Architecture de modération des villages (`nom_clean`, autocomplétion, validation admin par lot, statut `en_attente`), dénormalisation optimisée par `RessortissantObserver` (cache N+1) et référentiel ONU 193 pays (`PaysSeeder`).  
   _Sources : `raw/Liste des 193 pays du monde.md`, `raw/DOC-20260504-WA0000..pdf`, `raw/Laravel Expert.md`._

2. **[Projet — Recensement des Ressortissants Ivoiriens](projet-recensement.md)**  
   Objectifs fonctionnels et techniques du projet : structure ANStat (4 niveaux), structure coutumière (3 niveaux), modélisation du Ressortissant et cartographie API ANStat.  
   _Sources : `raw/Sujet.md`, `raw/DOC-20260504-WA0000..pdf`, `raw/WhatsApp Image 2026-05-25 at 07.53.54.jpeg`, `raw/Liste des 193 pays du monde.md`._

3. **[Référence Laravel & Conventions de Développement](reference-laravel.md)**  
   Normes d'architecture Laravel 11 : FormRequests, Observers (`#[ObservedBy]`), Seeders `upsert`, Eloquent relations et commandes Artisan.  
   _Sources : `raw/documentation de Laravel/`, `raw/Laravel codeur.md`, `raw/Laravel Expert.md`, `raw/code-commenter_1.md`, `raw/dev-step-by-step.md`._

4. **[Structure Officielle du Mémoire (Direction de la Pédagogie ESATIC)](structure-officielle-memoire.md)**  
   La norme officielle obligatoire : ordre des séquences, pagination bas-droite, tranches de pages (25-30 pages Licence), gabarits d'introduction et de conclusion.  
   _Source : `raw/STRUCTURE DU MEMOIRE DE FIN DE CYCLE ET SOUTENANCE VF 1 TOUTE DERNIERE.pdf`._

5. **[Méthodologie de Rédaction du Mémoire (ESATIC)](methodologie-memoire.md)**  
   Directives de rédaction, présentation matérielle, normes de citation APA, préparation du support de soutenance PPTX.  
   _Sources : `raw/COURS METHODOLOGIE DE REDACTION DE MEMOIRE DE FIN DE CYCLE 22-23 VF.pdf`, `raw/Présentation_Soutenance .pptx`._

6. **[Exemples et Modèles de Mémoires de Référence](exemples-et-modeles-memoires.md)**  
   Analyses des mémoires réels déposés dans `raw/` (Brou Siévié, Coulibaly Tenena, Bénédicte Ngandi, Hien Yao Axel).  
   _Sources : `raw/brou_sievie_jean_marc_24-ESATIC0082AS-4.pdf`, `raw/MEMOIRE DE COULIBALY TENENA MOHAMED FNL.pdf`, `raw/MEMOIRE BENEDICTE_NGANDI.pdf`, `raw/MEMOIRE_HIEN_YAO_AXEL_.pdf`, `raw/Exemple de Memoire.pdf`._

---

## Statut des Manques
- _Aucun manque actuellement. Toutes les sources du dossier `raw/` sont référencées et organisées dans ce Wiki._
