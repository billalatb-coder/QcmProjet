<?php
// Inclusion des fichiers communs (Base de données et Header)
require_once 'commun/includes/db.php';
require_once 'commun/includes/header.php';
?>

<!-- Section Héro (Accueil) -->
<section class="hero text-center">
    <h1 class="hero-title">Bienvenue sur QCM<span>Pro</span></h1>
    <p class="hero-subtitle">La plateforme d'évaluation en ligne la plus performante et sécurisée.</p>
    
    <div class="hero-actions">
        <!-- Affichage conditionnel selon si l'utilisateur est connecté ou non -->
        <?php if (isset($_SESSION['utilisateur_id'])) : ?>
            <a href="/qcm/qcm/start.php" class="btn btn-primary btn-lg">Lancer un QCM (10 questions)</a>
            <a href="/qcm/qcm/history.php" class="btn btn-outline btn-lg">Consulter mon historique</a>
        <?php else : ?>
            <a href="/qcm/auth/inscription.php" class="btn btn-primary btn-lg">S'inscrire gratuitement</a>
            <a href="/qcm/auth/connexion.php" class="btn btn-outline btn-lg">Déjà un compte ? Connexion</a>
        <?php endif; ?>
    </div>
</section>

<!-- Section des Fonctionnalités -->
<section class="features">
    <h2 class="section-title text-center">Pourquoi utiliser QCM Pro ?</h2>
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon">🎲</div>
            <h3>Génération Aléatoire</h3>
            <p>10 questions choisies au hasard parmi notre base de données pour un test unique à chaque tentative.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🛡️</div>
            <h3>Sécurité Anti-Triche</h3>
            <p>Mode plein écran obligatoire, suivi des changements d'onglets et limite de temps stricte (10 min).</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">📊</div>
            <h3>Suivi Détaillé</h3>
            <p>Correction complète en fin de test et conservation d'un historique pour analyser votre progression.</p>
        </div>
    </div>
</section>

<?php
// Inclusion du pied de page
require_once 'commun/footer.php';
?>
