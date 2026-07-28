# Décuplez votre Claude

Ce package transforme Claude en un assistant qui vous connaît et qui retient tout. Il contient les deux briques :

1. Un Claude.md personnel : il apprend à Claude qui vous êtes et comment vous répondre.
2. Une base de connaissances : une mémoire où vous déposez tout, que Claude organise et interroge.

Pas besoin d'être technique. Vous travaillez dans Claude Cowork (application desktop, plus un abonnement payant, le plan Pro suffit pour démarrer). Tout se fait sans une ligne de code.

## Installation en 3 étapes

1. Placez ce dossier dans Cowork (ouvrez-le comme dossier de travail, ou déposez-le dans votre dossier de travail).
2. Dites à Claude : "Installe mon assistant en suivant le fichier INSTALLER.md."
3. Répondez à ses questions. Claude crée votre Claude.md personnel automatiquement.

C'est tout. Votre Claude vous connaît maintenant, et il sait gérer votre base de connaissances.

## Utiliser votre base de connaissances

La base est déjà incluse dans le dossier base-de-connaissances. Pour vous en servir :

1. Déposez vos documents en vrac dans le dossier raw (articles, notes, captures, PDF). Ne rangez rien, c'est le travail de Claude.

2. Demandez à Claude de compiler votre base :

   Lis tout ce qui se trouve dans le dossier raw de ma base de connaissances, puis compile la base dans le dossier wiki en suivant les règles du Claude.md de ce dossier. Crée d'abord un fichier index.md, puis une page par sujet, et relie les sujets entre eux. Cite la source de chaque information.

3. Posez ensuite vos questions :

   En te basant uniquement sur ma base de connaissances, réponds à cette question : [VOTRE QUESTION]. Lis l'index et les pages concernées, cite tes sources, et enregistre ta réponse dans le dossier outputs. Si une information manque, dis-le-moi au lieu de l'inventer.

## Contenu du package

- Claude.md : votre fichier personnel (rempli pendant l'installation).
- INSTALLER.md : le module qui vous interviewe. Vous n'avez pas à le modifier.
- base-de-connaissances/ : votre mémoire (un Claude.md qui la pilote, et les dossiers raw, wiki, outputs).

## Pour aller plus loin

Ceci est la version débutant. Pour un système beaucoup plus avancé (commandes, veille personnalisée, mémoire évolutive), regardez ma formation complète de 6h sur Claude Code, sur la chaîne : youtube.com/@yassine-sdiri

Créé par Yass pour la Communauté IA : skool.com/intelligenceartificielle
