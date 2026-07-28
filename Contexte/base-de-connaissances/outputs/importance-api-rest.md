# Importance de l'API REST dans le projet Recensement

> Question posée par Ibrahim le 2026-07-06. Aucune source dans `raw/` ne traite ce point directement — synthèse construite à partir du sujet de stage et de l'architecture réellement implémentée (pas une extraction de source existante).
> Sources : [wiki/projet-recensement.md](../wiki/projet-recensement.md), [reponses-10-questions-sujet.md](reponses-10-questions-sujet.md), code du projet (`app/Http/Controllers/`, `routes/api.php`, `app/Console/Commands/ImportAnstat.php`).

## 1. C'est l'exigence même du sujet de stage

Le sujet énonce littéralement : *« Créer une API en Laravel »* ([projet-recensement.md:11](../wiki/projet-recensement.md)). Ce n'est pas un choix d'architecture secondaire — c'est la nature du livrable attendu par le cahier des charges.

## 2. Le projet applique REST des deux côtés

- **Consommateur** : `ImportAnstat` consomme l'API REST externe ANStat (pagination via `next`, JSON, codes de statut).
- **Fournisseur** : le projet expose sa propre API REST (7 contrôleurs, `routes/api.php`).

Mêmes concepts (ressources, verbes HTTP, codes de statut, pagination) appliqués dans les deux sens — cohérence architecturale de bout en bout, argument valorisable dans le mémoire.

## 3. Gère la séparation des deux structures indépendantes

Le cœur du sujet : ANStat et le coutumier sont deux hiérarchies **non liées hiérarchiquement**. REST, en désignant chaque hiérarchie comme des ressources distinctes (`/api/districts` vs `/api/cantons`), permet d'appliquer des règles d'accès différentes sans les mélanger :
- ANStat : lecture seule, 2 méthodes (`index`, `show`), la vraie source de vérité reste `anstat:import`.
- Coutumier : CRUD complet, 5 méthodes, car aucune API externe n'existe pour peupler ces données.

## 4. Multi-client, pertinent pour la diaspora

Hypothèse 3 du cadrage du sujet : la séparation résidence/rattachement permet de recenser les ressortissants de la diaspora ([reponses-10-questions-sujet.md](reponses-10-questions-sujet.md)). Une API REST découple le back-end de l'interface : un futur front web, une appli mobile pour la diaspora, ou un outil d'administration consulaire pourraient tous consommer la même API sans dupliquer la logique métier.

## 5. Testabilité démontrée concrètement

Vérifié dans ce projet, pas seulement en théorie :
- `Http::fake()` sur les tests de `ImportAnstat` (3 tests Feature).
- Tests HTTP réels (`Invoke-RestMethod`) sur les contrôleurs ANStat et coutumiers, y compris le cas d'erreur (409 sur suppression avec contrainte FK).

Un contrat REST clair (entrée/sortie JSON, codes de statut HTTP) se prête à l'automatisation des tests — bien plus difficile à obtenir avec un rendu de vue serveur classique. Alimente directement le « rapport de tests » attendu en Partie 3 du mémoire ([reponses-10-questions-sujet.md](reponses-10-questions-sujet.md)).

## 6. Extensible pour la suite du projet

`Ressortissant` et `Résidence` (pas encore développés) suivront le même moule que Canton/Tribu/Village : mêmes conventions de contrôleur REST, mêmes codes HTTP (ex. 409 déjà en place pour les contraintes FK), sans remise en cause de l'existant.
