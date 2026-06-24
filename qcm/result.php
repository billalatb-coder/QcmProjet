<?php
require_once '../commun/includes/db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: /qcm/auth/connexion.php");
    exit;
}

if (!isset($_SESSION['qcm_score']) || !isset($_SESSION['qcm_details'])) {
    header("Location: /qcm/index.php");
    exit;
}

$score = $_SESSION['qcm_score'];
$details = $_SESSION['qcm_details'];

require_once '../commun/includes/header.php';
?>

<div class="container">
    <div class="qcm-header">
        <h2>Résultats du QCM</h2>
        <a href="history.php" class="btn btn-outline">Voir mon historique</a>
    </div>

    <div class="text-center" style="margin: 3rem 0;">
        <h1 style="font-size: 4rem; color: <?php echo $score >= 10 ? 'var(--success-color)' : 'var(--danger-color)'; ?>;">
            <?php echo $score; ?> / 20
        </h1>
        <p class="hero-subtitle">
            <?php 
            if ($score >= 16) echo "Excellent travail !";
            elseif ($score >= 10) echo "Bien joué, vous avez la moyenne.";
            else echo "Il va falloir réviser un peu plus.";
            ?>
        </p>
    </div>

    <h3 style="margin-bottom: 2rem;">Correction détaillée :</h3>

    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <?php foreach ($details as $index => $d) : ?>
            <div class="question-card" style="padding: 1.5rem; border-left: 5px solid <?php echo $d['est_correcte'] ? 'var(--success-color)' : 'var(--danger-color)'; ?>">
                <h4 style="margin-bottom: 1rem;">Question <?php echo $index + 1; ?> : <?php echo htmlspecialchars($d['question']); ?></h4>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; background: #f8fafc; padding: 1rem; border-radius: var(--radius-md);">
                    <div>
                        <strong>Votre réponse :</strong><br>
                        <span style="color: <?php echo $d['est_correcte'] ? 'var(--success-color)' : 'var(--danger-color)'; ?>; font-weight: 500;">
                            <?php echo htmlspecialchars($d['reponse_utilisateur']); ?>
                        </span>
                    </div>
                    
                    <?php if (!$d['est_correcte']) : ?>
                    <div>
                        <strong>Bonne réponse :</strong><br>
                        <span style="color: var(--success-color); font-weight: 500;">
                            <?php echo htmlspecialchars($d['bonne_reponse']); ?>
                        </span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <div class="text-center" style="margin-top: 3rem;">
        <a href="start.php" class="btn btn-primary btn-lg">Refaire un QCM</a>
    </div>
</div>

<?php require_once '../commun/footer.php'; ?>
