# Réponses aux 10 questions du sujet de stage

> Sujet : « Concevoir une application web de recensement des ressortissants ivoiriens : structure ANStat et structure coutumière. »
>
> Version validée avec Ibrahim le 01/07/2026, question par question, en langage simple. S'appuie sur le [cours de méthodologie](../wiki/methodologie-memoire.md) et sur des recherches web vérifiées (RGPH, RNPP, Cityweb — voir sources en Q4). L'état de l'art complet reste à rédiger.

## Résolution de l'exercice

### **1. Identification de l'objet d'étude**

L'objet d'étude est **Relier chaque ressortissant ivoirien, au moment de son recensement, à sa place dans l'administration officielle (ANStat : district, région, département, sous-préfecture) et à sa place dans la structure coutumière (canton, tribu, village)**

* **Justification :** L'objet d'étude n'est pas le titre du sujet, et ce n'est pas non plus l'application qu'on va construire — l'application web est juste l'outil. Ce qu'on étudie vraiment, c'est l'action de relier une personne à ses deux appartenances (officielle et traditionnelle) au moment où on la recense. Si on confond l'objet d'étude avec l'outil (l'appli) ou avec le titre, on répond à côté

### **2. Question de recherche**

**« Comment concevoir une application web qui recense les Ivoiriens en tenant compte à la fois de leur rattachement officiel (ANStat) et de leur rattachement coutumière (canton, tribu, village) ? »**

* **Justification :** La question de recherche, c'est la grande question à laquelle tout le mémoire va essayer de répondre. Elle doit reprendre l'idée de l'objet d'étude (relier les deux rattachements) et la tourner en question précise, pas trop large, pas trop étroite

### **3. Objectifs de l'analyse**

* **Objectif général :**  Concevoir et développer une application web qui recense les ressortissants ivoiriens en les rattachant à la fois à la structure ANStat et à la structure coutumière
* **Objectifs spécifiques :** 
1. Récupérer les données officielles de l'ANStat (district, région, département, sous-préfecture) et les enregistrer dans la base de données. 
2. Construire la structure coutumière (canton, tribu, village) dans la base de données, sans l'obliger à dépendre de la structure administrative.
3. Créer la fiche du ressortissant qui relie chaque personne à ses deux rattachements et à son lieu de résidence.
4. Mettre en place la connexion (authentification) et l'enregistrement des ressortissants dans l'application.

* **Justification :**  Les objectifs disent ce qu'on veut atteindre à la fin du travail. L'objectif général donne le but d'ensemble, et les objectifs spécifiques le découpent en étapes concrètes — ces étapes vont correspondre aux grandes parties du développement (import des données, base de données, application).

### **4. Problématique**

Constater qu'en Côte d'Ivoire, plusieurs systèmes s'occupent déjà de recenser ou d'identifier la population, mais chacun séparément et pour un objectif différent : le RGPH (mené par l'ANStat) compte les habitants par zone administrative, et le RNPP/Cityweb (gérés par l'ONECI) enregistrent l'état civil et attribuent un numéro national d'identification. Relever qu'aucun de ces systèmes n'enregistre le rattachement coutumier d'une personne (son canton, sa tribu, son village traditionnel), qui reste géré de façon informelle, sans registre numérique. Ce vide empêche de recenser un Ivoirien de façon complète, en tenant compte à la fois de son identité administrative et de son identité coutumière.

* **Justification :**  Une bonne problématique montre un manque réel dans ce qui existe déjà. Ici, le manque est vérifié : trois systèmes réels (RGPH, RNPP, Cityweb) existent, mais aucun ne touche à la structure coutumière — c'est exactement le vide que ce projet veut combler.

### **5. Hypothèses de travail**

* **Hypothèse 1 :** Représenter, dans une seule base de données, le rattachement administratif (ANStat) et le rattachement coutumier (canton, tribu, village) de chaque ressortissant, grâce à des relations séparées et non obligatoires entre les deux structures, permet de recenser une personne de façon complète.

* **Hypothèse 2 :** Récupérer automatiquement les données de l'ANStat via leur API garantit un découpage administratif toujours à jour, sans ressaisie manuelle et sans erreur de copie.

* **Hypothèse 3 :** Séparer le lieu de résidence actuel du rattachement d'origine (administratif et coutumier) permet aussi de recenser les Ivoiriens de la diaspora, qui vivent ailleurs mais gardent leurs deux rattachements.

* **Justification :** Une hypothèse, c'est une réponse qu'on pense vraie avant d'avoir fini le travail, et qu'on va vérifier en construisant l'application. Chaque hypothèse ici correspond à une partie concrète du travail : la base de données, l'import ANStat, et la gestion de la diaspora.

### **6. Méthodologie de travail**

La méthodologie s'appuiera sur :

1. **L'analyse documentaire :** Étudier les documents disponibles sur le projet : la fiche de spécification, les données de l'ANStat, l'organisation coutumière (canton, tribu, village).

2. **L'étude de l'existant :** Comparer les systèmes qui existent déjà (RGPH, RNPP/ONECI, Cityweb, outils de collecte CSPro, ODK, KoboToolbox) pour voir ce qu'ils font et ce qu'il leur manque 

3. **La méthode de conception :** Concevoir la base de données avec une méthode reconnue (Merise ou UML) pour représenter les deux structures et le ressortissant.

4. Développer l'application avec le framework Laravel (langage PHP), en avançant étape par étape : import des données ANStat, création des modèles, authentification, enregistrement des ressortissants.

* **Justification :** La méthodologie, c'est la liste des méthodes et outils utilisés pour répondre à la question de recherche. Elle doit rester cohérente avec les objectifs (Q3) et les hypothèses (Q5) — ici, chaque étape de la méthodologie correspond à un objectif ou une hypothèse déjà posée.

### **7. Résultats probables et livrables par partie**

* **Partie 1 (Généralités) :** Produire un cahier des charges détaillé du projet et un état de l'art sur les systèmes de recensement et d'identification déjà existants (RGPH, RNPP, Cityweb)

* **Partie 2 (Matériels et Méthodes) :**  Produire le modèle de données (structure ANStat, structure coutumière, ressortissant, résidence) et l'architecture technique de l'application.

* **Partie 3 (Résultats et Discussion) :** Produire une application fonctionnelle (import des données ANStat, structure coutumière, enregistrement des ressortissants, authentification), accompagnée d'un rapport de tests et d'une estimation financière du projet.

* **Justification :** Les résultats probables doivent découler des hypothèses (Q5) et suivre le découpage en trois parties imposé par le cours de méthodologie. Chaque partie du mémoire doit donner un livrable concret et vérifiable.


### **8. Intérêts de l'étude**

* **Intérêt académique :** Valider les compétences acquises en Licence DASI (analyse, conception de base de données, développement web avec Laravel) à travers une application réelle.
* **Intérêt sociétal :** Faciliter un recensement complet des Ivoiriens, y compris ceux de la diaspora, en reconnaissant leur ancrage coutumier autant que leur statut administratif.
* **Intérêt scientifique :** Proposer un modèle de données qui relie deux structures territoriales non hiérarchiques (administrative et coutumière), un aspect peu traité dans les systèmes existants comme le RGPH ou le RNPP.
* **Justification :**  L'intérêt académique montre le lien avec la formation, l'intérêt sociétal montre l'utilité pour les gens, et l'intérêt scientifique montre ce que le travail apporte de nouveau par rapport à ce qui existe déjà (Q4).

### **9. Contexte du sujet (6 lignes)**

La Côte d'Ivoire modernise progressivement ses outils de recensement et d'identification. L'ANStat réalise le RGPH pour compter la population par zone administrative, et l'ONECI gère l'état civil avec le RNPP et le logiciel Cityweb. Pourtant, aucun de ces systèmes n'enregistre le rattachement coutumier d'un Ivoirien (son canton, sa tribu, son village), qui reste géré de façon informelle. Ce projet propose de concevoir une application web qui relie, pour chaque ressortissant, son rattachement administratif et son rattachement coutumier. Il s'appuie sur les données réelles de l'ANStat et sur l'organisation traditionnelle reconnue en Côte d'Ivoire. Il s'inscrit ainsi dans une démarche de modernisation qui respecte à la fois l'administration et la culture ivoirienne.

* **Justification :** Le contexte doit amener le sujet en montrant son importance et son actualité. Ici, on part de ce qui existe déjà (RGPH, ONECI) pour arriver au vide qu'on veut combler.

### **10. Chronogramme de travail (3 mois)**

Période	Activités
Mois 1:	Étudier les documents et l'existant (RGPH, RNPP, Cityweb) ; rédiger le cahier   des charges et l'état de l'art ; récupérer et analyser les données de l'ANStat.
Mois 2:	Concevoir la base de données (Merise/UML) pour les deux structures ; développer l'import ANStat, la structure coutumière, l'authentification et l'enregistrement des ressortissants.
Mois 3:	Tester et corriger l'application ; rédiger le mémoire de fin de cycle ; préparer la soutenance.


* **Justification :** Le chronogramme répartit les trois grandes phases du travail (recherche, conception/développement, finalisation) sur les trois mois, dans le même ordre que les trois parties du mémoire.
