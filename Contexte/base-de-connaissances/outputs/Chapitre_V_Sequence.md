# 2. Diagrammes de séquence

Les diagrammes de séquence complètent la modélisation des cas d'utilisation en expliquant la succession précise des échanges de messages entre les composants du système pour les scénarios les plus représentatifs. Les trois diagrammes présentés reposent sur les mêmes quatre participants fondamentaux : l'Utilisateur (acteur externe), l'Interface Client / Frontend (interface utilisateur), l'API Backend (logique métier Laravel) et la Base de données (couche de persistance MySQL), auxquels s'ajoute l'API Externe ANStat pour la synchronisation du territoire national.

---

### 2.1 S'authentifier

Ce diagramme explique la procédure de vérification des accès, indispensable avant d'accéder aux fonctionnalités privées. L'utilisateur (ressortissant ou administrateur) entre son identifiant (numéro de téléphone ou email) et son mot de passe. L'interface transmet la demande au Backend via une requête HTTP POST. Le serveur interroge la base de données pour contrôler le compte et vérifier la correspondance du mot de passe crypté (`Hash::check`). Un fragment alternatif (*alt*) gère deux situations : si la combinaison est correcte, l'API génère un jeton d'accès sécurisé (Laravel Sanctum), ouvre la session et renvoie une confirmation avec les informations du profil ; en cas d'erreur, une alerte est transmise et l'accès reste refusé.

(image du diagramme : S'authentifier)

*Figure V.6 — Diagramme de séquence : S'authentifier*

---

### 2.2 Importer les données administratives nationales ANStat

Ce diagramme illustre le fonctionnement de l'importation automatique du découpage officiel de la Côte d'Ivoire. Déclenché par une commande système (`php artisan import:anstat`) lancée par l'administrateur, ce processus interroge l'API publique distante ANStat (`api-public.anstat.ci`) selon un ordre hiérarchique strict (districts, régions, départements, sous-préfectures). Une boucle d'itération (*loop*) parcourt les données JSON reçues et met à jour la base de données locale sans créer de doublons (`updateOrCreate`). À l'issue du traitement, le système comptabilise les 526 sous-préfectures et territoires associés, puis retourne un bilan complet de synchronisation.

(image du diagramme : Importer les données ANStat)

*Figure V.7 — Diagramme de séquence : Importer les données ANStat*

---

### 2.3 Remplir et enregistrer sa fiche de recensement

Ce diagramme décrit l'enregistrement d'une déclaration individuelle par un citoyen authentifié, véritable cœur de métier de la plateforme. Le ressortissant complète ses informations d'état civil, sélectionne ses territoires officiels par chargements successifs en cascade (chaque choix d'un territoire parent recharge dynamiquement la liste suivante), précise ses origines coutumières optionnelles ainsi que sa domiciliation (locale ou Diaspora). Lors de la soumission via une requête HTTP POST, le Backend applique les règles de validation métier. Un fragment alternatif (*alt*) distingue le cas où les données sont incomplètes ou erronées (retour d'un message d'erreur explicite sans sauvegarde en base) du cas conforme, où la fiche est insérée en base avec le statut initial « en attente » (`statut_validation = 'en_attente'`) et confirmée à l'usager.

(image du diagramme : Remplir sa fiche de recensement)

*Figure V.8 — Diagramme de séquence : Remplir et enregistrer sa fiche de recensement*
