<?php
session_start();
include('server.php');
// Check if the user is logged in as an employee
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'employeur') {
    // If not logged in as an employee, redirect to the login page
    header("Location: commun/login.php");
    exit();
}?>
<?php include('head.php') ?>

  <header class=" text-center py-5 mt-5 " >
   
      <br><h1 >Bienvenue dans l'application GesClic !</h1><br>
      <p class="lead">L'outil parfait pour gérer vos tâches en ligne.</p>
    </div>
  </header>
  <div class="container py-5">
    <h2>Welcome, Admin!</h2>

<?php include('footer.php') ?>

   


