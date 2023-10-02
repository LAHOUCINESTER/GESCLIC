<?php
include('server.php');

// Récupérer la valeur de l'email de l'employé
$employeeEmail = $_POST['email'];
$employeeCIN = $_POST['cin'];
$employeeID = $_POST['employeeID'];
$date = date('Y-m-d');

// Code pour se connecter à la base de données et exécuter la requête de mise à jour
$updateQuery = "UPDATE employe SET id_entreprise='$entreprise_id'  WHERE employe_id='$employeeID'";
$insertQuery = "INSERT INTO employe_entreprise (employe_id, id_entreprise, DateEmbauche) VALUES ('$employeeID', '$entreprise_id', '$date')";
// Exécuter la requête de mise à jour
$addSuccess = mysqli_query($conn, $updateQuery);
$insert = mysqli_query($conn, $insertQuery);

// Si la mise à jour est réussie
if ($addSuccess) {
  // Vérifier si des lignes ont été affectées par la mise à jour
  if (mysqli_affected_rows($conn) > 0) {
    $response = array(
      'status' => 'success'
    );
  } else {
    $response = array(
      'status' => 'error',
      'message' => 'Aucune modification n\'a été effectuée. Vérifiez l\'ID de l\'employé.'
    );
  }
} else {
  // Erreur lors de l'exécution de la requête
  $response = array(
    'status' => 'error',
    'message' => 'Une erreur s\'est produite : ' . mysqli_error($conn)
  );
}

// Fermer la connexion à la base de données
mysqli_close($conn);

// Retourner la réponse au format JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
