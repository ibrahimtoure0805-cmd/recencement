# II. Diagrammes structurels

Les diagrammes structurels décrivent l'architecture statique du système, c'est-à-dire l'organisation de ses composants et leurs relations indépendamment du temps. Pour ce projet, la modélisation structurelle repose principalement sur le diagramme de classes et son tableau de règles de gestion associés.

## 1. Diagramme de classes

Le diagramme de classes constitue la pièce centrale de la modélisation structurelle. Il représente les entités métier du système, leurs attributs, leurs méthodes et les relations qui les unissent. Il sert de pont direct entre la conception et l'implémentation : chaque classe se traduit en un modèle Eloquent dans le backend Laravel et en une table dans la base de données MySQL. Le système est modélisé autour de dix classes principales : District, Region, Departement, SousPrefecture, Canton, Tribu, Village, Ressortissant, Pays et User.

### 1.1 Règles de gestion

Le tableau suivant récapitule l'ensemble des règles de gestion et des contraintes d'association qui régissent le modèle de données :

*Tableau V.2 — Règles de gestion issues du diagramme de classes*

| Code | Entités concernées | Multiplicité | Description explicative |
| :--- | :--- | :---: | :--- |
| **RG01** | District – Région | `1 / 1..*` | Un district rassemble une ou plusieurs régions administratives. Une région appartient obligatoirement à un seul district. |
| **RG02** | Région – Département | `1 / 1..*` | Une région regroupe un ou plusieurs départements. Un département dépend d'une seule région. |
| **RG03** | Département – Sous-préfecture | `1 / 1..*` | Un département comprend une ou plusieurs sous-préfectures. Une sous-préfecture est rattachée à un seul département. |
| **RG04** | Canton – Tribu | `1 / 0..*` | Un canton traditionnel regroupe plusieurs tribus. Une tribu appartient à un canton. |
| **RG05** | Tribu – Village | `1 / 1..*` | Une tribu rassemble un ou plusieurs villages coutumiers. Un village est rattaché à une tribu. |
| **RG06** | Ressortissant – Sous-préfecture | `1..* / 1` | Une sous-préfecture enregistre plusieurs ressortissants. Chaque ressortissant est obligatoirement lié à une sous-préfecture d'origine. |
| **RG07** | Ressortissant – Village | `0..* / 0..1` | Un ressortissant peut être relié à un village d'origine. Cette liaison est optionnelle pour éviter tout blocage à la saisie. |
| **RG08** | Ressortissant – Pays | `1..* / 1` | Chaque ressortissant réside dans un pays donné (Côte d'Ivoire ou territoire de la Diaspora). |
| **RG09** | Utilisateur – Ressortissant | `1 / 0..1` | Un compte utilisateur de type citoyen peut créer et gérer sa propre fiche de recensement. |
| **RG10** | Administrateur – Modération | `1 / 0..*` | Un administrateur peut modérer, valider ou rejeter plusieurs fiches de recensement. |

### 1.2 Diagramme de classe

L'architecture du diagramme de classes repose sur quatre choix fondamentaux garantissant la cohérence et l'évolution de la plateforme :

1. **Rattachement administratif en cascade optimisé** :  
   Pour éviter la redondance des données et éliminer les risques d'incohérence territoriale, la fiche de recensement pointe directement vers la **Sous-préfecture**. Les paliers administratifs englobants (Département, Région et District) sont déduits automatiquement par la relation d'appartenance de la pyramide administrative. Conserver quatre références distinctes séparés sur la fiche créerait un risque de contradiction (comme indiquer une sous-préfecture qui n'appartient pas au district sélectionné).

2. **Maillage coutumier et traditionnel souple** :  
   Le rattachement au **Village** traditionnel suit le même principe d'héritage (le village détermine la Tribu et le Canton). Cependant, cette relation est définie comme optionnelle ($0..1$). Cette souplesse permet aux citoyens qui ignorent leur village d'origine exact d'enregistrer leur déclaration sans blocage. Le rattachement coutumier pourra être complété ultérieurement par mise à jour.

3. **Prise en compte de la Domiciliation et de la Diaspora** :  
   La localisation de résidence distingue les résidents du territoire national et les ressortissants installés à l'étranger. L'entité **Pays** permet de classifier instantanément la répartition géographique (locale ou internationale) et d'alimenter les cartographies statistiques.

4. **Contrôle d'accès et modération intégrée** :  
   Chaque déclaration est reliée à un compte **Utilisateur** et dispose d'un statut d'évaluation (`en_attente`, `valide`, `rejete`). Ce mécanisme assure la traçabilité de la saisie et permet aux administrateurs de modérer le contenu avant toute comptabilisation officielle.

---

(image du diagramme : Diagramme de classes)

*Figure V.9 — Diagramme de classes du système de recensement*
