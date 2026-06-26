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

    <div class="text-center my-6">
        <h1 class="result-score-title <?php echo $score >= 10 ? 'text-success' : 'text-danger'; ?>">
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

    <h3 class="mb-5">Correction détaillée :</h3>
    
    <div class="d-flex flex-column gap-4">
        <?php foreach ($details as $index => $d) : ?>
            <div class="question-card <?php echo $d['est_correcte'] ? 'question-card-success' : 'question-card-danger'; ?>" style="padding: 1.5rem;">
                <h4 class="mb-3">Question <?php echo $index + 1; ?> : <?php echo htmlspecialchars($d['question']); ?></h4>
                
                <div class="correction-grid">
                    <div>
                        <strong>Votre réponse :</strong><br>
                        <span class="<?php echo $d['est_correcte'] ? 'text-success' : 'text-danger'; ?> fw-500">
                            <?php echo htmlspecialchars($d['reponse_utilisateur']); ?>
                        </span>
                    </div>
                    
                    <?php if (!$d['est_correcte']) : ?>
                    <div>
                        <strong>Bonne réponse :</strong><br>
                        <span class="text-success fw-500">
                            <?php echo htmlspecialchars($d['bonne_reponse']); ?>
                        </span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <div class="text-center mt-6">
        <a href="start.php" class="btn btn-primary btn-lg">Refaire un QCM</a>
    </div>
</div>

<?php require_once '../commun/footer.php'; ?>
