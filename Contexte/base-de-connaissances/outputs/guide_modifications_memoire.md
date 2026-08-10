# 📜 Guide et Textes Exacts à Modifier dans `memoire fin.docx`

> **Document source** : `C:\Users\tieco\Desktop\herd Laravel\recensement\memoire fin.docx`  
> **Objet** : Guide pas à pas des modifications et textes exacts à copier/coller dans Word pour mettre à jour le mémoire selon l'application réelle.

---

## 1. CHAPITRE III — Cahier des Charges & Acteurs du Système

### 📌 Textes à remplacer dans le Tableau des Besoins Fonctionnels

#### A. Profil Ressortissant / Citoyen (Remplacer la section Usager)
> **Texte à insérer dans Word :**
> 
> * **S'inscrire et s'authentifier** : Créer un compte citoyen sécurisé et accéder à son espace personnel via son adresse e-mail ou son numéro de téléphone avec mot de passe.
> * **Remplir et mettre à jour sa fiche individuelle** : Saisir ses informations d'état civil (nom, prénom, sexe, date et lieu de naissance, profession), ses informations sociodémographiques (situation matrimoniale, niveau d'étude, statut d'occupation) et joindre les informations d'identité (`type_piece`, `numero_piece`).
> * **Dépôt des pièces justificatives** : Télécharger le scan ou la photo de sa pièce d'identité (`Scan / Photo Pièce d'Identité (PDF, JPG, JPEG, PNG)`). Télécharger de façon **optionnelle / facultative** un justificatif de domicile (`Justificatif de Domicile (PDF, JPG, JPEG, PNG) - Optionnel`), utile pour les chefs de ménage ou la Diaspora (non exigé pour les mineurs ou membres du foyer).
> * **Déclarer sa résidence et sa Diaspora** : Indiquer son adresse de résidence effective en Côte d'Ivoire ou à l'étranger. Pour les ressortissants de la Diaspora, renseigner le **Consulat de rattachement** et un **Contact référent local** (nom et téléphone).
> * **Rattachement administratif et coutumier** : Sélectionner la Sous-Préfecture de rattachement officiel (ANStat) et préciser, s'ils sont connus, le Canton, la Tribu et le Village d'origine coutumière.
> * **Suivre le statut de sa fiche** : Consulter l'état de modération de sa déclaration (`en_attente`, `valide` ou `rejete` avec motif expliquant la décision).

#### B. Profil Administrateur (Remplacer la section Administrateur)
> **Texte à insérer dans Word :**
> 
> * **Authentification sécurisée** : Se connecter à l'espace de gestion d'administration avec un compte administrateur protégé par jetons Sanctum.
> * **Modération des fiches de recensement** : Consulter la liste des fiches soumises, prévisualiser et télécharger directement les pièces d'identité et justificatifs joints, puis **Valider** la fiche ou la **Rejeter** en saisissant obligatoirement un motif explicatif pour le citoyen.
> * **Gestion du référentiel coutumier** : Effectuer la création, la modification et la suppression (CRUD) des Cantons, Tribus et Villages d'origine.
> * **Importation du découpage ANStat** : Contrôler et exécuter l'importation automatique du découpage administratif officiel (Districts, Régions, Départements, Sous-Préfectures) via la commande système CLI (`php artisan import:anstat`).
> * **Pilotage et statistiques** : Consulter le tableau de bord décisionnel interactif regroupant le volume national, la répartition de la Diaspora par pays et consulats, l'ancrage coutumier et la ventilation par type de pièce d'identité et niveau d'étude.

---

## 2. CHAPITRE V — Modélisation du Système (Diagrammes UML)

### 📌 Images et Descriptions des Diagrammes à Mettre à Jour dans Word

#### A. Figure V.1 : Diagramme Global des Cas d'Utilisation
* **Fichier Image à insérer dans Word** : [diagramme_global_cas_utilisation.drawio](file:///C:/Users/tieco/Desktop/herd%20Laravel/recensement/Contexte/base-de-connaissances/outputs/diagramme_global_cas_utilisation.drawio)
* **Texte descriptif à remplacer sous la Figure V.1 :**
> Le diagramme global des cas d'utilisation capture les interactions entre les trois acteurs principaux du système : le **Visiteur / Public** (exploration des référentiels et auto-inscription), le **Ressortissant / Citoyen** (authentification, renseignement de la fiche, dépôt des pièces justificatives CNI/Passeport, déclaration de domicile/Diaspora et suivi de modération) et l'**Administrateur** (authentification Sanctum, modération documentaire avec prévisualisation des pièces joints, gestion du référentiel coutumier et importation ANStat via CLI).

#### B. Figure V.2 : Diagramme du Module Portail Public
* **Fichier Image à insérer dans Word** : [diagramme_module_portail_public.drawio](file:///C:/Users/tieco/Desktop/herd%20Laravel/recensement/Contexte/base-de-connaissances/outputs/diagramme_module_portail_public.drawio)

#### C. Figure V.3 : Diagramme du Module Espace Ressortissant
* **Fichier Image à insérer dans Word** : [diagramme_module_espace_ressortissant.drawio](file:///C:/Users/tieco/Desktop/herd%20Laravel/recensement/Contexte/base-de-connaissances/outputs/diagramme_module_espace_ressortissant.drawio)
* **Texte descriptif à remplacer sous la Figure V.3 :**
> Le module Espace Ressortissant permet à tout citoyen ivoirien d'enregistrer et mettre à jour sa fiche individuelle. Il englobe la saisie de l'état civil, des informations sociodémographiques (situation matrimoniale, niveau d'étude, profession), le chargement du scan ou photo de sa pièce d'identité (CNI, Passeport, Carte consulaire), la jointure optionnelle d'un justificatif de domicile, la déclaration de sa domiciliation (locale ou Diaspora avec Consulat et contact référent) ainsi que le suivi en temps réel du statut de sa modération.

#### D. Figure V.4 : Diagramme du Module Administration
* **Fichier Image à insérer dans Word** : [diagramme_module_administration.drawio](file:///C:/Users/tieco/Desktop/herd%20Laravel/recensement/Contexte/base-de-connaissances/outputs/diagramme_module_administration.drawio)
* **Texte descriptif à remplacer sous la Figure V.4 :**
> Le module Administration permet aux gestionnaires du système de contrôler et valider les fiches transmises en inspectant les pièces d'identité et justificatifs joints. L'administrateur valide la conformité du dossier ou saisit un motif motivé en cas de rejet. Ce module permet également d'administrer l'arbre coutumier (Cantons, Tribus, Villages) et d'exécuter la commande d'importation CLI du référentiel ANStat (`php artisan import:anstat`).

#### E. Figure V.5 : Diagramme du Module Cartographie & Statistiques
* **Fichier Image à insérer dans Word** : [diagramme_module_cartographie_statistiques.drawio](file:///C:/Users/tieco/Desktop/herd%20Laravel/recensement/Contexte/base-de-connaissances/outputs/diagramme_module_cartographie_statistiques.drawio)

---

### 📌 Description Textuelle du Cas d'Utilisation « Enregistrer sa fiche de recensement »

> **Texte à remplacer dans la fiche textuelle du cas d'utilisation dans Word :**
> 
> * **Nom du cas d'utilisation** : Enregistrer sa fiche de recensement
> * **Acteur principal** : Ressortissant / Citoyen (utilisateur authentifié via son espace personnel).
> * **Description** : Permettre au citoyen d'enregistrer sa déclaration individuelle complète comprenant son état civil, son type et numéro de pièce d'identité, ses pièces justificatives jointes, sa situation sociodémographique, son rattachement administratif officiel (ANStat), ses origines coutumières et sa résidence (locale ou Diaspora avec Consulat).
> * **Préconditions** : L'usager est connecté à son compte. Les référentiels géographiques sont chargés.
> 
> **Scénario Nominal :**
> 1. L'acteur clique sur « Remplir ma fiche » depuis son espace citoyen.
> 2. Le système affiche le formulaire de déclaration structuré en cartes thématiques.
> 3. L'acteur renseigne son état civil (nom, prénom, sexe, date et lieu de naissance, profession), son type et numéro de pièce d'identité (`type_piece`, `numero_piece`), sa situation matrimoniale, son niveau d'étude et son statut d'occupation.
> 4. L'acteur télécharge le scan ou la photo de sa pièce d'identité (format PDF, JPG, JPEG, PNG) et éventuellement un justificatif de domicile (optionnel).
> 5. L'acteur indique son rattachement administratif officiel (District, Région, Département, Sous-Préfecture).
> 6. L'acteur précise ses origines coutumières (Canton, Tribu, Village d'origine).
> 7. L'acteur déclare son lieu de résidence (Pays, Ville, Quartier, Adresse). S'il rside dans la Diaspora, il indique le **Consulat de rattachement** et son **Contact référent local**.
> 8. L'acteur valide la soumission du formulaire.
> 9. Le système effectue le contrôle des données, enregistre les fichiers dans le stockage sécurisé, attribue le statut initial `en_attente` (`statut_validation = 'en_attente'`) et confirme l'enregistrement.
> 
> **Scénarios Alternatifs :**
> * *04.a : Pièce justificative invalide ou volumineuse* $\rightarrow$ Le système alerte l'usager et demande un fichier conforme (max 5 Mo, format PDF, JPG, JPEG, PNG).
> * *06.a : Origines coutumières inconnues* $\rightarrow$ L'usager laisse les champs coutumiers vides ; le système valide la fiche sur le seul rattachement administratif ANStat.
> * *07.a : Justificatif de domicile absent* $\rightarrow$ Le champ étant facultatif, le système valide la saisie sans bloquer l'enregistrement.

---

### 📌 Diagrammes de Séquence à Mettre à Jour dans Word

1. **Figure V.6 : Diagramme de Séquence « S'authentifier »**
   * **Fichier Image à insérer** : [diagramme_sequence_authentification.drawio](file:///C:/Users/tieco/Desktop/herd%20Laravel/recensement/Contexte/base-de-connaissances/outputs/diagramme_sequence_authentification.drawio)
   * **Texte descriptif** : Illustre la connexion sécurisée par e-mail ou téléphone et mot de passe, la vérification par le `AuthController` Laravel et la délivrance du jeton d'authentification Sanctum.

2. **Figure V.7 : Diagramme de Séquence « Importer les données ANStat »**
   * **Fichier Image à insérer** : [diagramme_sequence_import_anstat.drawio](file:///C:/Users/tieco/Desktop/herd%20Laravel/recensement/Contexte/base-de-connaissances/outputs/diagramme_sequence_import_anstat.drawio)
   * **Texte descriptif** : Illustre le déclenchement de la commande CLI `php artisan import:anstat` par l'administrateur, la lecture des structures JSON et l'insertion hiérarchique optimisée en base de données.

3. **Figure V.8 : Diagramme de Séquence « Remplir et enregistrer sa fiche »**
   * **Fichier Image à insérer** : [diagramme_sequence_remplir_fiche.drawio](file:///C:/Users/tieco/Desktop/herd%20Laravel/recensement/Contexte/base-de-connaissances/outputs/diagramme_sequence_remplir_fiche.drawio)
   * **Texte descriptif** : Représente le chargement initial des référentiels en cache client, l'envoi multipart `FormData` des informations et fichiers justificatifs vers `RessortissantController`, le stockage public dans `storage/app/public/documents_identite` et l'attribution du statut `en_attente`.

---

### 📌 Diagramme de Classes (Figure V.9) & Tableau des Entités

* **Fichier Image à insérer dans Word** : [diagramme_de_classes_recensement.drawio](file:///C:/Users/tieco/Desktop/herd%20Laravel/recensement/diagramme_de_classes_recensement.drawio)
* **Texte de description de la classe `Ressortissant` à copier dans Word :**

> **Classe `Ressortissant` (Entité Pivot du Recensement)**
> 
> * **Attributs d'état civil & identité** : `id: Int`, `user_id: Int?`, `nom: String`, `prenom: String`, `telephone: String?`, `sexe: String`, `date_naissance: Date?`, `lieu_naissance: String?`, `famille: String?`, `profession: String?`.
> * **Attributs de pièces justificatives** : `type_piece: String?` (CNI, Passeport, Carte Consulaire, Attestation, Extrait, Autre), `numero_piece: String?`, `document_identite_path: String?`, `justificatif_domicile_path: String?`.
> * **Attributs Diaspora & Référent** : `consulat_rattachement: String?`, `contact_referent_nom: String?`, `contact_referent_telephone: String?`.
> * **Attributs sociodémographiques** : `situation_matrimoniale: String?`, `niveau_etude: String?`, `statut_occupation: String?`.
> * **Attributs de modération & domicile** : `statut_validation: Enum('en_attente', 'valide', 'rejete')`, `motif_rejet: String?`, `pays: String`, `ville: String?`, `quartier: String?`, `adresse: String?`.
> * **Méthodes et Accesseurs virtuels** : `getDocumentIdentiteUrlAttribute()`, `getJustificatifDomicileUrlAttribute()`, `enregistrerFiche()`, `modifierFiche()`, `validerFiche()`, `rejeterFiche()`.

---

## 3. CHAPITRE VI — Captures d'Écran et Implémentation

### 📌 Légendes et Emplacements des Captures à insérer dans Word

1. **Capture 1 : Formulaire de Saisie avec Upload des Pièces**
   * *Emplacement* : Section IHM Citoyen.
   * *Légende* : *Formulaire de recensement citoyen intégrant les champs de pièces d'identité et le justificatif de domicile facultatif (PDF, JPG, JPEG, PNG).*

2. **Capture 2 : Espace de Modération Administrateur**
   * *Emplacement* : Section IHM Administration.
   * *Légende* : *Interface de modération admin affichant la colonne « Pièce & Document » avec les liens de prévisualisation des fichiers et les boutons de validation/rejet motivé.*

3. **Capture 3 : Tableau de Bord Statistiques & Diaspora**
   * *Emplacement* : Section Cartographie & Reporting.
   * *Légende* : *Tableau de bord décisionnel présentant la répartition des ressortissants par type de pièce d'identité, niveau d'étude et consulats de la Diaspora.*
