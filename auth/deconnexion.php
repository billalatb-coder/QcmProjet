<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Détruire toutes les variables de session
$_SESSION = array();

// Si vous voulez détruire complètement la session, effacez également le cookie de session
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Détruire la session
session_destroy();

// Redirection vers la page d'accueil
header("Location: /qcm/index.php");
exit;
?>
