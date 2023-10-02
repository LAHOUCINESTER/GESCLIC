<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('server.php');

// Check if the user is logged in as an employee
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'employeur'){
    // If not logged in as an employee, redirect to the login page
    header("Location: login.php");
    exit();
}

if (isset($_POST['add_ste'])) {
    $patente = $_POST['patente'];
    $fiscale = $_POST['fiscale'];
    $ice = $_POST['ice'];
    $cnss = $_POST['cnss'];
    $tp = $_POST['tp'];
    $rc = $_POST['rc'];
    $comp_assurance = $_POST['comp_assurance'];
    $caisse_retraite = $_POST['caisse_retraite'];
    $Nom_Entreprise = $_POST['Nom_Entreprise'];
    $Contact = $_POST['Contact'];
    $forme = $_POST['forme'];
    $activite = $_POST['activite'];
    $delegation = $_POST['delegation'];
    $region = $_POST['region'];
    $ville = $_POST['ville'];
    $email = $_POST['email'];
    $fax = $_POST['fax'];
    $Contact = $_POST['Contact'];
    $adress1 = $_POST['adress1'];
    $adress2 = $_POST['adress2'];
    $web = $_POST['web'];
  
    // Vérifier si une nouvelle image de profil a été téléchargée
    if (!empty($_FILES['ste_img']['name'])) {
        $ste_img = uniqid() . '_' . $_FILES['ste_img']['name'];
        move_uploaded_file($_FILES['ste_img']['tmp_name'], "images/societe/$ste_img");
    } else {
        // Récupérer le nom de l'image actuelle dans la base de données
        $query = "SELECT ste_img FROM entreprises WHERE id_entreprise = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $entreprise_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $current_ste_img);
        mysqli_stmt_store_result($stmt); // Ajout de cette ligne
        mysqli_stmt_fetch($stmt);
        
        $ste_img = $current_ste_img;
    }
    // Update the employe table
    $query = "UPDATE entreprises SET ste_img = ?, patente = ?, fiscale = ?, ice = ?, cnss = ?, tp = ?, rc = ?, comp_assurance = ?, caisse_retraite = ?, Nom_Entreprise = ?, Contact = ?, forme = ?, activite = ?, delegation = ?, region = ?, ville = ?, email = ?, fax = ?, adress1 = ?, adress2 = ?, web = ? WHERE id_entreprise = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssssssssssssssssssssss", $ste_img, $patente, $fiscale, $ice, $cnss, $tp, $rc, $comp_assurance, $caisse_retraite, $Nom_Entreprise, $Contact, $forme, $activite, $delegation, $region, $ville, $email, $fax, $adress1, $adress2, $web, $entreprise_id);

if (mysqli_stmt_execute($stmt)) {
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
            window.location = "societe.php";
        })
    </script>     
    </body>
    </html>';
} else {
echo "<script>alert('Erreur lors de l'enregistrement des informations ! ');</script>";

}        mysqli_stmt_close($stmt);
}
                    // Update info banque 

if (isset($_POST['add_banq'])) {
    // Retrieve form input
    $nom_banque = $_POST['nom_banque'];
    $rib = $_POST['rib'];
    $iban = $_POST['iban'];
    
    // Update date
    $update_date = date("Y-m-d H:i:s"); // Current date and time
    
    // Perform database insertion
    $sql = "INSERT INTO banque (id_entreprise, nom_banque, rib, iban, date) VALUES ('$entreprise_id', '$nom_banque', '$rib', '$iban', '$update_date')";
    
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
                window.location = "societe.php";
            })
        </script>     
        </body>
        </html>';    }
    else {
            echo "<script>alert('Erreur lors de l'enregistrement des informations ! ');</script>";
    }
}
// Add info professionnelle


?>




