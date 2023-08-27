<?php
// Inclure votre fichier de configuration de la base de données
include('db.php');

// Vérifier si le formulaire de réponse a été soumis
if (isset($_POST['response'])) {
    $requestId = $_POST['requestId'];
    $responseText = $_POST['response'];

    // Effectuer la mise à jour de la demande avec la réponse dans la base de données
    $updateQuery = "UPDATE demande SET reponse = ? WHERE N_dmd = ?";
    $stmt = mysqli_prepare($conn, $updateQuery);
    mysqli_stmt_bind_param($stmt, "si", $responseText, $requestId);

    if (mysqli_stmt_execute($stmt)) {
        // La mise à jour a réussi
        echo "<script>alert('Réponse ajoutée avec succès');</script>";
        echo "<script>window.location.href='demandes_recues.php'</script>";

        exit();
    } else {
        // Erreur lors de la mise à jour
        echo "<script>alert('Erreur lors de l'ajout de la réponse ! Veuillez réessayer plus tard ');</script>";
        echo "<script>window.location.href='demandes_recues.php'</script>";
        exit();
    }
} else {
    // Rediriger si le formulaire n'a pas été soumis
    header("Location: demandes_recues.php");
    exit();
}
?>