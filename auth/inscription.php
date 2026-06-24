<?php
require_once '../commun/includes/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirection si déjà connecté
if (isset($_SESSION['utilisateur_id'])) {
    header("Location: /qcm/index.php");
    exit;
}

$erreur = '';
$succes = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';

    if (empty($nom) || empty($prenom) || empty($email) || empty($mot_de_passe)) {
        $erreur = "Tous les champs sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreur = "Format d'email invalide.";
    } else {
        // Prévention injection SQL manuelle (sans PDO)
        $email_escaped = mysqli_real_escape_string($conn, $email);
        $sql_check = "SELECT id FROM utilisateurs WHERE email = '$email_escaped'";
        $result_check = mysqli_query($conn, $sql_check);
        
        if (mysqli_num_rows($result_check) > 0) {
            $erreur = "Cet email est déjà utilisé.";
        } else {
            $nom_escaped = mysqli_real_escape_string($conn, $nom);
            $prenom_escaped = mysqli_real_escape_string($conn, $prenom);
            // Hashage du mot de passe
            $hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);
            $hash_escaped = mysqli_real_escape_string($conn, $hash);
            
            $sql_insert = "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe) VALUES ('$nom_escaped', '$prenom_escaped', '$email_escaped', '$hash_escaped')";
            
            if (mysqli_query($conn, $sql_insert)) {
                $succes = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
            } else {
                $erreur = "Erreur lors de l'inscription : " . mysqli_error($conn);
            }
        }
    }
}

require_once '../commun/includes/header.php';
?>

<div class="form-container">
    <h2 class="text-center">Créer un compte</h2>
    
    <?php if (!empty($erreur)) : ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($erreur); ?></div>
    <?php endif; ?>
    
    <?php if (!empty($succes)) : ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($succes); ?></div>
        <div class="text-center" style="margin-top: 1rem;">
            <a href="/qcm/auth/connexion.php" class="btn btn-primary">Aller à la connexion</a>
        </div>
    <?php else : ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" class="form-control" required value="<?php echo htmlspecialchars($_POST['nom'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom" class="form-control" required value="<?php echo htmlspecialchars($_POST['prenom'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="email">Adresse email</label>
                <input type="email" id="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="mot_de_passe">Mot de passe</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" class="form-control" required>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%;">S'inscrire</button>
        </form>
        <p class="text-center" style="margin-top: 1.5rem;">
            Déjà un compte ? <a href="/qcm/auth/connexion.php">Connectez-vous</a>
        </p>
    <?php endif; ?>
</div>

<?php require_once '../commun/footer.php'; ?>
