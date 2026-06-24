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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';

    if (empty($email) || empty($mot_de_passe)) {
        $erreur = "Veuillez remplir tous les champs.";
    } else {
        $email_escaped = mysqli_real_escape_string($conn, $email);
        $sql = "SELECT * FROM utilisateurs WHERE email = '$email_escaped'";
        $result = mysqli_query($conn, $sql);
        
        if ($user = mysqli_fetch_assoc($result)) {
            if ($user['statut'] === 'bloque') {
                $erreur = "Votre compte a été bloqué par un administrateur.";
            } elseif (password_verify($mot_de_passe, $user['mot_de_passe'])) {
                // Stockage des informations en session
                $_SESSION['utilisateur_id'] = $user['id'];
                $_SESSION['nom'] = $user['nom'];
                $_SESSION['prenom'] = $user['prenom'];
                $_SESSION['role'] = $user['role'];
                
                header("Location: /qcm/index.php");
                exit;
            } else {
                $erreur = "Email ou mot de passe incorrect.";
            }
        } else {
            $erreur = "Email ou mot de passe incorrect.";
        }
    }
}

require_once '../commun/includes/header.php';
?>

<div class="form-container">
    <h2 class="text-center">Connexion</h2>
    
    <?php if (!empty($erreur)) : ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($erreur); ?></div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div class="form-group">
            <label for="email">Adresse email</label>
            <input type="email" id="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
            <label for="mot_de_passe">Mot de passe</label>
            <input type="password" id="mot_de_passe" name="mot_de_passe" class="form-control" required>
        </div>
        
        <button type="submit" class="btn btn-primary" style="width: 100%;">Se connecter</button>
    </form>
    
    <p class="text-center" style="margin-top: 1.5rem;">
        Pas encore de compte ? <a href="/qcm/auth/inscription.php">Inscrivez-vous</a>
    </p>
</div>

<?php require_once '../commun/footer.php'; ?>
