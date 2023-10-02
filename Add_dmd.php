<?php
session_start();
include('server.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
function getAdminIdForEmployee($employee_id, $conn) {
    $query = "SELECT ae.id_admin FROM admin_entreprise ae INNER JOIN employe ON ae.id_entreprise = employe.id_entreprise WHERE employe.employe_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $employee_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    return $row['id_admin'];
}
$stmt = null;
if (isset($_POST['envoi_dmd'])) {
    // Check if the user is logged in
    if (isset($_SESSION['user_id']) && isset($_SESSION['user_type'])) {
        $date_dmnd = $_POST['DateSoumission'];
        $type_dmd = $_POST['type_dmd'];
        $dscr_dmd = $_POST['dscr_dmd'];
        $id_conge = null;
        $id_bltn = null;
        $employe_id_destinataire = null;
        $adminID_destinataire = null;

        if ($_SESSION['user_type'] === 'employeur') {
            $admin_id = $_SESSION['user_id'];
            $cin_employe = $_POST['cin'];

            // Query to get the employee ID based on CIN
            $query_employe = "SELECT employe_id FROM employe WHERE cin = ?";
            $stmt_employe = mysqli_prepare($conn, $query_employe);
            mysqli_stmt_bind_param($stmt_employe, "s", $cin_employe);
            mysqli_stmt_execute($stmt_employe);
            $result_employe = mysqli_stmt_get_result($stmt_employe);
            $row_employe = mysqli_fetch_assoc($result_employe);

            if ($row_employe) {
                $employe_id_destinataire = $row_employe['employe_id'];
                // Insert the request with appropriate values
                $sql = "INSERT INTO demande (id_admin, DateSoumission, type_dmd, dscr_dmd, employeID_destinataire) VALUES (?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "issss", $admin_id, $date_dmnd, $type_dmd, $dscr_dmd, $employe_id_destinataire);

                if (mysqli_stmt_execute($stmt)) {
                    // Success message
                    echo '
                    <html>
                    <body>
                    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
                    <script>
                        swal({
                            icon: "success",
                            text: "La demande a été ajoutée avec succès!",
                            showConfirmButton: true,
                            confirmButtonText: "Cerrar",
                            closeOnConfirm: false
                        }).then(function(result){
                            window.location = "do_dmnd.php";
                        })
                    </script>     
                    </body>
                    </html>';
                } else {
                    // Error message for database insertion failure
                    echo "<script>alert('Demande n'est pas envoyé !');</script>";
                    echo "<script>window.location.href='do_dmnd.php'</script>";

                }
            } else {
                // Error message for invalid CIN
                echo "<script>alert('Invalide cin !');</script>";
                echo "<script>window.location.href='do_dmnd.php'</script>";

            }

            // Close the prepared statement for employee query
            mysqli_stmt_close($stmt_employe);
        } elseif ($_SESSION['user_type'] === 'employee') {
            $employe_id = $_SESSION['user_id'];
            $adminID_destinataire = getAdminIdForEmployee($employe_id, $conn);
                // Requête préparée pour insérer la demande avec l'employe_id récupéré
                $sql = "INSERT INTO demande (employe_id, DateSoumission, type_dmd, dscr_dmd, adminID_destinataire, id_conge, id_bltn, id_recup) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                $id_conge = null;
                $id_bltn = null;
            if ($type_dmd === 'Congé') {
                $dateDebut_conge = $_POST['DateDebut_conge'];
                $dateFin_conge = $_POST['DateFin_conge'];
                $type_conge = $_POST['type_conge'];
                $periode = $_POST['periode'];

                $sql_conge = "INSERT INTO conges (DateDebut_conge, DateFin_conge, type_conge, periode) VALUES (?, ?, ?, ?)";
                $stmt_conge = mysqli_prepare($conn, $sql_conge);
                mysqli_stmt_bind_param($stmt_conge, "ssss", $dateDebut_conge, $dateFin_conge, $type_conge, $periode);

                if (mysqli_stmt_execute($stmt_conge)) {
                    $id_conge = mysqli_insert_id($conn);
                }

                mysqli_stmt_close($stmt_conge);
            } elseif ($type_dmd === 'Bulletin de paie') {
                $mois = $_POST['mois'];
                $annee = $_POST['annee'];

                $sql_bltn = "INSERT INTO paie (mois, annee) VALUES (?, ?)";
                $stmt_bltn = mysqli_prepare($conn, $sql_bltn);
                mysqli_stmt_bind_param($stmt_bltn, "ss", $mois, $annee);

                if (mysqli_stmt_execute($stmt_bltn)) {
                    $id_bltn = mysqli_insert_id($conn);
                }

                mysqli_stmt_close($stmt_bltn);
            }elseif ($type_dmd === 'Récupération') {
                $dateDebut_recup = $_POST['DateDebut_recup'];
                $dateFin_recup = $_POST['DateFin_recup'];
                $sql_recup = "INSERT INTO recuperation (DateDebut_recup, DateFin_recup) VALUES (?, ?)";
                $stmt_recup = mysqli_prepare($conn, $sql_recup);
                mysqli_stmt_bind_param($stmt_recup, "ss", $dateDebut_recup, $dateFin_recup);
            
                if (mysqli_stmt_execute($stmt_recup)) {
                    $id_recup = mysqli_insert_id($conn);
                }
            
                mysqli_stmt_close($stmt_recup);
            }
            // Lier les paramètres et exécuter la requête préparée pour la demande
            mysqli_stmt_bind_param($stmt, "isssiiii", $employe_id, $date_dmnd, $type_dmd, $dscr_dmd, $adminID_destinataire, $id_conge, $id_bltn, $id_recup);
            if (mysqli_stmt_execute($stmt)) {
                // Success message
                echo '
                        <html>
                        <body>
                        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
                        <script>
                            swal({
                                icon: "success",
                                text: "La demande a été ajoutée avec succès!",
                                showConfirmButton: true,
                                confirmButtonText: "Cerrar",
                                closeOnConfirm: false
                            }).then(function(result){
                                window.location = "do_dmnd.php";
                            })
                        </script>     
                        </body>
                        </html>';
            } else {
                // Error message for database insertion failure
                echo "<script>alert('Demande n'est pas envoyée !');</script>";
                echo "<script>window.location.href='do_dmnd.php'</script>";


            }
        }
    } else {
              // Error message for not being logged in
              echo '
                        <html>
                        <body>
                        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
                        <script>
                            swal({
                                icon: "Error",
                                text: "Vous devez vous connecter !",
                                showConfirmButton: true,
                                confirmButtonText: "Cerrar",
                                closeOnConfirm: false
                            }).then(function(result){
                                window.location = "login.php";
                            })
                        </script>     
                        </body>
                        </html>';

          }
      }
      
      // Close the prepared statement

        mysqli_stmt_close($stmt);

    

      
      // Close the database connection
      mysqli_close($conn);
      ?>
      