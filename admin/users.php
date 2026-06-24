<?php
require_once '../commun/includes/db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /qcm/index.php");
    exit;
}

// Traitement des actions (Bloquer, Débloquer, Supprimer)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $action = $_GET['action'];
    
    // On empêche l'admin de se modifier lui-même
    if ($id != $_SESSION['utilisateur_id']) {
        if ($action === 'bloquer') {
            mysqli_query($conn, "UPDATE utilisateurs SET statut = 'bloque' WHERE id = $id");
        } elseif ($action === 'debloquer') {
            mysqli_query($conn, "UPDATE utilisateurs SET statut = 'actif' WHERE id = $id");
        } elseif ($action === 'supprimer') {
            // Suppression en cascade théorique (ou via BDD). Ici on supprime d'abord les réponses
            // Pour simplifier et respecter le code procédural basique :
            // (La vraie bonne pratique serait d'utiliser ON DELETE CASCADE dans la BDD)
            mysqli_query($conn, "DELETE FROM utilisateurs WHERE id = $id");
        }
    }
    header("Location: users.php");
    exit;
}

// Récupération de tous les utilisateurs
$sql = "SELECT * FROM utilisateurs ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

require_once '../commun/includes/header.php';
?>

<div class="container">
    <div class="qcm-header">
        <h2>Gestion des Utilisateurs</h2>
        <a href="dashboard.php" class="btn btn-outline">Retour au Dashboard</a>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo htmlspecialchars($user['nom']); ?></td>
                    <td><?php echo htmlspecialchars($user['prenom']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo $user['role'] === 'admin' ? '<span class="user-badge" style="color:var(--accent-color);">Admin</span>' : 'Utilisateur'; ?></td>
                    <td>
                        <?php if ($user['statut'] === 'actif') : ?>
                            <span style="color:var(--success-color); font-weight:bold;">Actif</span>
                        <?php else : ?>
                            <span style="color:var(--danger-color); font-weight:bold;">Bloqué</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($user['id'] != $_SESSION['utilisateur_id']) : ?>
                            <?php if ($user['statut'] === 'actif') : ?>
                                <a href="users.php?action=bloquer&id=<?php echo $user['id']; ?>" class="btn btn-outline" style="padding:0.25rem 0.5rem; font-size:0.875rem;">Bloquer</a>
                            <?php else : ?>
                                <a href="users.php?action=debloquer&id=<?php echo $user['id']; ?>" class="btn btn-primary" style="padding:0.25rem 0.5rem; font-size:0.875rem;">Débloquer</a>
                            <?php endif; ?>
                            <a href="users.php?action=supprimer&id=<?php echo $user['id']; ?>" class="btn btn-danger" style="padding:0.25rem 0.5rem; font-size:0.875rem;" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">Supprimer</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../commun/footer.php'; ?>
