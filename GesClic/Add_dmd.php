<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('server.php');
function getAdminIdForEmployee($employe_id, $conn) {
    $query = "SELECT administrateurs.id_admin FROM administrateurs INNER JOIN employe ON administrateurs.id_entreprise = employe.id_entreprise WHERE employe.employe_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $employe_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    return $row['id_admin'];
}
if (isset($_POST['envoi_dmd'])) {
    // Vérifier si l'ID de l'employé est disponible dans la session
        if (isset($_SESSION['user_id'])) {
            $employe_id = $_SESSION['user_id'];
            $adminID_destinataire = getAdminIdForEmployee($employe_id, $conn);

            $date_dmnd = $_POST['DateSoumission'];
            $type_dmd = $_POST['type_dmd'];
            $dscr_dmd = $_POST['dscr_dmd'];

            // Requête préparée pour insérer la demande avec l'employe_id récupéré
            $sql = "INSERT INTO demande (employe_id, DateSoumission, type_dmd, dscr_dmd, adminID_destinataire, id_conge, id_bltn) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            $id_conge = null;
            $id_bltn = null;
                // Si la demande est un congé, ajoutez également une entrée à la table "conges"
                    if ($type_dmd === 'Congé') {
                        $dateDebut_conge = $_POST['DateDebut_conge'];
                        $dateFin_conge = $_POST['DateFin_conge'];
                        $type_conge = $_POST['type_conge'];
                        $periode = $_POST['periode'];

                        // Requête préparée pour insérer le congé associé à la demande
                        $sql_conge = "INSERT INTO conges (DateDebut_conge, DateFin_conge, type_conge, periode) VALUES (?, ?, ?, ?)";
                        $stmt_conge = mysqli_prepare($conn, $sql_conge);
                        mysqli_stmt_bind_param($stmt_conge, "ssss", $dateDebut_conge, $dateFin_conge, $type_conge, $periode);
                        // Exécuter la requête préparée pour le congé
                        if (mysqli_stmt_execute($stmt_conge)) {
                            // Récupérer l'ID du congé inséré
                            $id_conge = mysqli_insert_id($conn);
                        }
                        // Fermer la déclaration préparée pour le congé
                        mysqli_stmt_close($stmt_conge);
                    }
                    // Si la demande est un Bulletin de paie 
                    else if ($type_dmd === 'Bulletin de paie') {
                        $mois = $_POST['mois'];

                        // Requête préparée pour insérer le congé associé à la demande
                        $sql_bltn = "INSERT INTO paie (mois) VALUES (?)";
                        $stmt_bltn = mysqli_prepare($conn, $sql_bltn);
                        mysqli_stmt_bind_param($stmt_bltn, "s", $mois);
                        // Exécuter la requête préparée pour le congé
                        if (mysqli_stmt_execute($stmt_bltn)) {
                            // Récupérer l'ID du congé inséré
                            $id_bltn = mysqli_insert_id($conn);
                        }
                        // Fermer la déclaration préparée pour le congé
                        mysqli_stmt_close($stmt_bltn);
                    }
                // Lier les paramètres et exécuter la requête préparée pour la demande
                mysqli_stmt_bind_param($stmt, "isssiii", $employe_id, $date_dmnd, $type_dmd, $dscr_dmd, $adminID_destinataire, $id_conge, $id_bltn);
                if (mysqli_stmt_execute($stmt)) {
                }  echo '
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
                echo "<script>alert('Demande n'est pas envoyé !');</script>";

            }        mysqli_stmt_close($stmt);

    } else {
        // Si l'ID de l'employé n'est pas disponible dans la session, afficher un message d'erreur approprié
        echo "Erreur : ID de l'employé non défini.";
    }

?>
