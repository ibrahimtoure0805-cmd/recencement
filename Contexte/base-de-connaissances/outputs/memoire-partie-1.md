# PREMIÈRE PARTIE — GÉNÉRALITÉS

> Rédaction en cours du mémoire (projet Recensement, ESATIC / Worldev). Plan « Façon A » (titres du cours de méthodologie). Cible : ~11 pages pour cette partie, ~35 pages pour le corps du mémoire. Sources factuelles : mémoire Kotou (entreprise Worldev), recherches web vérifiées (voir [recherche-etat-de-lart.md](recherche-etat-de-lart.md)), textes de loi officiels.
>
> Statut des sections : PREMIÈRE PARTIE COMPLÈTE ✅ (Chapitres I et II validés, transition incluse).
> Acteurs du système : **Ressortissant + Administrateur** (toutes les fonctionnalités sont gérées directement par les ressortissants et validées par les administrateurs).
> Règle de rédaction (Ibrahim, 2026-07-07) : langage simple partout, pas de mots compliqués — sauf citations officielles entre guillemets, qui ne se réécrivent jamais.

---

*Cette première partie pose les bases nécessaires à la compréhension du projet. Elle présente d'abord l'entreprise d'accueil au sein de laquelle le travail a été mené, puis conduit une étude préalable du projet : son contexte et ses objectifs, les notions de base indispensables, l'étude des dispositifs de recensement et d'identification déjà existants, et enfin le cahier des charges qui a orienté la conception de la solution.*

---

## CHAPITRE I — PRÉSENTATION DE L'ENTREPRISE D'ACCUEIL

### I. Présentation de Worldev

**1. Historique et positionnement**

Worldev est une société à responsabilité limitée (SARL) ivoirienne spécialisée dans la conception, le développement et l'intégration de solutions informatiques sur mesure. Basée à Cocody Deux-Plateaux, à Abidjan, elle a été fondée en 2017 par Bahi Zaourou Hubert, à partir de la transformation de l'entreprise IMSC. Elle accompagne des organisations issues des secteurs de l'immobilier, du commerce et des services dans leur transformation numérique, en alliant une bonne connaissance du marché local et le respect des standards internationaux.

**2. Activités et domaines d'expertise**

La mission de Worldev est d'accompagner ses clients dans la conception et l'intégration de solutions numériques performantes. Ses activités couvrent le développement d'applications web et mobiles, la mise en place de solutions de gestion (ERP et CRM), l'automatisation des processus métiers et l'intégration d'interfaces de programmation (API). L'entreprise s'appuie sur quatre valeurs fondamentales — l'innovation, la qualité, la proximité client et l'engagement — qui guident chacun de ses projets. Parmi ses réalisations figurent E-Stock FNE, une solution de gestion et de suivi des mouvements de stock, ainsi que la Bibliothèque numérique du CNDJ, qui facilite l'accès aux ressources juridiques numériques.

### II. Organisation interne de Worldev

*[Figure 1 — Organigramme de Worldev, à insérer]*

L'organigramme ci-dessus présente la structure de Worldev et les relations entre ses différents services (direction, service technique, service commercial, ressources humaines). Cette organisation assure la coordination des activités administratives, techniques et commerciales, et constitue le cadre dans lequel le projet de recensement a été réalisé.

---

## CHAPITRE II — ÉTUDE PRÉALABLE DU PROJET

### I. Présentation du projet

**1. Contexte du projet**

La Côte d'Ivoire modernise progressivement ses outils de recensement et d'identification de la population. L'Agence Nationale de la Statistique (ANStat) réalise le Recensement Général de la Population et de l'Habitat (RGPH) pour dénombrer les habitants par zone administrative, tandis que l'Office National de l'État Civil et de l'Identification (ONECI) gère l'état civil à travers le Registre National des Personnes Physiques (RNPP) et le logiciel Cityweb. Pourtant, aucun de ces outils n'enregistre le rattachement coutumier d'un Ivoirien — son canton, sa tribu, son village traditionnel —, qui demeure géré de façon informelle.

C'est dans ce contexte que s'inscrit le présent projet, mené au sein de Worldev en tant que projet interne. Il consiste à concevoir une application web de recensement qui relie, pour chaque ressortissant, son rattachement administratif officiel et son rattachement coutumier, en s'appuyant sur les données réelles de l'ANStat et sur l'organisation traditionnelle reconnue en Côte d'Ivoire. Le projet s'inscrit ainsi dans une démarche de modernisation qui valorise à la fois la dimension administrative et la dimension culturelle du recensement national.

**2. Objectifs du projet**

L'objectif général est de concevoir et de développer une application web capable de recenser les ressortissants ivoiriens en les rattachant à la fois à la structure administrative (ANStat) et à la structure coutumière. Cet objectif se décline en quatre objectifs spécifiques :

- récupérer les données officielles de l'ANStat (district, région, département, sous-préfecture) et les enregistrer dans la base de données ;
- construire la structure coutumière (canton, tribu, village) dans la base de données, sans la subordonner à la structure administrative ;
- créer la fiche du ressortissant reliant chaque personne à ses deux rattachements et à son lieu de résidence ;
- mettre en place l'authentification et l'enregistrement des ressortissants dans l'application.

### II. Notions de base

**1. Le recensement**

Le recensement est l'opération qui consiste à dénombrer et à décrire la population d'un territoire à un moment donné. Il permet à l'État de connaître le nombre d'habitants, leur répartition et leurs caractéristiques, afin de planifier les politiques publiques (santé, éducation, infrastructures). Dans ce projet, le recensement ne se limite pas au comptage : il vise à enregistrer chaque personne avec ses deux rattachements territoriaux.

**2. La structure administrative (ANStat)**

La structure administrative est le découpage officiel du territoire ivoirien, tel qu'il est établi et publié par l'ANStat. Il s'organise en quatre niveaux emboîtés, du plus grand au plus petit : le **district**, la **région**, le **département** et la **sous-préfecture**. Chaque niveau dépend du niveau supérieur : une région appartient à un district, un département à une région, et une sous-préfecture à un département. Ce découpage sert de base officielle pour situer administrativement une personne.

**3. La structure coutumière**

La structure coutumière est l'organisation traditionnelle de la population, reconnue par la loi n° 2014-428 du 14 juillet 2014 portant statut des Rois et Chefs traditionnels. Elle repose sur des autorités désignées selon les us et coutumes. Dans le cadre de ce projet, trois niveaux sont retenus, du plus grand au plus petit : le **canton**, la **tribu** et le **village**. Contrairement à la structure administrative, elle traduit l'appartenance culturelle et historique d'une personne à sa communauté d'origine.

### III. Étude de l'existant et diagnostic

#### I. Évaluation des systèmes d'identification et de recensement existants

**1. Méthodologie d'évaluation des dispositifs actuels**

Afin de mesurer les forces et les limites des solutions existantes en Côte d'Ivoire, nous avons analysé les dispositifs nationaux selon cinq critères complémentaires : la prise en compte du découpage administratif, la gestion du rattachement coutumier, la continuité du suivi, l'inclusion de la diaspora et le degré d'automatisation des données. Le tableau ci-dessous décrit le périmètre de chacun de ces critères.

| Critère | Ce qui est mesuré |
|---|---|
| **Découpage administratif** | Présence et précision de l'organisation officielle du territoire (du district jusqu'à la sous-préfecture). |
| **Ancrage coutumier** | Enregistrement de l'appartenance traditionnelle de la personne (son canton, sa tribu et son village d'origine). |
| **Permanence du suivi** | Capacité à maintenir un registre d'information à jour dans le temps (système continu versus enquête ponctuelle). |
| **Prise en compte de la diaspora** | Possibilité de recenser les Ivoiriens résidant à l'étranger sans confondre leur lieu de vie et leur origine. |
| **Gestion et automatisation** | Facilité d'alimentation du système et autonomie de gestion des données sans saisie manuelle lourde. |

*Tableau 1 — Critères d'évaluation des systèmes d'identification et de recensement existants*

**2. Diagnostic des dispositifs institutionnels en Côte d'Ivoire**

L'examen des outils actuels au regard de ces critères met en évidence un manque d'articulation entre l'administratif et le coutumier.

* **Le Recensement Général de la Population et de l'Habitat (RGPH).** Mené par l'ANStat, le RGPH 2021 a dénombré 29 389 150 habitants. Il couvre très bien le territoire administratif jusqu'au village. Cependant, il s'agit d'une photo ponctuelle réalisée tous les dix ans et non d'un suivi continu. De plus, il ne garde pas de dossier individuel reliant la personne à son canton ou à sa tribu d'origine.
* **Le Registre National des Personnes Physiques (RNPP) et Cityweb.** Gérés par l'ONECI (décret n° 2018-454), ils attribuent un Numéro National d'Identification (NNI) et assurent un suivi permanent de l'état civil (naissances, mariages, décès). Toutefois, ce système s'arrête à l'état civil légal : il n'enregistre aucune donnée sur la structure coutumière et nécessite une présence physique dans les centres d'état civil, ce qui rend l'accès difficile pour la diaspora.
* **La Chambre Nationale des Rois et Chefs Traditionnels (CNRCT).** Créée par la loi n° 2014-428 du 14 juillet 2014 et confirmée par la Constitution de 2016, elle liste les autorités traditionnelles (Rois, Chefs de canton, de tribu et de village). Cependant, elle constitue un annuaire des chefs eux-mêmes et non une base de données de la population rattachée à chaque chefferie. De plus, des travaux sociologiques (Flan et al., 2021 ; Bah et al., 2018) soulignent la fragilité de la gestion locale des chefferies dans certaines zones.

#### II. Enseignements internationaux et spécificités du contexte ivoirien

**1. Comparaison internationale (Ghana et Afrique du Sud)**

L'analyse d'expériences étrangères montre la même séparation. Au Ghana, l'autorité d'identification (NIA) s'appuie sur les chefs pour sensibiliser la population lors de l'établissement de la *Ghana Card*, mais le lien coutumier n'est pas sauvegardé dans la base nationale. En Afrique du Sud, la loi de 2003 (*Traditional Leadership and Governance Framework Act*) reconnaît l'autorité traditionnelle, mais le registre national (*Department of Home Affairs*) reste totalement séparé.

**2. Spécificités du contexte ivoirien et positionnement du projet**

En Côte d'Ivoire, l'appartenance coutumière garde une forte valeur sociale et identitaire. De plus, de nombreux ressortissants vivent hors de leur région d'origine ou à l'étranger. Notre projet apporte une réponse ciblée : une application web légère qui importe automatiquement les données officielles de l'ANStat par API, permet la création autonome des structures coutumières (canton, tribu, village) et donne la possibilité à chaque Ivoirien — d'ici ou de la diaspora — de renseigner sa fiche en ligne de manière simple et sécurisée.

### IV. Cahier des charges

Le cahier des charges définit ce que l'application doit faire et dans quelles conditions. Il sert de document de référence pour guider la conception et le développement tout au long du projet.

**1. Besoins fonctionnels**

Les besoins fonctionnels décrivent les actions que chaque utilisateur doit pouvoir faire dans l'application. Deux acteurs ont été retenus : le **ressortissant**, qui se recense lui-même en créant un compte — ce qui convient particulièrement à la diaspora —, et l'**administrateur**, qui gère les données de référence, les comptes, et peut enregistrer ou corriger une fiche au besoin.

| Acteur | Besoin fonctionnel | Description |
|---|---|---|
| **Ressortissant** | Créer un compte et se connecter | S'enregistrer dans l'application avec ses informations personnelles. |
| | Remplir sa fiche de recensement | Renseigner son identité, son rattachement administratif (district, région, département, sous-préfecture), son rattachement coutumier (canton, tribu, village — facultatif) et son lieu de résidence. |
| | Consulter sa fiche | Revoir ses informations et les mettre à jour. |
| **Administrateur** | Gérer la structure administrative | Lancer l'import des données officielles de l'ANStat et vérifier leur bonne insertion. |
| | Gérer la structure coutumière | Créer et mettre à jour les cantons, les tribus et les villages. |
| | Gérer les comptes | Gérer les comptes des ressortissants et les accès. |
| | Enregistrer ou corriger une fiche | Créer ou mettre à jour la fiche d'un ressortissant au besoin. |

**2. Besoins non fonctionnels**

Au-delà des fonctions, l'application doit respecter des exigences de qualité :

| Critère | Exigence |
|---|---|
| **Fiabilité des données** | Le découpage administratif doit venir directement des données officielles de l'ANStat, sans ressaisie manuelle, pour éviter les erreurs de copie. |
| **Sécurité** | L'application enregistre des informations personnelles, y compris l'appartenance coutumière, qui est une donnée sensible. L'accès doit être protégé par une authentification et les données doivent être protégées. |
| **Simplicité d'utilisation** | Les écrans doivent rester simples et clairs, pour que toute personne, même peu habituée au numérique, puisse remplir sa fiche sans aide. |
| **Évolutivité** | La base de données doit pouvoir accueillir plus tard de nouveaux besoins (statistiques, exports, nouveaux niveaux de structure) sans tout refaire. |

**3. Contraintes techniques**

Le projet doit respecter le cadre technique suivant :
- l'application est développée avec le framework **Laravel** (langage PHP), conformément à l'environnement de travail de Worldev ;
- les données administratives proviennent de l'**API publique de l'ANStat**, qui impose ses propres règles : réponses paginées page par page, et méthodes d'accès différentes selon les points d'accès ;
- l'import des données doit être **rejouable** : on doit pouvoir le relancer sans créer de doublons dans la base ;
- la durée du stage — trois mois — impose de livrer une application fonctionnelle dans ce délai.

**4. Périmètre du projet**

Le périmètre précise ce qui est inclus dans le projet et ce qui ne l'est pas.

Pour la structure coutumière, la loi n° 2014-428 reconnaît cinq catégories d'autorités (Rois, Chefs de province, Chefs de canton, Chefs de tribu, Chefs de village). Le projet n'en retient que trois niveaux — **canton, tribu, village** —, conformément à la spécification fonctionnelle fournie par l'entreprise. Ce choix se justifie simplement : les Rois et les Chefs de province exercent leur autorité au-dessus de plusieurs cantons, mais le rattachement concret d'une personne à sa communauté se joue aux niveaux du canton, de la tribu et du village. Ce sont donc ces trois niveaux qui suffisent pour recenser un ressortissant.

Par ailleurs, les deux structures — administrative et coutumière — restent **indépendantes** dans la base de données : aucune ne dépend de l'autre. Un lien entre elles reste possible, mais il est facultatif, car les liens entre villages coutumiers et découpage administratif sont historiques et non systématiques.

De même, le **rattachement coutumier d'un ressortissant est facultatif** : une personne qui ne connaît pas son canton, sa tribu ou son village peut quand même être recensée. Son rattachement administratif et sa résidence suffisent pour créer sa fiche, et le rattachement coutumier peut être ajouté plus tard.

Enfin, le lieu de **résidence** est enregistré séparément des deux rattachements. Cette séparation permet de recenser aussi les Ivoiriens qui vivent à l'étranger : leur résidence est ailleurs, mais leurs rattachements administratif et coutumier restent en Côte d'Ivoire.

**5. Planification du projet**

Le projet se déroule sur les trois mois du stage, répartis en trois grandes phases qui suivent la logique des trois parties du mémoire :

| Période | Activités |
|---|---|
| **Mois 1** | Étudier les documents et les systèmes existants (RGPH, RNPP, Cityweb) ; rédiger le cahier des charges et l'étude de l'existant ; récupérer et analyser les données de l'ANStat. |
| **Mois 2** | Concevoir la base de données pour les deux structures ; développer l'import ANStat, la structure coutumière, l'authentification et l'enregistrement des ressortissants. |
| **Mois 3** | Tester et corriger l'application ; rédiger le mémoire de fin de cycle ; préparer la soutenance. |

---

*Transition*

*Cette première partie a permis de poser le cadre du projet. Elle a présenté l'entreprise d'accueil, puis situé le projet dans son contexte : les systèmes de recensement et d'identification qui existent en Côte d'Ivoire — le RGPH, le RNPP et son logiciel Cityweb, la Chambre Nationale des Rois et Chefs Traditionnels — remplissent chacun leur rôle, mais aucun ne relie le rattachement administratif et le rattachement coutumier d'une même personne. Le cahier des charges a ensuite précisé ce que l'application doit faire pour combler ce manque, pour quels acteurs et dans quelles limites. Il reste maintenant à transformer ces besoins en une solution concrète : c'est l'objet de la deuxième partie, consacrée à l'analyse et à la conception du système — le choix de la méthode d'analyse, puis la modélisation de l'application et de sa base de données.*
