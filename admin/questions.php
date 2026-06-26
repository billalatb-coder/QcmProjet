<?php
require_once '../commun/includes/db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /qcm/index.php");
    exit;
}

// Action de suppression
if (isset($_GET['action']) && $_GET['action'] === 'supprimer' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    mysqli_query($conn, "DELETE FROM questions WHERE id = $id");
    header("Location: questions.php");
    exit;
}

// Récupération de toutes les questions
$sql = "SELECT * FROM questions ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

require_once '../commun/includes/header.php';
?>

<div class="container">
    <div class="qcm-header">
        <h2>Gestion des Questions</h2>
        <div>
            <a href="question_add.php" class="btn btn-primary">Ajouter une question</a>
            <a href="dashboard.php" class="btn btn-outline">Retour</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Question</th>
                    <th>Catégorie</th>
                    <th>Bonne réponse</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($q = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?php echo $q['id']; ?></td>
                    <td><?php echo htmlspecialchars(substr($q['question'], 0, 50)) . '...'; ?></td>
                    <td><?php echo htmlspecialchars($q['categorie']); ?></td>
                    <td>
                        <?php 
                        $bonne_rep_key = 'reponse' . $q['bonne_reponse'];
                        echo htmlspecialchars($q[$bonne_rep_key]); 
                        ?>
                    </td>
                    <td>
                        <a href="question_edit.php?id=<?php echo $q['id']; ?>" class="btn btn-outline btn-sm">Modifier</a>
                        <a href="questions.php?action=supprimer&id=<?php echo $q['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer cette question ?');">Supprimer</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../commun/footer.php'; ?>
