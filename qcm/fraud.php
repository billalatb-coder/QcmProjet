<?php
require_once '../commun/includes/db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Vérification connexion
if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: /qcm/auth/connexion.php");
    exit;
}

// On n'autorise l'accès que si le flag de fraude est présent en session
// Cela empêche un accès direct à cette page sans avoir vraiment triché
if (!isset($_SESSION['qcm_fraud']) || $_SESSION['qcm_fraud'] !== true) {
    header("Location: /qcm/index.php");
    exit;
}

// NOTE : on ne supprime PAS le flag ici.
// Il doit rester actif tant que l'utilisateur est sur cette page.
// Cela stabilise l'affichage si des requêtes JS parasites arrivent en retard sur process.php.
// Le flag sera nettoyé uniquement par start.php quand un nouveau QCM est démarré.

require_once '../commun/includes/header.php';
?>

<div class="container text-center my-8 max-w-700 mx-auto">
    <div class="fraud-icon">🚫</div>
    <h1 class="text-danger fraud-title">Triche Détectée</h1>
    
    <div class="alert alert-danger fraud-alert">
        <strong>Violation des règles de l'examen :</strong><br><br>
        Notre système anti-triche a détecté un comportement interdit (changement d'onglet, perte de focus de la fenêtre, ou abandon répété du mode plein écran).
    </div>
    
    <div class="fraud-card">
        <h2 class="mb-4">Sanction immédiate</h2>
        <p class="fraud-score-text">
            Une note de <span class="text-danger fraud-score">0 / 20</span> vous a été attribuée.
        </p>
        <p class="text-muted mt-4">
            Cette tentative frauduleuse a été enregistrée de manière permanente dans votre historique ainsi que dans la base de données.
        </p>
    </div>

    <div class="mt-6 d-flex justify-center gap-3 flex-wrap">
        <a href="/qcm/qcm/history.php" class="btn btn-primary btn-lg">Consulter mon historique</a>
        <a href="/qcm/index.php" class="btn btn-outline btn-lg">Retour à l'accueil</a>
    </div>
</div>

<?php require_once '../commun/footer.php'; ?>
