<?php
include('server.php');
// Check if the user is logged in as an employee
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'employee' && $_SESSION['user_type'] !== 'employeur') {
    // If not logged in as an employee, redirect to the login page
    header("Location: commun/login.php");
    exit();
}?>
<?php include('head.php') ?>

  <header class=" text-center py-5 mt-5 " >
    <div class="container ">
  
      <br><h4 >Les Alertes  !</h4>
    </div>
  </header>
  <div class="container mb-4 ">
    <?php
$user_id = $_SESSION['user_id'];
       // Récupérer la date d'expiration du CIN
       $sqlCIN = "SELECT date_exp_cin FROM cin WHERE employe_id = '$user_id '"; // Remplacez 'cin' par le nom de votre table et 'employe_id' par l'identifiant de l'employé concerné

       $resultCIN = $conn->query($sqlCIN);
   
       $alertes = array(); // Tableau pour stocker les alertes
   
       if ($resultCIN->num_rows > 0) {
           $rowCIN = $resultCIN->fetch_assoc();
           $expirationCIN = $rowCIN['date_exp_cin'];
   
           // Comparer avec la date actuelle
           $currentDate = date('Y-m-d');
   
           if ($expirationCIN < $currentDate) {
               $alertes[] = "<div class='alert alert-danger '>Attention ! Votre CIN a expiré le $expirationCIN. </div>";
           } elseif ($expirationCIN == $currentDate) {
               $alertes[] = "<div class='alert alert-warning'>Attention ! Votre CIN expire aujourd'hui. </div>";
           } elseif ($expirationCIN > $currentDate) {
               // Calculer le nombre de jours restants
               $diff = strtotime($expirationCIN) - strtotime($currentDate);
               $daysRemaining = floor($diff / (60 * 60 * 24));
   
               if ($daysRemaining <= 30) {
                   $alertes[] = "<div class='alert alert-success'>Votre CIN est valide jusqu'au $expirationCIN. Il vous reste encore $daysRemaining jours. </div>";
               }
           }
       }
   
       // Récupérer la date d'expiration du permis de conduire
       $sqlPermis = "SELECT date_exp_permis FROM permis WHERE employe_id = '$user_id'"; // Remplacez 'permis' par le nom de votre table et 'employe_id' par l'identifiant de l'employé concerné
   
       $resultPermis = $conn->query($sqlPermis);
   
       if ($resultPermis->num_rows > 0) {
           $rowPermis = $resultPermis->fetch_assoc();
           $expirationPermis = $rowPermis['date_exp_permis'];
   
           // Comparer avec la date actuelle
           $currentDate = date('Y-m-d');
   
           if ($expirationPermis < $currentDate) {
               $alertes[] = "<div class='alert alert-danger'>Attention ! Votre permis de conduire a expiré le $expirationPermis. </div>";
           } elseif ($expirationPermis == $currentDate) {
               $alertes[] = "<div class='alert alert-warning'>Attention ! Votre permis de conduire expire aujourd'hui.</div>";
           } elseif ($expirationPermis > $currentDate) {
               // Calculer le nombre de jours restants
               $diff = strtotime($expirationPermis) - strtotime($currentDate);
               $daysRemaining = floor($diff / (60 * 60 * 24));
   
               if ($daysRemaining <= 30) {
                $alertes[] = "<div class='alert alert-success'>Votre permis de conduire est valide jusqu'au $expirationPermis. Il vous reste encore $daysRemaining jours.</div>";               }
           }
       }
   
       $conn->close();
   
       if (!empty($alertes)) {
           foreach ($alertes as $alerte) {
               echo "<div class='mb-4 mt-4 '> $alerte </div> ";
           }
       } else {
           echo "<div class='alert alert-info'>Il n'existe aucune alerte pour le moment .</div>";
       }
       
       ?>
<?php include('footer.php') ; ?>
