<?php
require_once '../commun/includes/db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Si pas connecté, on redirige vers la connexion
if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: /qcm/auth/connexion.php");
    exit;
}

// Si la session QCM est déjà terminée (double appel après fraude)
// On redirige vers fraud.php si le flag est présent, sinon vers l'accueil
if (!isset($_SESSION['qcm_questions'])) {
    if (isset($_SESSION['qcm_fraud']) && $_SESSION['qcm_fraud'] === true) {
        header("Location: /qcm/qcm/fraud.php");
    } else {
        header("Location: /qcm/index.php");
    }
    exit;
}

$utilisateur_id = $_SESSION['utilisateur_id'];
$duree = time() - $_SESSION['qcm_start_time'];
if ($duree > 600) $duree = 600;

$questions_ids = $_SESSION['qcm_questions'];
$reponses_user = $_POST['reponses'] ?? [];

// Initialiser le score
$score = 0;
$details_correction = [];

// Vérification d'une annulation ou d'une triche directe
$is_canceled = isset($_GET['cancel']) && $_GET['cancel'] == 1;
$is_cheated = isset($_GET['cheat']) && $_GET['cheat'] == 1;

if ($is_canceled || $is_cheated) {
    // Score 0 direct
    $score = 0;
    
    // Insertion de la tentative pour obtenir son ID (Score 0)
    $date = date('Y-m-d H:i:s');
    $sql_tentative = "INSERT INTO tentatives (utilisateur_id, score, date, duree) VALUES ($utilisateur_id, 0, '$date', $duree)";
    mysqli_query($conn, $sql_tentative);
    $tentative_id = mysqli_insert_id($conn);
    
    // Remplir les détails avec un message spécial
    $message = $is_canceled ? "Tentative annulée par l'utilisateur." : "Triche détectée (Changement d'onglet ou plein écran quitté plusieurs fois).";
    
    foreach ($questions_ids as $qid) {
        // Insertion réponse fausse
        mysqli_query($conn, "INSERT INTO reponses (tentative_id, question_id, reponse_utilisateur, correcte) VALUES ($tentative_id, $qid, 0, 0)");
        
        $sql = "SELECT * FROM questions WHERE id = $qid";
        $q = mysqli_fetch_assoc(mysqli_query($conn, $sql));
        $details_correction[] = [
            'question' => $q['question'],
            'reponse_utilisateur' => $message,
            'bonne_reponse' => $q['reponse' . $q['bonne_reponse']],
            'est_correcte' => 0
        ];
    }
} else {
    // Traitement normal
    // Insertion de la tentative
    $date = date('Y-m-d H:i:s');
    $sql_tentative = "INSERT INTO tentatives (utilisateur_id, score, date, duree) VALUES ($utilisateur_id, 0, '$date', $duree)";
    mysqli_query($conn, $sql_tentative);
    $tentative_id = mysqli_insert_id($conn);

    foreach ($questions_ids as $qid) {
        $sql = "SELECT * FROM questions WHERE id = $qid";
        $result = mysqli_query($conn, $sql);
        $q = mysqli_fetch_assoc($result);
        
        $rep_user = $reponses_user[$qid] ?? 0;
        $est_correcte = ($rep_user == $q['bonne_reponse']) ? 1 : 0;
        
        if ($est_correcte) {
            $score += 2; // Chaque bonne réponse vaut 2 points (10 questions -> sur 20)
        }
        
        // Insertion de la réponse en BDD
        $sql_rep = "INSERT INTO reponses (tentative_id, question_id, reponse_utilisateur, correcte) 
                    VALUES ($tentative_id, $qid, $rep_user, $est_correcte)";
        mysqli_query($conn, $sql_rep);
        
        // Stockage en session pour l'affichage immédiat
        $details_correction[] = [
            'question' => $q['question'],
            'reponse_utilisateur' => $rep_user ? $q['reponse' . $rep_user] : 'Non répondue',
            'bonne_reponse' => $q['reponse' . $q['bonne_reponse']],
            'est_correcte' => $est_correcte
        ];
    }

    // Mise à jour du score final
    mysqli_query($conn, "UPDATE tentatives SET score = $score WHERE id = $tentative_id");
}

// Stockage des résultats en session pour result.php
$_SESSION['qcm_score'] = $score;
$_SESSION['qcm_details'] = $details_correction;

// Si c'est une fraude, on marque la session AVANT de nettoyer
// Cela permet à fraud.php de rester visible même si la page est rechargée
if ($is_cheated) {
    $_SESSION['qcm_fraud'] = true;
}

// On nettoie la session de QCM pour empêcher un rechargement
unset($_SESSION['qcm_questions']);
unset($_SESSION['qcm_index']);
unset($_SESSION['qcm_reponses']);
unset($_SESSION['qcm_start_time']);

if ($is_cheated) {
    header("Location: fraud.php");
} else {
    header("Location: result.php");
}
exit;
?>
