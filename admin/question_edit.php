<?php
require_once '../commun/includes/db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /qcm/index.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: questions.php");
    exit;
}

$id = (int)$_GET['id'];
$erreur = '';
$succes = '';

// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question = trim($_POST['question'] ?? '');
    $reponse1 = trim($_POST['reponse1'] ?? '');
    $reponse2 = trim($_POST['reponse2'] ?? '');
    $reponse3 = trim($_POST['reponse3'] ?? '');
    $reponse4 = trim($_POST['reponse4'] ?? '');
    $bonne_reponse = (int)($_POST['bonne_reponse'] ?? 0);
    $categorie = trim($_POST['categorie'] ?? 'Général');

    if (empty($question) || empty($reponse1) || empty($reponse2) || empty($reponse3) || empty($reponse4) || $bonne_reponse < 1 || $bonne_reponse > 4) {
        $erreur = "Tous les champs sont obligatoires.";
    } else {
        $q = mysqli_real_escape_string($conn, $question);
        $r1 = mysqli_real_escape_string($conn, $reponse1);
        $r2 = mysqli_real_escape_string($conn, $reponse2);
        $r3 = mysqli_real_escape_string($conn, $reponse3);
        $r4 = mysqli_real_escape_string($conn, $reponse4);
        $cat = mysqli_real_escape_string($conn, $categorie);

        $sql = "UPDATE questions SET 
                question = '$q', 
                reponse1 = '$r1', 
                reponse2 = '$r2', 
                reponse3 = '$r3', 
                reponse4 = '$r4', 
                bonne_reponse = $bonne_reponse, 
                categorie = '$cat' 
                WHERE id = $id";
        
        if (mysqli_query($conn, $sql)) {
            $succes = "Question modifiée avec succès !";
        } else {
            $erreur = "Erreur lors de la modification : " . mysqli_error($conn);
        }
    }
}

// Récupération de la question
$result = mysqli_query($conn, "SELECT * FROM questions WHERE id = $id");
$question_data = mysqli_fetch_assoc($result);

if (!$question_data) {
    header("Location: questions.php");
    exit;
}

require_once '../commun/includes/header.php';
?>

<div class="container">
    <div class="form-container" style="max-width: 800px;">
        <div class="qcm-header">
            <h2>Modifier la question #<?php echo $id; ?></h2>
            <a href="questions.php" class="btn btn-outline">Retour</a>
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
                <input type="text" id="categorie" name="categorie" class="form-control" value="<?php echo htmlspecialchars($_POST['categorie'] ?? $question_data['categorie']); ?>" required>
            </div>

            <div class="form-group">
                <label for="question">Intitulé de la question</label>
                <textarea id="question" name="question" class="form-control" rows="3" required><?php echo htmlspecialchars($_POST['question'] ?? $question_data['question']); ?></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="reponse1">Réponse 1</label>
                    <input type="text" id="reponse1" name="reponse1" class="form-control" value="<?php echo htmlspecialchars($_POST['reponse1'] ?? $question_data['reponse1']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="reponse2">Réponse 2</label>
                    <input type="text" id="reponse2" name="reponse2" class="form-control" value="<?php echo htmlspecialchars($_POST['reponse2'] ?? $question_data['reponse2']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="reponse3">Réponse 3</label>
                    <input type="text" id="reponse3" name="reponse3" class="form-control" value="<?php echo htmlspecialchars($_POST['reponse3'] ?? $question_data['reponse3']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="reponse4">Réponse 4</label>
                    <input type="text" id="reponse4" name="reponse4" class="form-control" value="<?php echo htmlspecialchars($_POST['reponse4'] ?? $question_data['reponse4']); ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label for="bonne_reponse">Quelle est la bonne réponse ?</label>
                <?php $br = isset($_POST['bonne_reponse']) ? $_POST['bonne_reponse'] : $question_data['bonne_reponse']; ?>
                <select id="bonne_reponse" name="bonne_reponse" class="form-control" required>
                    <option value="">-- Sélectionner --</option>
                    <option value="1" <?php echo $br == 1 ? 'selected' : ''; ?>>Réponse 1</option>
                    <option value="2" <?php echo $br == 2 ? 'selected' : ''; ?>>Réponse 2</option>
                    <option value="3" <?php echo $br == 3 ? 'selected' : ''; ?>>Réponse 3</option>
                    <option value="4" <?php echo $br == 4 ? 'selected' : ''; ?>>Réponse 4</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Enregistrer les modifications</button>
        </form>
    </div>
</div>

<?php require_once '../commun/footer.php'; ?>
