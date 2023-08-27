<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('server.php');

// Check if the user is logged in as an employee
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'employee'){
    // If not logged in as an employee, redirect to the login page
    header("Location: commun/login.php");
    exit();
}

if (isset($_POST['addInfo_perso'])) {
    $user_id = $_SESSION['user_id'];
    $profil_img = $_FILES['profil_img']['name'];
    $Nom = $_POST['Nom'];
    $prenom = $_POST['prenom'];
    $sexe = $_POST['sexe'];
    $civilite = $_POST['civilite'];
    $situation_fam = $_POST['situation_fam'];
    $tele = $_POST['tele'];
    $email_perso = $_POST['email_perso'];
    $ville = $_POST['ville'];
    $code_postal = $_POST['code_postal'];
    $adresse = $_POST['adresse'];
    $lieu_naissance = $_POST['lieu_naissance'];
    $date_naissance = $_POST['date_naissance'];
    $nationalite = $_POST['nationalite'];
    $cin = $_POST['cin'];
    $date_exp_cin = $_POST['date_exp_cin'];
    $cin_img = $_FILES['cin_img']['name'];
    $permis = $_POST['permis'];
    $type_permis = $_POST['type_permis'];
    $date_exp_permis = $_POST['date_exp_permis'];
    $permis_img = $_FILES['permis_img']['name'];

    // Move uploaded files to a permanent location
    move_uploaded_file($_FILES['profil_img']['tmp_name'], "images/profile/$profil_img");
    move_uploaded_file($_FILES['cin_img']['tmp_name'], "images/profile/$cin_img");
    move_uploaded_file($_FILES['permis_img']['tmp_name'], "images/profile/$permis_img");

    // Update the employe table
    $query = "UPDATE employe SET profil_img = ?, cin = ?, permis = ?, Nom = ?, prenom = ?, sexe = ?, civilite = ?, situation_fam = ?, tele = ?, email_perso = ?, ville = ?, code_postal = ?, adresse = ?, lieu_naissance = ?, date_naissance = ?, nationalite = ? WHERE employe_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssssssssssssssss", $profil_img, $cin, $permis, $Nom, $prenom, $sexe, $civilite, $situation_fam, $tele, $email_perso, $ville, $code_postal, $adresse, $lieu_naissance, $date_naissance, $nationalite, $user_id);
    mysqli_stmt_execute($stmt);

if (mysqli_stmt_execute($stmt)) {
    // Check if a record exists in the cin table with the same CIN for the same employee
    $query_cin_check = "SELECT * FROM cin WHERE employe_id = ? AND cin = ?";
    $stmt_cin_check = mysqli_prepare($conn, $query_cin_check);
    mysqli_stmt_bind_param($stmt_cin_check, "is", $user_id, $cin);
    mysqli_stmt_execute($stmt_cin_check);
    $result_cin_check = mysqli_stmt_get_result($stmt_cin_check);

    if (mysqli_num_rows($result_cin_check) > 0) {
        // Update the existing record in the cin table
        $query_cin_update = "UPDATE cin SET date_update_cin = NOW() WHERE employe_id = ? AND cin = ?";
        $stmt_cin_update = mysqli_prepare($conn, $query_cin_update);
        mysqli_stmt_bind_param($stmt_cin_update, "is", $user_id, $cin);
        mysqli_stmt_execute($stmt_cin_update);
    } else {
        // Insert a new record in the cin table
        $query_cin_insert = "INSERT INTO cin (cin, cin_img, employe_id, date_exp_cin, date_update_cin) VALUES (?, ?, ?, ?, NOW())";
        $stmt_cin_insert = mysqli_prepare($conn, $query_cin_insert);
        mysqli_stmt_bind_param($stmt_cin_insert, "ssis", $cin, $cin_img, $user_id, $date_exp_cin);
        mysqli_stmt_execute($stmt_cin_insert);
    }
        // Check if a record exists in the permis table for the employe_id
        
  // Check if a record exists in the permis table with the same permis for the same employee
$query_permis_check = "SELECT * FROM permis WHERE employe_id = ? AND permis = ?";
$stmt_permis_check = mysqli_prepare($conn, $query_permis_check);
mysqli_stmt_bind_param($stmt_permis_check, "is", $user_id, $permis);
mysqli_stmt_execute($stmt_permis_check);
$result_permis_check = mysqli_stmt_get_result($stmt_permis_check);

if (mysqli_num_rows($result_permis_check) > 0) {
    // Update the existing record in the permis table
    $query_permis_update = "UPDATE permis SET date_update_permis = NOW() WHERE employe_id = ? AND permis = ?";
    $stmt_permis_update = mysqli_prepare($conn, $query_permis_update);
    mysqli_stmt_bind_param($stmt_permis_update, "is", $user_id, $permis);
    mysqli_stmt_execute($stmt_permis_update);

} else {
    // Insert a new record in the permis table
    $query_permis_insert = "INSERT INTO permis (permis, permis_img, employe_id, type_permis, date_exp_permis, date_update_permis) VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt_permis_insert = mysqli_prepare($conn, $query_permis_insert);
    mysqli_stmt_bind_param($stmt_permis_insert, "ssiss", $permis, $permis_img, $user_id, $type_permis, $date_exp_permis);
    mysqli_stmt_execute($stmt_permis_insert);
}

    echo '
    <html>
    <body>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        swal({
            icon: "success",
            text: "Les informations sont bien ajoutés !",
            showConfirmButton: true,
            confirmButtonText: "Cerrar",
            closeOnConfirm: false
        }).then(function(result){
            window.location = "employe.php";
        })
    </script>     
    </body>
    </html>';
} else {
echo "<script>alert('Erreur lors de l'enregistrement des informations ! ');</script>";

}        mysqli_stmt_close($stmt);
}
                    // Update info banque 

if (isset($_POST['update_banque'])) {
    // Retrieve form input
    $user_id = $_SESSION['user_id'];
    $nom_banque = $_POST['nom_banque'];
    $rib = $_POST['rib'];
    $iban = $_POST['iban'];
    
    // Update date
    $update_date = date("Y-m-d H:i:s"); // Current date and time
    
    // Perform database insertion
    $sql = "INSERT INTO banque (employe_id, nom_banque, rib, iban, date) VALUES ('$user_id', '$nom_banque', '$rib', '$iban', '$update_date')";
    
    // Execute the query
    if (mysqli_query($conn, $sql)) {
        echo '
        <html>
        <body>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script>
            swal({
                icon: "success",
                text: "Les informations sont bien ajoutés !",
                showConfirmButton: true,
                confirmButtonText: "Cerrar",
                closeOnConfirm: false
            }).then(function(result){
                window.location = "employe.php";
            })
        </script>     
        </body>
        </html>';    }
    else {
            echo "<script>alert('Erreur lors de l'enregistrement des informations ! ');</script>";
    }
}
?>




