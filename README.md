# QCM Pro - Application d'évaluation en ligne

Projet universitaire de développement web réalisé par :

- **Arezki** (Authentification & Sécurité) "Dossier Auth"
- **Aris** (Interface Admin & Design CSS) "Dossier admin"
- **Billal** (QCM, Anti-triche, Résultats, Historique) "Dossier qcm"

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

## remarque pour les utilisateurs de WAMP:

Ce projet est adapté a MAMP donc si vous utilisez wamp

### Identifiants Administrateur par défaut

- Email : `admin@qcm.local`
- Mot de passe : `admin123`
