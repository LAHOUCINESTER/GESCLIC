<?php
$servername = 'localhost';
$username = 'root';
$password = '';
$db = 'gesclic';
// On établit la connexion
$conn = mysqli_connect($servername, $username, $password, $db);
if (!$conn) {
    echo "Connexion interrompue";
}
?>