<?php
session_start();
// Check if the user is logged in as an employee
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'employee') {
    // If not logged in as an employee, redirect to the login page
    header("Location: commun/login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-jBq2Ei5fYBCX+W8Ol0vlpSG6IkC8eAty6Kw0Kc/IfvrpBnfnwHNK1Bm/GeTuAxvfVXqQ1zC8JGu+QGnTtXryXQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="css/landing.css">

    <title>Welcome Employee</title>
    <style>
    /* Add custom styles for a fun and friendly look */
 

    .card-title {
      font-size: 24px;
    }

    .btn-primary {
      background-color: #FF6B6B;
      border-color: #FF6B6B;
    }

    .btn-primary:hover {
      background-color: #FF4040;
      border-color: #FF4040;
    }

    .btn-primary:active {
      background-color: #FF3030;
      border-color: #FF3030;
    }
  
  </style>
</head>
<body>
   <!--   <form action="logout.php" method="post">
      <input type="submit" value="Déconnexion">
    </form>
    Add your employee content here -->

    <!-- Add a logout link or button to log out 
    <a href="logout.php">Logout</a> -->
    <nav class="navbar navbar2 navbar-expand-lg navbar-dark  fixed-top" style=" background: #1D3461;">
    <a class="navbar-logo"  href="landing.php"><img  src="images/logo2.png" alt="GesClic"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav nav-pills ml-auto mr-auto  ">
                <li class="nav-item">
            <a class="nav-link"   href="#"><i class="bi bi-user"></i> Mon profil</a>
          </li><li class="nav-item">
            <a class="nav-link"   href="#"><i class="bi bi-user"></i> Mon profil</a>
          </li><li class="nav-item">
            <a class="nav-link"   href="#"><i class="bi bi-user"></i> Mon profil</a>
          </li><li class="nav-item">
            <a class="nav-link"   href="#"><i class="bi bi-user"></i> Mon profil</a>
          </li><li class="nav-item">
            <a class="nav-link"   href="#"><i class="bi bi-user"></i> Mon profil</a>
          </li>
          <li class="nav-item" >
            <a class="nav-link" href="#"><i class="bi bi-inbox"></i> Demandes reçues</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#"><i class="bi bi-file-alt"></i> Mes demandes</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#"><i class="bi bi-plus"></i> Effectuer une demande</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#"><i class="bi bi-clock"></i> Pointage</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logout.php"><i class="bi bi-sign-out-alt"></i> Déconnexion</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <header class="bg-light text-center py-5">
    <div class="container">
      <h1 class="display-4">Bienvenue dans l'application GesClic !</h1>
      <p class="lead">L'outil parfait pour gérer vos tâches en ligne.</p>
    </div>
  </header>

  <section class="py-5">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <div class="card mb-3">
            <div class="card-body">
              <h5 class="card-title">Pointer votre sortie</h5>
              <p class="card-text">Cliquez sur le bouton ci-dessous pour enregistrer votre heure de sortie.</p>
              <a href="#" class="btn btn-primary">Pointer maintenant !</a>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card mb-3">
            <div class="card-body">
              <h5 class="card-title">Pointer votre entrée</h5>
              <p class="card-text">Cliquez sur le bouton ci-dessous pour enregistrer votre heure d'entrée.</p>
              <a href="#" class="btn btn-primary">Pointer maintenant !</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <script>
  $(document).ready(function() {
    const url = window.location.href;
    $('.navbar-nav .nav-item .nav-link').each(function() {
      if (url.includes($(this).attr('href'))) {
        $(this).addClass('active');
      }
    });
  });
</script>
  <?php include('footer.php') ?>
