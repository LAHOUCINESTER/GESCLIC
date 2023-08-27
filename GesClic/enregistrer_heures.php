<?php
include('server.php');
// Vérifier si l'utilisateur est connecté en tant qu'employé
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'employee') {
    // Si l'utilisateur n'est pas connecté en tant qu'employé, redirigez vers la page de connexion
    header("Location: commun/login.php");
    exit();
}
// Vérifier si le formulaire est soumis pour pointer l'entrée ou la sortie
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"]; // action cachée pour savoir s'il s'agit d'une entrée ou d'une sortie
    $date = $_POST['date'];
    $heure_actuelle = $_POST['heure_actuelle'];
    $heure_entree = $_POST['h_entree'];
    $heure_sortie = $_POST['h_sortie'];
    // Récupérer l'ID de l'employé depuis la session
    $employeId = $_SESSION['user_id'];

    //  connexion à la base de données MySQL 
    include('db.php');
    // Vérifier la connexion
    if ($conn->connect_error) {
        die("La connexion a échoué : " . $conn->connect_error);
    }
        // Échappez les valeurs pour éviter les injections SQL (à faire correctement avec les vraies données)
        $date = $conn->real_escape_string($date);
        $heure_actuelle = $conn->real_escape_string($heure_actuelle);

        // Vérifier si l'employé a déjà pointé son entrée pour la date actuelle
        $sql_check_entry = "SELECT * FROM pointer WHERE employe_id = '$employeId' AND date = '$date' AND h_entree IS NOT NULL";
        $result_check_entry = $conn->query($sql_check_entry);

        // Si l'employé n'a pas encore pointé son entrée pour la date actuelle, enregistrez le pointage d'entrée
        if ($action === "clock_in" && $result_check_entry->num_rows === 0) {
            $sql = "INSERT INTO pointer (employe_id, date, h_entree_sys, h_entree) VALUES ('$employeId', '$date','$heure_actuelle', '$heure_entree')";
        } elseif ($action === "clock_out") {
            // Mise à jour du pointage de sortie pour l'employé et la date spécifiée
            $sql = "UPDATE pointer SET h_sortie = '$heure_actuelle', h_sortie_sys = '$heure_sortie' WHERE employe_id = '$employeId' AND date = '$date'";
            }
            if ($conn->query($sql) === TRUE) {
                echo '
                <html>
                <body>
                <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
                <script>
                    swal({
                        icon: "success",
                        text: "L\'heure a été ajoutée avec succès!",
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
                echo '
                <html>
                <body>
                <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
                <script>
                    swal({
                        icon: "error",
                        text: "Erreur lors de l\'enregistrement de l\'heure!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar",
                        closeOnConfirm: false
                    }).then(function(result){
                        window.location = "employe.php";
                    })
                </script>     
                </body>
                </html>';
            }

            // Fermer la connexion à la base de données
            $conn->close();
}
?>
