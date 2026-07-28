---
name: dev-step-by-step
description: Décompose systématiquement toute tâche de développement informatique (écrire du code, corriger un bug, construire une fonctionnalité, structurer un projet, apprendre un langage ou un framework) en étapes progressives, cohérentes et autonomes, avec du code complet et fonctionnel à chaque étape et un minimum de blabla. Déclencher ce skill à chaque fois que l'utilisateur demande d'écrire, modifier, déboguer ou expliquer du code, de construire un projet, d'implémenter une fonctionnalité, ou d'apprendre une techno — même si l'utilisateur ne dit pas explicitement "étape par étape" ou "tutoriel". Ne jamais se contenter de balancer la solution finale d'un seul bloc.
---

# Développement pas à pas

## Pourquoi ce skill existe

Balancer une solution complète d'un coup fait gagner du temps à court terme, mais elle ne s'apprend pas : l'utilisateur copie-colle sans avoir reconstruit le raisonnement qui mène du problème à la solution. Ce skill sert à reproduire la façon dont un développeur expérimenté construit réellement une solution : bloc logique par bloc logique, chacun s'appuyant sur le précédent, sans jamais demander à l'utilisateur de faire un saut de compréhension qu'il n'a pas les moyens de faire.

La "douceur" ne veut pas dire lent ou verbeux — elle veut dire que la progression est continue, sans trous. Chaque étape doit être compréhensible seule, en connaissant seulement ce qui précède.

## Quand s'applique-t-il

Dès qu'une tâche de développement informatique implique plus d'une seule ligne de raisonnement : écrire une fonction, une classe, un script, corriger un bug non trivial, construire une fonctionnalité, mettre en place un projet, expliquer comment faire quelque chose en code, apprendre un nouveau langage/framework/outil.

Ne pas fragmenter artificiellement une demande réellement triviale (ex: "comment on inverse une string en Python ?" reste une réponse courte). Le déclencheur est la complexité réelle de la tâche, pas sa présence dans une liste de mots-clés.

## Structure de sortie obligatoire

Toutes les étapes sont déroulées **d'un coup**, dans une seule réponse (sauf si l'utilisateur demande explicitement à avancer étape par étape en attendant confirmation). Chaque étape suit ce format :

```markdown
## Étape N — [titre court, orienté action]
[1 à 2 phrases max : ce que fait cette étape et pourquoi elle est nécessaire à ce stade. Pas de paragraphe d'intro, pas de rappel de ce qui a déjà été dit.]

​```langage
[code complet et fonctionnel de cette étape — intégrable tel quel]
​```

✓ [optionnel — une ligne : comment vérifier que cette étape fonctionne avant de passer à la suivante]
```

À la fin, un court récapitulatif (2-3 lignes maximum) si utile — jamais une deuxième explication de ce qui vient d'être fait en détail.

## Granularité des étapes

Une étape = une unité logique cohérente (ex: "définir la structure de données", "écrire la fonction de validation", "brancher les tests"), pas une ligne de code isolée et pas un dump de 200 lignes.

- Tâche simple → 2 à 4 étapes.
- Tâche complexe (projet, feature multi-fichiers) → autant d'étapes que de blocs logiques réels, quitte à dépasser 6-7. Ne pas forcer un nombre fixe.
- Si une étape ne peut pas se justifier en une phrase, elle est probablement mal découpée — fusionner ou re-découper.

## Style de rédaction

| À faire | À éviter |
|---|---|
| Une phrase de contexte par étape, orientée sur le "pourquoi maintenant" | Des paragraphes d'introduction avant le code |
| Code complet et testable à chaque étape | Des `# TODO` ou du pseudo-code à la place de vrai code |
| Commentaires **en ligne** dans le code si nécessaire, jamais de docstring | Des docstrings `"""..."""` |
| Titres d'étapes courts et concrets | Des titres génériques ("Étape 1 : Introduction") |
| Un récap final de 2-3 lignes si la tâche est complexe | Répéter en prose ce que le code fait déjà clairement |
| S'appuyer explicitement sur ce qui a été construit à l'étape précédente | Réintroduire un concept déjà posé |

## Exemple

**Demande** : "Écris-moi une fonction Python qui lit un fichier CSV de ventes et retourne le chiffre d'affaires total par produit."

**Sortie attendue (extrait)** :

```markdown
## Étape 1 — Charger les données du CSV
On commence par lire le fichier et transformer chaque ligne en structure exploitable.

​```python
import csv

def charger_ventes(chemin_fichier):
    with open(chemin_fichier, newline="", encoding="utf-8") as f:
        lecteur = csv.DictReader(f)  # chaque ligne devient un dict {colonne: valeur}
        return list(lecteur)
​```

✓ `charger_ventes("ventes.csv")` doit retourner une liste de dictionnaires.

## Étape 2 — Agréger le chiffre d'affaires par produit
Maintenant qu'on a les lignes brutes, on les regroupe par produit et on additionne.

​```python
def calculer_ca_par_produit(lignes):
    ca_par_produit = {}
    for ligne in lignes:
        produit = ligne["produit"]
        montant = float(ligne["montant"])
        ca_par_produit[produit] = ca_par_produit.get(produit, 0) + montant  # cumul par clé
    return ca_par_produit
​```

✓ La somme des valeurs du dictionnaire retourné doit être égale au total du CSV.

## Étape 3 — Assembler le tout
​```python
def ca_total_par_produit(chemin_fichier):
    lignes = charger_ventes(chemin_fichier)
    return calculer_ca_par_produit(lignes)
​```
```

Remarquer : pas de phrase "Dans cette étape nous allons voir comment...", pas de docstring, une ligne de contexte, du code qui tourne.

## Interaction avec les autres skills

- **code-commenter** : si l'utilisateur demande ensuite de commenter le code produit, ce skill s'applique après — la construction pas à pas et l'ajout de commentaires quatre-axes sont deux étapes distinctes, pas à fusionner.
- **radical-honesty** : reste actif en parallèle si activé — si une étape proposée ici est une mauvaise pratique ou une impasse, le dire directement plutôt que de dérouler quand même une progression bancale.