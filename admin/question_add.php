<?php
require_once '../commun/includes/db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /qcm/index.php");
    exit;
}

$erreur = '';
$succes = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question = trim($_POST['question'] ?? '');
    $reponse1 = trim($_POST['reponse1'] ?? '');
    $reponse2 = trim($_POST['reponse2'] ?? '');
    $reponse3 = trim($_POST['reponse3'] ?? '');
    $reponse4 = trim($_POST['reponse4'] ?? '');
    $bonne_reponse = (int)($_POST['bonne_reponse'] ?? 0);
    $categorie = trim($_POST['categorie'] ?? 'Général');

    if (empty($question) || empty($reponse1) || empty($reponse2) || empty($reponse3) || empty($reponse4) || $bonne_reponse < 1 || $bonne_reponse > 4) {
        $erreur = "Tous les champs sont obligatoires et la bonne réponse doit être sélectionnée.";
    } else {
        $q = mysqli_real_escape_string($conn, $question);
        $r1 = mysqli_real_escape_string($conn, $reponse1);
        $r2 = mysqli_real_escape_string($conn, $reponse2);
        $r3 = mysqli_real_escape_string($conn, $reponse3);
        $r4 = mysqli_real_escape_string($conn, $reponse4);
        $cat = mysqli_real_escape_string($conn, $categorie);

        $sql = "INSERT INTO questions (question, reponse1, reponse2, reponse3, reponse4, bonne_reponse, categorie) 
                VALUES ('$q', '$r1', '$r2', '$r3', '$r4', $bonne_reponse, '$cat')";
        
        if (mysqli_query($conn, $sql)) {
            $succes = "Question ajoutée avec succès !";
            // On vide les champs pour la prochaine saisie
            $_POST = [];
        } else {
            $erreur = "Erreur lors de l'ajout : " . mysqli_error($conn);
        }
    }
}

require_once '../commun/includes/header.php';
?>

<div class="container">
    <div class="form-container max-w-800">
        <div class="qcm-header">
            <h2>Ajouter une question</h2>
            <a href="questions.php" class="btn btn-outline">Retour à la liste</a>
        </div>

        <?php if (!empty($erreur)) : ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($erreur); ?></div>
        <?php endif; ?>
        
        <?php if (!empty($succes)) : ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($succes); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="categorie">Catégorie</label>
                <input type="text" id="categorie" name="categorie" class="form-control" value="<?php echo htmlspecialchars($_POST['categorie'] ?? 'Général'); ?>" required>
            </div>

            <div class="form-group">
                <label for="question">Intitulé de la question</label>
                <textarea id="question" name="question" class="form-control" rows="3" required><?php echo htmlspecialchars($_POST['question'] ?? ''); ?></textarea>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label for="reponse1">Réponse 1</label>
                    <input type="text" id="reponse1" name="reponse1" class="form-control" value="<?php echo htmlspecialchars($_POST['reponse1'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="reponse2">Réponse 2</label>
                    <input type="text" id="reponse2" name="reponse2" class="form-control" value="<?php echo htmlspecialchars($_POST['reponse2'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="reponse3">Réponse 3</label>
                    <input type="text" id="reponse3" name="reponse3" class="form-control" value="<?php echo htmlspecialchars($_POST['reponse3'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="reponse4">Réponse 4</label>
                    <input type="text" id="reponse4" name="reponse4" class="form-control" value="<?php echo htmlspecialchars($_POST['reponse4'] ?? ''); ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label for="bonne_reponse">Quelle est la bonne réponse ?</label>
                <select id="bonne_reponse" name="bonne_reponse" class="form-control" required>
                    <option value="">-- Sélectionner --</option>
                    <option value="1" <?php echo (isset($_POST['bonne_reponse']) && $_POST['bonne_reponse'] == 1) ? 'selected' : ''; ?>>Réponse 1</option>
                    <option value="2" <?php echo (isset($_POST['bonne_reponse']) && $_POST['bonne_reponse'] == 2) ? 'selected' : ''; ?>>Réponse 2</option>
                    <option value="3" <?php echo (isset($_POST['bonne_reponse']) && $_POST['bonne_reponse'] == 3) ? 'selected' : ''; ?>>Réponse 3</option>
                    <option value="4" <?php echo (isset($_POST['bonne_reponse']) && $_POST['bonne_reponse'] == 4) ? 'selected' : ''; ?>>Réponse 4</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary btn-w100">Enregistrer la question</button>
        </form>
    </div>
</div>

<?php require_once '../commun/footer.php'; ?>
