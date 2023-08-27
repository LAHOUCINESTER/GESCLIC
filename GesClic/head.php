<?php  include("server.php") ;
$role = $_SESSION['user_type'] ;
 ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-jBq2Ei5fYBCX+W8Ol0vlpSG6IkC8eAty6Kw0Kc/IfvrpBnfnwHNK1Bm/GeTuAxvfVXqQ1zC8JGu+QGnTtXryXQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="css/landingS.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.0/css/dataTables.bootstrap4.min.css"> 
    <title>HR_GesClic</title>
   
</head>
<body style="background-color : #F7FDFE">

    <!-- Barre de navigation -->
    <nav class="navbar navbar2 navbar-expand-lg navbar-dark fixed-top " style="background: #1D3461; ">
        <a class="navbar-logo" href="landing.php"><img src="images/logo2.png" alt="GesClic"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav nav-pills ml-auto mr-auto">
            <?php if($role=="employee" ){?>
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'pointer.php') ? 'active-link active-link-text' : ''; ?>" href="pointer.php">
                        <i class="bi bi-clock-history"></i> Pointer
                    </a>
                </li> 
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'home_emp.php') ? 'active-link active-link-text' : ''; ?>" href="home_emp.php">
                        <i class="bi bi-house-door-fill"></i> Accueil
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'do_dmnd.php') ? 'active-link active-link-text' : ''; ?>" href="do_dmnd.php">
                        <i class="bi bi-file-earmark-text"></i> Effectuer une demande
                    </a>
                </li>
                <?php } ?>
                <?php if($role=="employeur" ){?>
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'employeur.php') ? 'active-link active-link-text' : ''; ?>" href="employeur.php">
                        <i class="bi bi-house-door-fill"></i> Accueil
                    </a>
                </li> 
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'stats.php') ? 'active-link active-link-text' : ''; ?>" href="stats.php">
                        <i class="bi bi-file-earmark-text"></i> Statistiques
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'do_dmnd.php') ? 'active-link active-link-text' : ''; ?>" href="do_dmnd.php">
                        <i class="bi bi-file-earmark-text"></i> Envoyer une demande
                    </a>
                </li>
                <?php } ?>
                <?php if( $role=="chef" ){?>
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'chef.php') ? 'active-link active-link-text' : ''; ?>" href="chef.php">
                        <i class="bi bi-file-earmark-text"></i> Valider pointage  
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'pointer_perso.php') ? 'active-link active-link-text' : ''; ?>" href="pointer_perso.php">
                        <i class="bi bi-file-earmark-text"></i> Pointer au personnel   
                    </a>
                </li>
                <?php } ?>
                <?php if($role=="employeur" || $role=="chef" ){?>
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'list_emp.php') ? 'active-link active-link-text' : ''; ?>" href="list_emp.php">
                        <i class="bi bi-file-earmark-text"></i> Liste des emloyés 
                    </a>
                </li>
                <?php } ?>
                
                <!-- Ajoutez la classe active-link pour les autres liens de la barre de navigation de la même manière que ci-dessus pour les autres pages correspondantes -->
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'pointage.php') ? 'active-link active-link-text' : ''; ?>" href="pointage.php">
                         <i class="bi bi-calendar-check"></i> Pointage
                    </a>
                </li>
               
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'demandes_effectues.php') ? 'active-link active-link-text' : ''; ?>" href="demandes_effectues.php">
                        <i class="bi bi-card-checklist"></i> Mes demandes
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'demandes_recues.php') ? 'active-link' : ''; ?>" href="demandes_recues.php">
                         <i class="bi bi-inbox"></i> Demandes reçues
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'alertes.php') ? 'active-link active-link-text' : ''; ?>" href="alertes.php">
                        <i class="bi bi-bell-fill"></i> Alertes
                    </a>
                </li>
                <?php if ($role=="employee" ) {?>
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'employe.php') ? 'active-link active-link-text' : ''; ?>" href="employe.php">
                        <i class="bi bi-person-circle"></i> Mon profil
                    </a>
                </li> 
                <?php } ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'logout.php') ? 'active-link active-link-text' : ''; ?>" href="logout.php" onclick="return confirm('Êtes-vous sûr de vouloir vous déconnecter ?')">
                        <i class="bi bi-box-arrow-right"></i> Déconnexion
                    </a>
                </li>
                <!-- Ajoutez d'autres liens avec la classe active-link pour les autres pages correspondantes -->
            </ul>
        </div>
    </nav>
  
