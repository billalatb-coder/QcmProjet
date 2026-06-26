<?php
require_once '../commun/includes/db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Vérification du rôle admin
if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /qcm/index.php");
    exit;
}

// Statistiques basiques
$req_users = mysqli_query($conn, "SELECT COUNT(*) as total FROM utilisateurs WHERE role = 'user'");
$total_users = mysqli_fetch_assoc($req_users)['total'];

$req_questions = mysqli_query($conn, "SELECT COUNT(*) as total FROM questions");
$total_questions = mysqli_fetch_assoc($req_questions)['total'];

$req_tentatives = mysqli_query($conn, "SELECT COUNT(*) as total, AVG(score) as moyenne FROM tentatives");
$stats_tentatives = mysqli_fetch_assoc($req_tentatives);
$total_tentatives = $stats_tentatives['total'];
$moyenne_generale = round($stats_tentatives['moyenne'], 2);

require_once '../commun/includes/header.php';
?>

<div class="container">
    <div class="qcm-header">
        <h2>Dashboard Administrateur</h2>
        <div class="hero-actions">
            <a href="users.php" class="btn btn-primary">Gérer les Utilisateurs</a>
            <a href="questions.php" class="btn btn-primary">Gérer les Questions</a>
        </div>
    </div>
    
    <div class="features-grid mt-5">
        <div class="feature-card">
            <div class="feature-icon">👥</div>
            <h3><?php echo $total_users; ?></h3>
            <p>Utilisateurs Inscrits</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">📝</div>
            <h3><?php echo $total_questions; ?></h3>
            <p>Questions en base</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">📊</div>
            <h3><?php echo $total_tentatives; ?></h3>
            <p>QCM passés</p>
            <p><small>Moyenne globale: <?php echo $moyenne_generale; ?>/20</small></p>
        </div>
    </div>
</div>

<?php require_once '../commun/footer.php'; ?>
