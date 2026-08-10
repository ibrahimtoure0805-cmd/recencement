# Documentation Complète — Diagramme de Classes UML (Style Classique Noir & Blanc - Modèle Kotou)

## I. Présentation Générale

Le **diagramme de classes UML** du système de recensement a été modélisé en suivant rigoureusement la représentation classique et épurée du cours UML (fond blanc, bordures noires, verbes d'association préfixés par `+`).

Fichier Draw.io généré : [diagramme_de_classes_recensement.drawio](file:///c:/Users/tieco/Desktop/herd%20Laravel/recensement/diagramme_de_classes_recensement.drawio)

---

## II. Structure des 10 Classes Métier (Zéro Hallucination)

### 1. `Utilisateur`
- **Attributs** : `+id: Int`, `+nom: String`, `+email: String`, `+motDePasse: String`, `+emailVerifieLe: DateTime`
- **Méthodes** : `+seConnecter()`, `+seDeconnecter()`, `+creerFicheRecensement()`

### 2. `Ressortissant` (Entité Pivot du Recensement)
- **Attributs** : `+id: Int`, `+user_id: Int?`, `+nom: String`, `+prenom: String`, `+telephone: String`, `+sexe: String`, `+dateNaissance: Date`, `+lieuNaissance: String`, `+famille: String`, `+profession: String`, `+typePiece: String`, `+numeroPiece: String`, `+documentIdentitePath: String`, `+justificatifDomicilePath: String`, `+consulatRattachement: String`, `+contactReferentNom: String`, `+contactReferentTelephone: String`, `+situationMatrimoniale: String`, `+niveauEtude: String`, `+statutOccupation: String`, `+statutValidation: String`, `+motifRejet: String`, `+paysResidence: String`, `+ville: String`, `+quartier: String`, `+adresse: String`
- **Méthodes** : `+enregistrerFiche()`, `+modifierFiche()`, `+validerFiche()`, `+rejeterFiche()`, `+getDocumentIdentiteUrl()`, `+getJustificatifDomicileUrl()`

### 3. `District`
- **Attributs** : `+id: Int`, `+codeDistrict: String`, `+nomDistrict: String`, `+annee: Int`
- **Méthodes** : `+listerRegions()`, `+ajouterRegion()`

### 4. `Region`
- **Attributs** : `+id: Int`, `+codeRegion: String`, `+nomRegion: String`, `+annee: Int`
- **Méthodes** : `+listerDepartements()`, `+ajouterDepartement()`

### 5. `Departement`
- **Attributs** : `+id: Int`, `+codeDepartement: String`, `+nomDepartement: String`, `+annee: Int`
- **Méthodes** : `+listerSousPrefectures()`, `+ajouterSousPrefecture()`

### 6. `SousPrefecture`
- **Attributs** : `+id: Int`, `+anstatId: Int`, `+codeSousPrefecture: String`, `+nomSousPrefecture: String`, `+annee: Int`
- **Méthodes** : `+listerCantons()`, `+listerRessortissants()`

### 7. `Canton`
- **Attributs** : `+id: Int`, `+nom: String`
- **Méthodes** : `+listerTribus()`, `+ajouterTribu()`

### 8. `Tribu`
- **Attributs** : `+id: Int`, `+nom: String`
- **Méthodes** : `+listerVillages()`, `+ajouterVillage()`

### 9. `Village`
- **Attributs** : `+id: Int`, `+nom: String`
- **Méthodes** : `+listerRessortissants()`

### 10. `Pays`
- **Attributs** : `+id: Int`, `+nom: String`, `+codeIso: String`, `+estParDefaut: Boolean`
- **Méthodes** : `+listerResidents()`

---

## III. Matrice des Associations & Verbes d'Action (`+Verbe`)

| Classe Source | Verbe d'Association | Classe Cible | Multiplicité Source | Multiplicité Cible | Explication Métier |
| :--- | :---: | :--- | :---: | :---: | :--- |
| **District** | `+Englober` | **Region** | `1` | `1..*` | Un district englobe une ou plusieurs régions administratives. |
| **Region** | `+Regrouper` | **Departement** | `1` | `1..*` | Une région regroupe un ou plusieurs départements. |
| **Departement** | `+Comprendre` | **SousPrefecture** | `1` | `1..*` | Un département comprend une ou plusieurs sous-préfectures. |
| **SousPrefecture** | `+Heberger` | **Canton** | `1` | `0..*` | Une sous-préfecture héberge 0 ou plusieurs cantons. |
| **Canton** | `+Rassembler` | **Tribu** | `1` | `0..*` | Un canton rassemble 0 ou plusieurs tribus. |
| **Tribu** | `+Composer` | **Village** | `1` | `1..*` | Une tribu compose un ou plusieurs villages coutumiers. |
| **SousPrefecture** | `+Rattacher` | **Ressortissant** | `1` | `1..*` | La sous-préfecture rattachera le citoyen sur le plan administratif. |
| **Village** | `+Associer` | **Ressortissant** | `0..1` | `0..*` | Le village associera le citoyen sur le plan coutumier (facultatif). |
| **Pays** | `+Localiser` | **Ressortissant** | `1` | `1..*` | Le pays localisera la résidence du citoyen (locale ou Diaspora). |
| **Utilisateur** | `+Detenir` | **Ressortissant** | `1` | `0..1` | Un compte utilisateur détiendra 0 ou 1 fiche citoyenne. |
