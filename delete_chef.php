<?php
include('server.php');



if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Si l'ID de l'employé n'est pas fourni, rediriger vers la liste des employés
    header("Location: list_chef.php");
    exit();
}

$chef_id = $_GET['id'];

// Vérifier si le chef existe dans la base de données
$query = "SELECT * FROM chef WHERE chef_id = '$chef_id'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$email = $row['email'];

if (!$row) {
    // Si le chef n'existe pas, rediriger vers la liste des chefs
    header("Location: list_chef.php");
    exit();
}

if (isset($_POST['supprimer_chef'])) {
    // Supprimer le chef de la base de données
    $deleteEmployeesQuery = "DELETE FROM login WHERE email = '$email'";
    $deleteChef = "UPDATE chef SET archive = '1'  WHERE chef_id = '$chef_id'";

    if (mysqli_query($conn, $deleteEmployeesQuery) && mysqli_query($conn, $deleteChef)) {

        // Set a success message
        $_SESSION['success_chef'] = "Le chef a été supprimé avec succès.";
    } else {
        // Set an error message
        $_SESSION['error_chef'] = "Une erreur s'est produite lors de la suppression du chef.";
    }
    // Rediriger vers la liste des chefs après la suppression
    header("Location: list_chef.php");
    exit();
}
?>