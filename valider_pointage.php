<?php
session_start();
include('server.php');

if (isset($_POST['valider_pe'])) {
    $point_id = $_POST['point_id'];
    $h_entree = $_POST['h_entree'];
    $chef_id = $_SESSION['user_id'];
    // Vérifiez si l'employé est connecté en tant que chef
    if ($_SESSION['user_type'] === 'chef') {
        // Mise à jour des heures de pointage dans la base de données
        $update_queryv= "UPDATE pointer SET h_entree_chef = '$h_entree', chef_id= '$chef_id' , statut ='Présent' WHERE point_id = '$point_id'";
        $update_resultv = mysqli_query($conn, $update_queryv);
        
        if ($update_resultv) {
            // Redirigez l'utilisateur vers la page de pointage avec un message de succès
            echo "<script>alert('Pointage d'entrée enregistré avec succès');</script>";
            echo "<script>window.location.href='valider_p.php'</script>";
        } else {
            // Redirigez l'utilisateur vers la page de pointage avec un message d'erreur
            echo "Erreur de mise à jour : " . mysqli_error($conn);

        }
    }
} else if (isset($_POST['valider_ps'])) {
    $point_id = $_POST['point_id'];
    $h_sortie = $_POST['h_sortie'];
    $chef_id = $_SESSION['user_id'];

    // Vérifiez si l'employé est connecté en tant que chef
    if ($_SESSION['user_type'] === 'chef') {
        // Mise à jour des heures de pointage dans la base de données
        $update_queryv = "UPDATE pointer SET h_sortie_chef = '$h_sortie', chef_id= '$chef_id' statut ='Présent' WHERE point_id = '$point_id'";
        $update_resultv = mysqli_query($conn, $update_queryv);

        if ($update_resultv) {
            // Redirigez l'utilisateur vers la page de pointage avec un message de succès
            echo "<script>alert('Pointage de sortie enregistré avec succès');</script>";
            echo "<script>window.location.href='valider_p.php'</script>";
        } else {
            // Redirigez l'utilisateur vers la page de pointage avec un message d'erreur
            echo "<script>alert('Erreur lors de la validation de pointage de sortie !');</script>";
            echo "<script>window.location.href='valider_p.php'</script>";         
        }
    }
} else {
    // Redirigez l'utilisateur vers la page de pointage en cas d'accès incorrect
    echo "<script>window.location.href='valider_p.php'</script>";
    exit();
}