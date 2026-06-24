<?php
// Démarrage de la session uniquement si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QCM Pro - Évaluation en ligne</title>
    <!-- Lien vers le fichier CSS commun -->
    <link rel="stylesheet" href="/qcm/commun/assets/css/style.css">
    <!-- Police Inter pour un design moderne -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="container nav-content">
                <a href="/qcm/index.php" class="brand">QCM<span>Pro</span></a>
                <ul class="nav-links">
                    <?php if (isset($is_qcm_active) && $is_qcm_active) : ?>
                        <!-- Mode QCM : On cache tout sauf le bouton d'annulation -->
                        <li><a href="/qcm/qcm/process.php?cancel=1" class="btn btn-danger" onclick="return confirm('Attention, annuler le test vous donnera une note de 0. Continuer ?');">Annuler la tentative</a></li>
                    <?php else : ?>
                        <!-- Navigation normale -->
                        <?php if (isset($_SESSION['utilisateur_id'])) : ?>
                            <li><a href="/qcm/index.php">Accueil</a></li>
                            <li><a href="/qcm/qcm/history.php">Mon Historique</a></li>
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') : ?>
                                <li><a href="/qcm/admin/dashboard.php" class="admin-link">Dashboard Admin</a></li>
                            <?php endif; ?>
                            <li><span class="user-badge">👤 <?php echo htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']); ?></span></li>
                            <li><a href="/qcm/auth/deconnexion.php" class="btn btn-outline">Déconnexion</a></li>
                        <?php else : ?>
                            <li><a href="/qcm/auth/connexion.php">Connexion</a></li>
                            <li><a href="/qcm/auth/inscription.php" class="btn btn-primary">Inscription</a></li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>
    
    <!-- Le contenu principal commence ici, il sera fermé dans footer.php -->
    <main class="main-content">
        <div class="container">
