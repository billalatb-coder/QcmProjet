<?php
require_once '../commun/includes/db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: /qcm/auth/connexion.php");
    exit;
}

require_once '../commun/includes/header.php';
?>

<div class="container text-center" style="margin-top: 4rem; margin-bottom: 4rem; max-width: 700px;">
    <div style="font-size: 6rem; margin-bottom: 1rem; line-height: 1;">🚫</div>
    <h1 style="color: var(--danger-color); font-size: 3rem; margin-bottom: 1.5rem;">Triche Détectée</h1>
    
    <div class="alert alert-danger" style="font-size: 1.2rem; text-align: left; margin-bottom: 2rem;">
        <strong>Violation des règles de l'examen :</strong><br><br>
        Notre système anti-triche a détecté un comportement interdit (changement d'onglet, perte de focus de la fenêtre, ou abandon répété du mode plein écran).
    </div>
    
    <div style="background-color: var(--card-bg); padding: 2.5rem; border-radius: var(--radius-lg); box-shadow: var(--shadow-md);">
        <h2 style="margin-bottom: 1.5rem;">Sanction immédiate</h2>
        <p style="font-size: 1.25rem; font-weight: 600; color: var(--text-main);">
            Une note de <span style="color: var(--danger-color); font-size: 2.5rem; vertical-align: middle; margin: 0 0.5rem;">0 / 20</span> vous a été attribuée.
        </p>
        <p style="color: var(--text-muted); margin-top: 1.5rem;">
            Cette tentative frauduleuse a été enregistrée de manière permanente dans votre historique ainsi que dans la base de données.
        </p>
    </div>

    <div style="margin-top: 3rem; display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
        <a href="/qcm/qcm/history.php" class="btn btn-primary btn-lg">Consulter mon historique</a>
        <a href="/qcm/index.php" class="btn btn-outline btn-lg">Retour à l'accueil</a>
    </div>
</div>

<?php require_once '../commun/footer.php'; ?>
