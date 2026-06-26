<?php
require_once '../commun/includes/db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: /qcm/auth/connexion.php");
    exit;
}

// Nettoyage du flag de fraude d'une éventuelle session précédente
// Cela garantit qu'un utilisateur qui démarre honnêtement un nouveau QCM
// repart d'un état propre, sans résidu de l'anti-triche précédent
unset($_SESSION['qcm_fraud']);

// Vérification qu'il y a assez de questions
$sql_count = "SELECT COUNT(*) as total FROM questions";
$result_count = mysqli_query($conn, $sql_count);
$total = mysqli_fetch_assoc($result_count)['total'];

if ($total < 10) {
    require_once '../commun/includes/header.php';
    echo "<div class='container'><div class='alert alert-warning'>Il n'y a pas assez de questions dans la base de données (10 requises). Veuillez contacter l'administrateur.</div></div>";
    require_once '../commun/footer.php';
    exit;
}

// Sélection de 10 questions aléatoires
$sql = "SELECT id FROM questions ORDER BY RAND() LIMIT 10";
$result = mysqli_query($conn, $sql);

$questions_ids = [];
while ($row = mysqli_fetch_assoc($result)) {
    $questions_ids[] = $row['id'];
}

// Initialisation des variables de session pour le QCM
$_SESSION['qcm_questions'] = $questions_ids;
$_SESSION['qcm_start_time'] = time();

// Flag unique autorisant le premier chargement de question.php
// Il sera consommé dès la première ouverture de la page
// Tout rechargement ultérieur (F5, Ctrl+R) sera détecté comme une triche
$_SESSION['qcm_autorise_chargement'] = true;

header("Location: question.php");
exit;
?>
