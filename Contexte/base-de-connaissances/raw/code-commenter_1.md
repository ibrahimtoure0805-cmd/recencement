---
name: code-commenter
description: Ajoute des commentaires explicatifs détaillés à du code, en français, en suivant le schéma "Ce code sert à... / il fonctionne avec... / dans le but de... / pour régler...". Couvre plusieurs langages (Python, JavaScript/TypeScript, C/C++, Java, etc.). À déclencher dès que l'utilisateur veut commenter du code, expliquer un bloc, une boucle ou une ligne complexe, documenter un script, rendre du code lisible/compréhensible, ou demande "commente ce code", "explique ce bloc", "ajoute des commentaires", "documente cette fonction". Utiliser même si l'utilisateur colle simplement du code en disant juste "commente".
---

# Code Commenter

Ajoute des commentaires explicatifs en français à du code existant, sans modifier la logique.

## Règle absolue

Ne JAMAIS modifier le code lui-même : ni renommer, ni réécrire, ni corriger, ni reformater. On ajoute UNIQUEMENT des commentaires. Le code doit rester strictement exécutable à l'identique. Si tu repères un bug, le signaler dans un commentaire `# ATTENTION:` mais ne pas le corriger sans accord.

## Niveaux de commentaire

Trois granularités, à choisir selon la complexité de l'élément :

### 1. Commentaire de bloc (au-dessus d'une fonction, classe, ou section logique)

Format détaillé en 4 axes :
- **Ce code sert à** : le rôle / l'objectif du bloc
- **Il fonctionne avec** : les entrées, dépendances, données ou variables utilisées
- **Dans le but de** : le résultat attendu / la finalité
- **Pour régler** : le problème concret que ça résout (omettre cette ligne si elle n'apporte rien)

### 2. Commentaire de boucle (au-dessus de chaque `for`/`while`/compréhension non triviale)

Expliquer : ce qu'on parcourt, ce qu'on fait à chaque tour, et la condition d'arrêt.

### 3. Commentaire de ligne complexe (en fin de ligne ou juste au-dessus)

Pour une ligne dense (regex, slicing, opération chaînée, calcul peu lisible) : une phrase courte décrivant ce qu'elle produit.

## Ce qu'il NE faut PAS commenter

Éviter le bruit. Pas de commentaire sur les lignes évidentes (`i = 0  # initialise i à 0`). Commenter seulement ce qui demande une explication.

## Syntaxe par langage

| Langage | Ligne | Bloc |
|---|---|---|
| Python | `# ...` | `# ...` répété (pas de docstring sauf demande) |
| JS/TS | `// ...` | `/* ... */` ou `//` répété |
| C/C++/Java | `// ...` | `/* ... */` |
| Shell/Bash | `# ...` | `# ...` |
| HTML | — | `<!-- ... -->` |
| CSS | `/* ... */` | `/* ... */` |

## Exemple (Python)

```python
# Ce code sert à filtrer une liste d'utilisateurs pour ne garder que les actifs.
# Il fonctionne avec une liste de dictionnaires "users" contenant une clé "last_login".
# Dans le but de produire une liste prête à afficher dans le tableau de bord.
# Pour régler le problème des comptes inactifs qui polluaient l'affichage.
def filtrer_actifs(users, seuil_jours=30):
    limite = datetime.now() - timedelta(days=seuil_jours)
    resultat = []
    # Parcourt chaque utilisateur pour tester sa date de dernière connexion
    for u in users:
        # Convertit la chaîne ISO en objet date, puis compare à la limite calculée
        if datetime.fromisoformat(u["last_login"]) >= limite:
            resultat.append(u)
    return resultat
```

## Processus

1. Identifier le langage (extension ou syntaxe).
2. Repérer les blocs (fonctions/classes), les boucles, et les lignes complexes.
3. Ajouter les commentaires aux bons endroits selon les 3 niveaux.
4. Renvoyer le code commenté en entier, dans un bloc de code, sans rien changer d'autre.
5. Ne pas ajouter de commentaire de fin de réponse expliquant ce qui a été fait — le code parle de lui-même.
