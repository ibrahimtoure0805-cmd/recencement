# TROISIÈME PARTIE : RÉSULTATS ET DISCUSSION

> Rédigée avec Ibrahim à partir du 08/07/2026. ⚠️ Le projet est encore en développement : cette partie contient le RÉEL (environnement, import ANStat vérifié le 01/07/2026) et des emplacements marqués **[À COMPLÉTER]** pour ce qui n'existe pas encore. Ne jamais remplir un [À COMPLÉTER] sans résultat réel constaté.
> Statut : Chapeau ✅ · Chapitre V : sections I-II rédigées (spécifications matérielles à fournir par Ibrahim) · Chapitre VI : squelette + premier résultat réel ; le reste [À COMPLÉTER].

---

*[Page intercalaire : TROISIÈME PARTIE : RÉSULTATS ET DISCUSSION]*

*[Chapeau]* La deuxième partie a posé les plans du système. Cette troisième partie entre dans le concret : elle décrit l'environnement dans lequel l'application a été développée, présente les résultats obtenus, les discute au regard des objectifs fixés dans le cahier des charges, et ouvre des perspectives d'évolution.

---

## CHAPITRE V — ENVIRONNEMENT DE TRAVAIL ET IMPLÉMENTATION

### I. Environnement de développement

**1. Ressources matérielles**

Le développement a été réalisé sur un ordinateur personnel dont les caractéristiques sont les suivantes :

| Caractéristique | Détail |
|---|---|
| Processeur | **[À COMPLÉTER — voir Paramètres Windows > Système > Informations système]** |
| Mémoire vive (RAM) | **[À COMPLÉTER]** |
| Stockage | **[À COMPLÉTER]** |
| Système d'exploitation | Windows 11 |

**2. Ressources logicielles**

L'environnement logiciel du projet repose sur des outils gratuits et open source :

| Outil | Rôle dans le projet |
|---|---|
| **PHP 8.3** | Langage de programmation principal de l'application. |
| **Laravel 13** | Framework PHP qui structure l'application : modèles de données (Eloquent), migrations de la base, commandes, authentification. |
| **SQLite** | Base de données relationnelle, stockée dans un simple fichier, adaptée au développement. |
| **Laravel Herd** | Environnement de développement local pour Windows : il fournit PHP et le serveur web sans installation complexe. |
| **Visual Studio Code** | Éditeur de code utilisé pour tout le développement. |
| **Postman** | Outil de test d'API, utilisé pour explorer et comprendre l'API publique de l'ANStat avant d'écrire le code d'import. |
| **DB Browser for SQLite** | Outil d'inspection visuelle de la base de données, utilisé pour vérifier les données importées. |
| **Pest** | Outil de tests automatisés intégré à Laravel. |

### II. Implémentation réalisée : l'import des données ANStat

La première brique développée est l'import du découpage administratif officiel, conformément au premier objectif spécifique du projet.

**1. La structure en base de données**

Quatre tables ont été créées par migrations : `districts`, `regions`, `departements` et `sous_prefectures`. Les liens entre elles utilisent les codes officiels de l'ANStat (et non des numéros internes), ce qui permet de comparer directement la base avec les publications officielles. Une particularité réelle des données a dû être gérée : le code d'une sous-préfecture n'est unique qu'à l'intérieur de son département (deux sous-préfectures de départements différents peuvent porter le même code). L'identifiant fourni par l'API de l'ANStat a donc été retenu comme identifiant unique des sous-préfectures.

**2. La commande d'import**

Une commande dédiée, `anstat:import`, a été développée. Elle interroge l'API publique de l'ANStat et remplit la base en respectant l'ordre des dépendances : les districts d'abord, puis les régions, les départements et enfin les sous-préfectures. Elle gère deux particularités de l'API découvertes lors de l'exploration avec Postman : les réponses sont paginées (la commande suit les pages jusqu'à la dernière), et les méthodes d'accès diffèrent selon les points d'accès. Enfin, l'import est **rejouable** : s'il est relancé, les données existantes sont mises à jour au lieu d'être dupliquées, conformément au cahier des charges.

**3. [À COMPLÉTER] La structure coutumière**

*(migrations et modèles Canton, Tribu, Village — non encore développés)*

**4. [À COMPLÉTER] L'enregistrement des ressortissants et l'authentification**

*(fiche du ressortissant, résidence, comptes et rôles — non encore développés)*

---

## CHAPITRE VI — PRÉSENTATION DES RÉSULTATS ET DISCUSSION

### I. Résultats obtenus

**1. Import complet du découpage administratif officiel**

L'import a été exécuté puis vérifié le premier juillet deux mille vingt-six : les comptes en base de données correspondent exactement aux totaux officiels publiés par l'ANStat.

| Niveau | Nombre en base | Total officiel ANStat |
|---|---|---|
| Districts | 14 | 14 |
| Régions | 33 | 33 |
| Départements | 111 | 111 |
| Sous-préfectures | 526 | 526 |

Ce résultat vérifie l'hypothèse selon laquelle la récupération automatique des données de l'ANStat garantit un découpage administratif fiable, sans ressaisie manuelle : l'intégralité du découpage officiel est en base, et l'import peut être relancé à tout moment pour suivre une mise à jour officielle.

**2. [À COMPLÉTER] Structure coutumière opérationnelle**

**3. [À COMPLÉTER] Enregistrement des ressortissants (avec captures d'écran)**

**4. [À COMPLÉTER] Authentification et comptes**

### II. Discussion

**[À COMPLÉTER — à rédiger quand les résultats seront complets. Contenu prévu par le cours : confronter les résultats aux objectifs du cahier des charges (tableau atteint / partiel / non atteint), aux hypothèses de départ et à l'étude de l'existant ; arguments appuyés sur des faits.]**

### III. Estimation financière

**[À COMPLÉTER — piste réelle déjà connue : le projet n'utilise que des outils gratuits et open source ; les coûts se limitent au matériel, à la connexion internet et au temps de développement. Chiffrer en fin de projet, comme les exemples (coûts d'investissement et d'exploitation).]**

### IV. Limites et perspectives

**[À COMPLÉTER — pistes déjà identifiées en cours de rédaction, à confirmer :**
- **l'application suppose que le ressortissant sait lire et utiliser un outil numérique ; une aide à l'enregistrement (par exemple un rôle d'assistance à la saisie) constitue une évolution future possible ;**
- **le rattachement coutumier passe par le village : une personne ne connaissant que son canton ne peut pas encore l'enregistrer ;**
- **le projet ne modélise que trois des cinq niveaux coutumiers reconnus par la loi n° 2014-428.]**

---

*[Conclusion générale du mémoire : à rédiger en toute fin, quand les résultats seront complets — bilan, réponse à la problématique, perspectives.]*
