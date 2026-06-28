<?php
// Paramètres de connexion
$db_host = 'localhost';
$db_user = 'root';
$db_pass = 'root'; // Pour MAMP
$db_name = 'qcm_app';

// Connexion procédurale avec mysqli (PAS DE PDO selon le cahier des charges)
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Vérification de la connexion
if (!$conn) {
    die("Échec de la connexion à la base de données : " . mysqli_connect_error());
}

// Définir le jeu de caractères utf8 pour éviter les problèmes d'accents
mysqli_set_charset($conn, "utf8");
?>