<?php
require_once 'commun/includes/db.php';

$email = 'admin@qcm.local';
$mot_de_passe = 'admin123';
$hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);

$sql_check = "SELECT * FROM utilisateurs WHERE email = 'admin@qcm.local'";
$res = mysqli_query($conn, $sql_check);

if (mysqli_num_rows($res) > 0) {
    $sql_update = "UPDATE utilisateurs SET mot_de_passe = '$hash', role = 'admin' WHERE email = 'admin@qcm.local'";
    mysqli_query($conn, $sql_update);
    echo "<h1>Le compte administrateur a ete mis a jour avec succes !</h1>";
} else {
    $sql_insert = "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role) VALUES ('Admin', 'Super', 'admin@qcm.local', '$hash', 'admin')";
    mysqli_query($conn, $sql_insert);
    echo "<h1>Le compte administrateur a ete cree avec succes !</h1>";
}

echo "<p>Vous pouvez maintenant vous connecter avec :<br>Email : <b>admin@qcm.local</b><br>Mot de passe : <b>admin123</b></p>";
echo "<a href='/qcm/auth/connexion.php'>Aller a la page de connexion</a>";
?>
