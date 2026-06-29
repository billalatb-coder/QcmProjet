<?php
require_once '../commun/includes/db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: /qcm/auth/connexion.php");
    exit;
}

//f5
if (!isset($_SESSION['qcm_autorise_chargement']) || $_SESSION['qcm_autorise_chargement'] !== true) {
    if (isset($_SESSION['qcm_questions'])) {
        // Rafraîchissement détecté : on punit comme une triche
        header("Location: process.php?cheat=1");
        exit;
    } else {

        header("Location: start.php");
        exit;
    }
} else {

    unset($_SESSION['qcm_autorise_chargement']);
}

$time_elapsed = time() - $_SESSION['qcm_start_time'];
$time_remaining = 600 - $time_elapsed; // 10 minutes max

// Redirection si temps serveur écoulé
if ($time_remaining <= 0) {
    header("Location: process.php");
    exit;
}

$questions_ids = $_SESSION['qcm_questions'];

// Construire une clause IN pour récupérer les 10 questions en une seule requête
$ids_string = implode(',', array_map('intval', $questions_ids));
$sql = "SELECT * FROM questions WHERE id IN ($ids_string)";
$result = mysqli_query($conn, $sql);

// On stocke les questions 
$questions_db = [];
while ($row = mysqli_fetch_assoc($result)) {
    $questions_db[$row['id']] = $row;
}

$questions_ordered = [];
foreach ($questions_ids as $id) {
    if (isset($questions_db[$id])) {
        $questions_ordered[] = $questions_db[$id];
    }
}

$is_qcm_active = true;
require_once '../commun/includes/header.php';
?>

<div class="container" id="qcm-container" style="display: none;">
    <div class="qcm-header qcm-header-sticky">
        <h2>Test en cours...</h2>
        <div class="timer">Temps restant : <span id="time-display"><?php echo gmdate("i:s", $time_remaining); ?></span>
        </div>
    </div>

    <form method="POST" action="process.php" id="qcm-form">
        <?php foreach ($questions_ordered as $index => $question): ?>
            <div class="question-card">
                <h3 class="question-text">Question <?php echo $index + 1; ?> :
                    <?php echo htmlspecialchars($question['question']); ?>
                </h3>

                <div class="options-grid">
                    <label class="option-label">
                        <input type="radio" name="reponses[<?php echo $question['id']; ?>]" value="1" required>
                        <span><?php echo htmlspecialchars($question['reponse1']); ?></span>
                    </label>

                    <label class="option-label">
                        <input type="radio" name="reponses[<?php echo $question['id']; ?>]" value="2" required>
                        <span><?php echo htmlspecialchars($question['reponse2']); ?></span>
                    </label>

                    <label class="option-label">
                        <input type="radio" name="reponses[<?php echo $question['id']; ?>]" value="3" required>
                        <span><?php echo htmlspecialchars($question['reponse3']); ?></span>
                    </label>

                    <label class="option-label">
                        <input type="radio" name="reponses[<?php echo $question['id']; ?>]" value="4" required>
                        <span><?php echo htmlspecialchars($question['reponse4']); ?></span>
                    </label>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="my-6 text-center">
            <button type="submit" class="btn btn-primary btn-lg">Terminer le QCM et voir les résultats</button>
        </div>
    </form>
</div>

<!-- Overlay d'instruction et bouton de lancement -->
<div id="start-overlay" class="start-overlay">
    <h2>Prêt à commencer ?</h2>
    <p class="start-overlay-text">
        Le QCM comprend 10 questions affichées sur une seule page.
        Vous avez 10 minutes pour répondre à toutes les questions.
        Le test se lancera en plein écran. Toute tentative de quitter le plein écran annulera votre test.
    </p>
    <button id="btn-start-fullscreen" class="btn btn-primary btn-lg">Démarrer le QCM en plein écran</button>
</div>

<!-- L'overlay d'anti-triche -->
<div id="cheat-overlay">
    <h1 class="cheat-warning">⚠️ ATTENTION ⚠️</h1>
    <p class="cheat-overlay-text">Vous avez quitté le mode plein écran ou changé d'onglet.</p>
    <p>Cette action est considérée comme une tentative de triche.</p>
    <button id="resume-btn" class="btn btn-danger btn-lg mt-5">Reprendre en plein écran</button>
</div>

<!-- Script Anti-Triche et Timer -->
<script>
    const timeRemainingInitial = <?php echo $time_remaining; ?>;
</script>
<script src="/qcm/commun/assets/js/anti_triche.js"></script>

<?php require_once '../commun/footer.php'; ?>