<?php
// Initialisation de la session
session_start();

// Destruction de toutes les variables de session
$_SESSION = array();

// Destruction de la session
session_destroy();

// Redirection vers la page de connexion
header("location: commun/login.php");
exit;
?>