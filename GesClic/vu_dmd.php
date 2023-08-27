<?php
include('server.php');

if (isset($_GET['id'])) {
    $requestId = $_GET['id'];

    // Mettez à jour le statut "vu" dans la base de données pour la demande spécifiée
    $updateQuery = "UPDATE demande SET vu = 'Oui' WHERE N_dmd = '$requestId'";
    $updateResult = mysqli_query($conn, $updateQuery);

    if ($updateResult) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>
