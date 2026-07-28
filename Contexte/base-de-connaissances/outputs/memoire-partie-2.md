# DEUXIÈME PARTIE : ANALYSE ET CONCEPTION DU SYSTÈME

> Rédigée avec Ibrahim, section par section, à partir du 07/07/2026. Même règles que la Partie 1 : cours de méthodologie = base absolue, langage simple, gabarit = schéma dominant des 5 mémoires exemples (chapitres numérotés à la suite de la Partie 1 : Chapitre III, Chapitre IV).
> Statut : DEUXIÈME PARTIE COMPLÈTE ✅ (Chapitres III et IV validés, transition incluse).
> Note (2026-07-08) : les règles de gestion sont conservées avec UML (elles justifient les multiplicités du diagramme de classes ; pratique confirmée par les mémoires Kotou et Brou).
> Choix de conception validés (Ibrahim, 2026-07-08) : A — rattachement administratif = une seule FK vers la sous-préfecture (niveaux supérieurs déduits) ; B — rattachement coutumier = une seule FK facultative (nullable) vers le village (tribu/canton déduits ; peut rester vide si le ressortissant ne connaît rien de son rattachement coutumier — cas réel confirmé).
> Décision (Ibrahim, 2026-07-07) : le rattachement coutumier d'un ressortissant est FACULTATIF (FK nullables). Répercuté dans le cahier des charges (P1, périmètre) et la description textuelle ci-dessous.
> Acteurs du système : **Ressortissant + Administrateur**. Le système s'appuie sur l'auto-enregistrement des ressortissants et la modération par l'administrateur.

---

*[Page intercalaire : DEUXIÈME PARTIE : ANALYSE ET CONCEPTION DU SYSTÈME]*

*[Chapeau]* La première partie a posé le cadre du projet : les systèmes existants, le manque à combler et le cahier des charges. Cette deuxième partie explique comment ces besoins ont été transformés en une solution concrète. Elle compare d'abord les méthodes d'analyse possibles et justifie celle qui a été retenue (Chapitre III), puis présente la modélisation du système : les acteurs et leurs actions, les échanges à l'intérieur de l'application, la structure de la base de données et l'architecture technique (Chapitre IV).

---

## CHAPITRE III — MÉTHODE D'ANALYSE ET DE CONCEPTION

### I. Présentation des méthodes d'analyse

**1. La méthode Merise**

Merise est une méthode française née dans les années 1970. Son idée principale est de séparer les données (ce que l'on stocke) des traitements (ce que l'on fait avec). Elle avance par niveaux successifs : d'abord un modèle conceptuel des données (MCD), qui décrit les informations et leurs liens sans se soucier de la technique ; puis un modèle logique (MLD), qui organise ces informations en tables ; enfin un modèle physique (MPD), adapté au logiciel de base de données choisi. Merise est très efficace pour concevoir des bases de données relationnelles, et elle reste très utilisée dans l'enseignement francophone.

**2. Le Processus Unifié (UP) et le langage UML**

Le Processus Unifié (UP) est une démarche de développement créée à la fin des années 1990 par les auteurs du langage UML. Elle repose sur quelques principes simples : partir des cas d'utilisation (ce que chaque acteur veut faire avec le système), avancer par étapes courtes qui produisent des versions successives du logiciel, et traiter en priorité les points les plus risqués. Le Processus Unifié s'appuie sur UML (Unified Modeling Language), un langage de schémas standardisé en 1997, qui permet de dessiner le système sous deux angles : son comportement (diagrammes de cas d'utilisation, diagrammes de séquence) et sa structure (diagramme de classes, diagramme de déploiement).

### II. Comparaison et choix de la méthode

**1. Comparaison**

| Critère | Merise | Processus Unifié (UP) + UML |
|---|---|---|
| Idée de départ | Séparer données et traitements | Partir des besoins des acteurs (cas d'utilisation) |
| Modélisation des données | Directe et précise (MCD → MLD → MPD) | Par le diagramme de classes |
| Modélisation du comportement | Limitée | Complète (cas d'utilisation, séquences) |
| Compatibilité avec la programmation orientée objet | Faible | Totale (conçu pour elle) |
| Usage | Surtout francophone | Standard mondial |

**2. Choix retenu et justification**

Le projet adopte le **Processus Unifié appuyé sur UML**. Ce choix se justifie par trois raisons :

- **La cohérence avec l'outil de développement.** L'application est développée avec Laravel, un framework orienté objet : chaque table de la base de données correspond à une classe (un « modèle »). Le diagramme de classes UML se traduit donc directement dans le code, sans étape de conversion.
- **La place centrale des acteurs.** Le cahier des charges a défini deux acteurs (le ressortissant et l'administrateur) avec des besoins précis. Le Processus Unifié part justement des cas d'utilisation de chaque acteur, ce qui assure une continuité naturelle entre le cahier des charges et la conception.
- **L'avancée par étapes.** Le développement s'est fait par morceaux livrés les uns après les autres (d'abord l'import des données ANStat, puis la structure coutumière, puis l'enregistrement des ressortissants), ce qui correspond à la démarche par itérations du Processus Unifié.

**Limite de la méthode retenue :** UML ne décrit pas directement le passage des classes vers les tables de la base de données. Cette limite est comblée par les migrations de Laravel, qui font ce passage dans le code, et par les règles de gestion présentées au chapitre suivant.

Quatre diagrammes UML ont été retenus pour la modélisation : le diagramme de cas d'utilisation (le périmètre fonctionnel), le diagramme de séquence (les échanges), le diagramme de classes (la structure des données) et le diagramme de déploiement (l'architecture technique).

---

## CHAPITRE IV — MODÉLISATION

### I. Diagramme de cas d'utilisation

**1. Définition**

Le diagramme de cas d'utilisation montre qui utilise le système et pour quoi faire. Chaque « cas d'utilisation » représente une action que l'application rend possible pour un acteur donné. C'est le premier schéma de la conception, car il traduit directement les besoins fonctionnels du cahier des charges.

**2. Les acteurs**

Deux acteurs interagissent avec l'application, tels que définis dans le cahier des charges :
- le **ressortissant**, qui crée son compte et remplit lui-même sa fiche de recensement ;
- l'**administrateur**, qui gère les données de référence (import ANStat, structure coutumière), les comptes, et peut enregistrer ou corriger une fiche au besoin.

Toutes les actions sensibles exigent d'être connecté : le cas d'utilisation « S'authentifier » est donc relié aux autres par une relation « include » (il est obligatoire avant d'agir).

**3. Diagramme global des cas d'utilisation**

*[Figure X — Diagramme de cas d'utilisation global, à dessiner]*

Contenu du schéma à dessiner :
- **Ressortissant** → Créer un compte · Remplir sa fiche de recensement · Consulter sa fiche
- **Administrateur** → Importer les données ANStat · Gérer la structure coutumière (cantons, tribus, villages) · Gérer les comptes · Enregistrer ou corriger une fiche
- Tous les cas (sauf « Créer un compte ») pointent vers **« S'authentifier »** avec une flèche « include ».

**4. Description textuelle du cas d'utilisation « Remplir sa fiche de recensement »**

| | |
|---|---|
| **Acteur** | Ressortissant |
| **Description** | Permettre à un ressortissant connecté de remplir sa fiche de recensement, avec son rattachement administratif, sa résidence et, s'il le connaît, son rattachement coutumier. |
| **Précondition** | Le ressortissant a créé un compte et est connecté. Les données ANStat ont été importées. |

*Scénario nominal :*
1. Le ressortissant ouvre sa fiche de recensement.
2. Le système affiche le formulaire à remplir.
3. Le ressortissant saisit son identité (nom, prénom, téléphone, sexe, date et lieu de naissance, famille).
4. Il choisit son rattachement administratif en cascade : le district, puis la région, puis le département, puis la sous-préfecture.
5. Il choisit, s'il le connaît, son rattachement coutumier : le canton, puis la tribu, puis le village (cette étape est facultative).
6. Il renseigne son lieu de résidence (pays, ville, quartier, adresse).
7. Il valide le formulaire.
8. Le système vérifie les données et enregistre la fiche.
9. Le système affiche la fiche créée avec un message de succès.

*Scénarios alternatifs :*
- 5.a — Le ressortissant ne connaît pas son rattachement coutumier → il laisse ces champs vides et poursuit ; le rattachement pourra être ajouté plus tard.
- 7.a — Un champ obligatoire est vide → le système affiche un message d'erreur et revient à la saisie.
- 7.b — Le numéro de téléphone existe déjà → le système signale un possible doublon.
- 4.a — Les listes administratives sont vides (import non effectué) → le système affiche un message d'indisponibilité et invite à réessayer plus tard.

| | |
|---|---|
| **Post-condition** | La fiche du ressortissant est enregistrée avec son rattachement administratif, sa résidence et, s'il est connu, son rattachement coutumier. |

### II. Diagrammes de séquence

Le diagramme de séquence complète le cas d'utilisation : il montre, dans l'ordre, les échanges de messages entre l'utilisateur et les différentes parties du système pour un scénario donné. Trois scénarios représentatifs ont été modélisés. Les participants sont l'**Utilisateur** (l'acteur), l'**Interface** (les pages affichées dans le navigateur), l'**Application** (la logique développée avec Laravel) et la **Base de données**.

**1. S'authentifier**

*[Figure X — Diagramme de séquence : S'authentifier, à dessiner]*

L'utilisateur saisit son identifiant et son mot de passe dans le formulaire de connexion. L'interface envoie ces informations à l'application, qui interroge la base de données pour vérifier le compte. Deux cas se présentent (fragment « alt ») : si les informations sont correctes, l'application ouvre la session et l'interface affiche la page d'accueil ; sinon, un message d'erreur est affiché et l'utilisateur reste sur la page de connexion.

**2. Importer les données ANStat**

*[Figure X — Diagramme de séquence : Importer les données ANStat, à dessiner]*

Ce scénario est particulier : il ne passe pas par le navigateur, mais par une commande lancée par l'administrateur. Les participants sont l'**Administrateur**, la **Commande d'import**, l'**API ANStat** (le service en ligne de l'agence) et la **Base de données**. L'administrateur lance la commande d'import. La commande interroge l'API de l'ANStat dans un ordre imposé par les dépendances entre niveaux : d'abord les districts, puis les régions, puis les départements, puis les sous-préfectures. Pour chaque niveau, une boucle (fragment « loop ») parcourt les pages de résultats tant que l'API en fournit. Chaque enregistrement est inséré ou mis à jour dans la base : si l'import est relancé, les données existantes sont mises à jour au lieu d'être dupliquées. À la fin, la commande affiche le nombre d'éléments importés.

**3. Remplir sa fiche de recensement**

*[Figure X — Diagramme de séquence : Remplir sa fiche de recensement, à dessiner]*

Le ressortissant ouvre sa fiche, et l'interface demande à l'application les listes nécessaires (districts, cantons…), lues depuis la base de données. À chaque choix, l'interface recharge la liste du niveau suivant (choisir un district charge ses régions, et ainsi de suite). Une fois le formulaire validé, l'application vérifie les données puis enregistre la fiche dans la base. Un fragment « alt » distingue deux cas : données valides (la fiche est créée et confirmée) ou données invalides (un message d'erreur est renvoyé, rien n'est enregistré).

### III. Diagramme de classes

**1. Définition**

Le diagramme de classes est la pièce centrale de la conception. Il représente les informations que le système stocke (les classes), leurs détails (les attributs) et les liens qui les unissent. Il se traduit directement dans le code : chaque classe devient un modèle Laravel et une table de la base de données.

**2. Choix de conception**

Deux choix structurent le modèle :

- **Le rattachement administratif passe par une seule flèche, vers la sous-préfecture.** Plutôt que de stocker quatre liens (district, région, département, sous-préfecture) sur la fiche du ressortissant, on n'en stocke qu'un : la sous-préfecture. Comme chaque sous-préfecture appartient déjà à un département, chaque département à une région et chaque région à un district, les trois niveaux supérieurs se retrouvent automatiquement. Stocker les quatre liens créerait un risque d'incohérence (une fiche indiquant un district et une sous-préfecture qui ne correspondent pas).
- **Le rattachement coutumier passe par une seule flèche facultative, vers le village.** La même logique s'applique : le village donne la tribu et le canton automatiquement. Cette flèche est facultative : un ressortissant qui ne connaît ni son canton, ni sa tribu, ni son village — cas qui existe réellement — laisse simplement cette partie vide, et sa fiche reste valide. Le rattachement pourra être ajouté plus tard.

**3. Règles de gestion**

| Code | Règle |
|---|---|
| RG01 | Un district regroupe une ou plusieurs régions ; une région appartient à un seul district. |
| RG02 | Une région regroupe un ou plusieurs départements ; un département appartient à une seule région. |
| RG03 | Un département regroupe une ou plusieurs sous-préfectures ; une sous-préfecture appartient à un seul département. |
| RG04 | Un canton regroupe une ou plusieurs tribus ; une tribu appartient à un seul canton. |
| RG05 | Une tribu regroupe un ou plusieurs villages ; un village appartient à une seule tribu. |
| RG06 | Un ressortissant est rattaché à une seule sous-préfecture (rattachement administratif, obligatoire) ; une sous-préfecture peut compter plusieurs ressortissants. |
| RG07 | Un ressortissant peut être rattaché à un village coutumier (facultatif) ; un village peut compter plusieurs ressortissants. |
| RG08 | Un ressortissant possède une résidence actuelle ; une résidence concerne un seul ressortissant. |
| RG09 | Un ressortissant est lié à au plus un compte utilisateur (auto-recensement) ; un compte correspond à au plus un ressortissant. |
| RG10 | Une fiche peut être créée ou corrigée par l'administrateur au besoin. |
| RG11 | Un village coutumier peut être situé dans une sous-préfecture (lien facultatif, car les liens entre les deux structures sont historiques et non systématiques). |

**4. Les classes du système**

*[Figure X — Diagramme de classes, à dessiner]*

Le système s'organise autour de neuf classes :
- **District** (code, nom, année), **Région**, **Département**, **SousPréfecture** : la structure administrative, importée de l'ANStat ;
- **Canton** (nom), **Tribu** (nom), **Village** (nom) : la structure coutumière, gérée par l'administrateur ;
- **Ressortissant** (nom, prénom, téléphone, sexe, date de naissance, lieu de naissance, famille) : l'entité centrale, reliée à sa sous-préfecture (obligatoire, RG06), à son village coutumier (facultatif, RG07) et à sa résidence (RG08) ;
- **Résidence** (pays, ville, quartier, adresse complète) ;
- **Utilisateur** (nom, email, mot de passe, rôle : ressortissant ou administrateur) : les comptes de connexion.

### IV. Diagramme de déploiement

**1. Définition**

Le diagramme de déploiement montre l'architecture physique du système : sur quelles machines les différentes parties de l'application s'exécutent, et comment elles communiquent entre elles.

**2. Architecture de déploiement**

*[Figure X — Diagramme de déploiement, à dessiner]*

L'architecture s'organise autour de quatre nœuds :
- le **poste de l'utilisateur**, qui accède à l'application depuis un navigateur web (ordinateur ou téléphone) ;
- le **serveur web**, qui héberge l'application Laravel : il reçoit les demandes du navigateur en HTTPS, applique la logique du système et prépare les pages affichées ;
- le **serveur de base de données**, qui stocke toutes les données (structures administrative et coutumière, ressortissants, résidences, comptes) et communique avec l'application par des requêtes SQL ;
- l'**API ANStat**, un service externe en ligne : l'application l'interroge en HTTPS uniquement lors de l'import des données administratives.

Cette organisation sépare clairement l'affichage, la logique et les données. Elle permet aussi de faire évoluer chaque partie indépendamment — par exemple changer de serveur de base de données sans toucher à l'application.

---

*Transition*

*Cette deuxième partie a transformé les besoins du cahier des charges en une conception complète. La méthode d'analyse a d'abord été choisie et justifiée : le Processus Unifié, appuyé sur le langage UML, en cohérence avec l'outil de développement orienté objet du projet. La modélisation a ensuite précisé le système sous tous ses angles : les cas d'utilisation des deux acteurs, les échanges internes pour les scénarios clés — dont l'import des données ANStat —, la structure des données à travers onze règles de gestion et neuf classes, et enfin l'architecture de déploiement. Tous les plans sont posés ; il reste à construire. La troisième partie présente la mise en œuvre concrète de cette conception : l'environnement de développement, les résultats obtenus, leur discussion et les perspectives d'évolution.*
