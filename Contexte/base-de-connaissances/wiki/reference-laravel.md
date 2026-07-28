# Référence Laravel

> _Sources : `raw/documentation de Laravel/` (doc officielle Laravel 13), `raw/Laravel codeur.md`, `raw/Laravel Expert.md`, `raw/code-commenter_1.md`._
> Page liée : [projet-recensement.md](projet-recensement.md)

## Doc officielle (non recopiée)
La doc officielle complète est dans `raw/documentation de Laravel/` (≈100 fichiers, déjà organisés). Pas de recopie ici : on pointe directement vers les fichiers utiles **pour ce projet** (API + import en base).

| Besoin du projet | Fichier source |
|---|---|
| Commande Artisan (import en base) | `documentation de Laravel/artisan.md` |
| Migrations (créer les tables) | `documentation de Laravel/migrations.md` |
| Modèles & relations Eloquent | `documentation de Laravel/eloquent.md`, `eloquent-relationships.md` |
| Seeders / remplissage initial | `documentation de Laravel/seeding.md` |
| Appeler l'API ANStat (HTTP) | `documentation de Laravel/http-client.md` |
| Exposer notre propre API | `documentation de Laravel/eloquent-resources.md`, `controllers.md`, `routing.md` |
| Validation des données importées | `documentation de Laravel/validation.md` |
| Tâches longues en file d'attente | `documentation de Laravel/queues.md` |

## Conventions de code à respecter
_Source : `Laravel codeur.md` (skill laravel-coder)._
- `declare(strict_types=1)` dans tous les fichiers PHP ; type hints complets ; comparaisons `===`.
- Créer les fichiers via `php artisan make:` (+ `--no-interaction`), jamais à la main.
- **Eloquent** : préférer `Model::query()` à `DB::` ; éviter le N+1 avec `with()` / `withCount()`.
- Ne jamais accéder à `$model->id` directement → utiliser `$model->getKey()`.
- Casts dans une méthode `casts()` du modèle.
- API : utiliser les **API Resources** Eloquent + versionnage.
- Validation : **Form Request** dédiées, pas de validation inline dans le contrôleur.
- Config : `config('app.name')`, jamais `env()` hors des fichiers de config.
- Opérations longues : jobs `ShouldQueue`.
- Tests : surtout des feature tests, via factories.

## Posture attendue de l'assistant Laravel
_Source : `Laravel Expert.md` (skill laravel-specialist)._
Workflow : analyser les besoins → concevoir le schéma → modèles Eloquent → contrôleurs/services/resources/jobs → tests. Sortie type : modèle + migration + resource + service + test + brève justification des choix.

## Commenter le code
_Source : `code-commenter_1.md` (skill code-commenter)._
Pour documenter du code : commentaires en français, schéma « Ce code sert à… / il fonctionne avec… / dans le but de… / pour régler… ». N'ajoute QUE des commentaires, ne modifie jamais la logique.
