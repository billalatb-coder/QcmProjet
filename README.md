# QCM Pro - Application d'évaluation en ligne

Projet universitaire de développement web réalisé par :
- **Arezki** (Authentification & Sécurité)
- **Aris** (Interface Admin & Design CSS)
- **Billal** (QCM, Anti-triche, Résultats, Historique)

## Fonctionnalités principales
- **Système d'authentification complet** (Inscription, Connexion, Déconnexion) avec mots de passe hashés.
- **Passage de QCM aléatoire** de 10 questions.
- **Système Anti-Triche strict** : passage obligatoire en plein écran, chronomètre de 10 minutes, blocage du clic droit, de la copie et de la sélection de texte. Interruption si changement d'onglet ou perte de focus.
- **Calcul et historique des scores**.
- **Panel d'administration** pour la gestion des utilisateurs (bloquer/débloquer/supprimer) et des questions (ajout, édition, suppression).

## Technologies utilisées
- **Frontend** : HTML5, CSS3 (sans framework, design premium et variables CSS), JavaScript Vanilla (pour l'anti-triche et le timer).
- **Backend** : PHP Vanilla (Mode procédural, aucune fonction complexe/POO conformément au cahier des charges).
- **Base de données** : MySQL / MariaDB (utilisation de `mysqli` procédural).

## Installation
1. Importer le fichier `database.sql` dans votre base de données via phpMyAdmin.
2. S'assurer que le dossier du projet est placé dans la racine web (ex: `c:\MAMP\htdocs\qcm`).
3. Modifier si nécessaire les identifiants de connexion à la BDD dans le fichier `commun/includes/db.php`.
4. Accéder à l'application via `http://localhost/qcm/`.

### Accéder au Panel Administrateur

Pour accéder au panel d'administration (`/qcm/admin/dashboard.php`), deux méthodes sont possibles :

#### Méthode 1 : Utiliser l'administrateur par défaut
Connectez-vous sur la page de connexion avec les identifiants pré-configurés :
- **Email** : `admin@qcm.local`
- **Mot de passe** : `admin123`

#### Méthode 2 : Promouvoir un utilisateur existant
1. Créez un compte via la page d'inscription de l'application (`/qcm/auth/inscription.php`).
2. Ouvrez votre outil de gestion de base de données (ex: **phpMyAdmin**).
3. Accédez à la base de données `qcm_app` et ouvrez la table `utilisateurs`.
4. Modifiez la ligne correspondant à votre utilisateur et remplacez la valeur de la colonne `role` (qui est `user` par défaut) par `admin`.
5. Connectez-vous avec ce compte sur l'application.

Une fois connecté en tant qu'administrateur, un lien **"Dashboard Admin"** apparaîtra automatiquement dans la barre de navigation. Vous pouvez également y accéder directement via l'URL : `http://localhost/qcm/admin/dashboard.php`.

## Conformité au cahier des charges
- **Code 100% Procédural** : Aucune classe, aucun design pattern n'a été utilisé.
- **Pas de fonctions réutilisables** : Le code a été codé de manière la plus explicite ("plate") possible pour être facilement justifiable à l'oral (en 10 secondes maximum par ligne).
- **Pas de framework** : Aucun framework PHP, CSS ou JS n'est inclus, tout a été développé "from scratch".
- **SQL procédural** : PDO est proscrit, `mysqli_*` a été utilisé partout.

