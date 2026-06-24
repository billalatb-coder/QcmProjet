<?php
require_once '../commun/includes/db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: /qcm/auth/connexion.php");
    exit;
}

$utilisateur_id = $_SESSION['utilisateur_id'];

// Récupération de l'historique
$sql = "SELECT * FROM tentatives WHERE utilisateur_id = $utilisateur_id ORDER BY date DESC";
$result = mysqli_query($conn, $sql);

// Calcul de la moyenne
$sql_moyenne = "SELECT AVG(score) as moyenne, COUNT(*) as total FROM tentatives WHERE utilisateur_id = $utilisateur_id";
$res_moyenne = mysqli_query($conn, $sql_moyenne);
$stats = mysqli_fetch_assoc($res_moyenne);
$moyenne = $stats['total'] > 0 ? round($stats['moyenne'], 2) : 0;

require_once '../commun/includes/header.php';
?>

<div class="container">
    <div class="qcm-header">
        <h2>Mon Historique</h2>
        <div>
            <span class="user-badge" style="font-size: 1.1rem;">Moyenne générale : <strong><?php echo $moyenne; ?> / 20</strong></span>
        </div>
    </div>

    <?php if ($stats['total'] == 0) : ?>
        <div class="alert alert-warning text-center">
            Vous n'avez passé aucun QCM pour le moment.
        </div>
        <div class="text-center" style="margin-top: 2rem;">
            <a href="start.php" class="btn btn-primary btn-lg">Passer mon premier QCM</a>
        </div>
    <?php else : ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>N° Tentative</th>
                        <th>Date et Heure</th>
                        <th>Score</th>
                        <th>Résultat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $compteur = $stats['total'];
                    while ($tentative = mysqli_fetch_assoc($result)) : 
                    ?>
                    <tr>
                        <td>Tentative #<?php echo $compteur--; ?></td>
                        <td><?php echo date('d/m/Y à H:i', strtotime($tentative['date'])); ?></td>
                        <td><strong><?php echo $tentative['score']; ?> / 20</strong></td>
                        <td>
                            <?php if ($tentative['score'] >= 10) : ?>
                                <span style="color:var(--success-color); font-weight:bold;">Réussi</span>
                            <?php else : ?>
                                <span style="color:var(--danger-color); font-weight:bold;">Échoué</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        
        <div class="text-center" style="margin-top: 3rem;">
            <a href="start.php" class="btn btn-primary">Passer un nouveau QCM</a>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../commun/footer.php'; ?>
