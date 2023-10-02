<?php
session_start();
include('server.php');
// Check if the user is logged in as an employee
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'chef'){
  // If not logged in as an employee, redirect to the login page
  header("Location: login.php");
  exit();
}
// Initialisation de la variable $filtrage pour éviter une erreur si le formulaire n'est pas soumis
$filtrage = '';
$chef_id = $_SESSION['user_id'];
// Vérifier si le formulaire de filtrage a été soumis
if (isset($_POST['filtrer'])) {
    // Récupérer la valeur sélectionnée dans la liste déroulante
    $filtrage = $_POST['filtrage'];

    // Déterminer la colonne de l'heure en fonction du type de pointage sélectionné
    $heureColonne = ($filtrage === 'arrivee') ? 'h_entree_chef' : 'h_sortie_chef';
    $heureSys = ($filtrage === 'arrivee') ? 'h_entree_sys' : 'h_sortie_sys';

    // Requête pour récupérer les données filtrées selon le type de pointage sélectionné
    $valider = mysqli_query($conn, "SELECT p.*, e.*
        FROM pointer p
        JOIN employe e ON p.employe_id = e.employe_id
        WHERE e.chef_id = '$chef_id'
        AND DATE(p.date) = CURDATE()
        AND (p.$heureColonne IS NULL OR p.$heureColonne = '00:00:00')
        AND (p.$heureSys != '00:00:00' )");
        $dateExp = isset($_POST['dateExp']) ? $_POST['dateExp'] : null;

        if (!is_null($dateExp)) {
            // Modifier la requête pour utiliser la date sélectionnée
            $valider = mysqli_query($conn, "SELECT p.*, e.*
            FROM pointer p
            JOIN employe e ON p.employe_id = e.employe_id
            WHERE e.chef_id = '$chef_id'
            AND DATE(p.date) = '$dateExp'
            AND (p.$heureColonne IS NULL OR p.$heureColonne = '00:00:00')
            AND (p.$heureSys != '00:00:00' )");
        } else {
            // Requête pour récupérer les données filtrées selon le type de pointage sélectionné
            $valider = mysqli_query($conn, "SELECT p.*, e.*
                FROM pointer p
                JOIN employe e ON p.employe_id = e.employe_id
                WHERE e.chef_id = '$chef_id'
                AND DATE(p.date) = CURDATE()
                AND (p.$heureColonne IS NULL OR p.$heureColonne = '00:00:00')
                AND (p.$heureSys != '00:00:00' )");
        }
} else {
    // Si le formulaire n'a pas été soumis, afficher tous les pointages par défaut
    $valider = mysqli_query($conn, "SELECT p.*, e.*
        FROM pointer p
        JOIN employe e ON p.employe_id = e.employe_id
        WHERE e.chef_id = '$chef_id'
        AND DATE(p.date) = CURDATE()
        AND ((p.h_entree_chef IS NULL OR p.h_entree_chef = '00:00:00') OR (p.h_sortie_chef IS NULL OR p.h_sortie_chef = '00:00:00'))");
}


include('head.php');
?>

<header class="text-center py-5 mt-5">
    <div class="container">
        <br><h1>Valider pointage des employés !</h1><br>
    </div>
</header>

<div class="container">
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="mb-4">
        <div class="form-row ">
            <div class="col-md-4 mb-4">
                <select class="form-control" id="filtrage" name="filtrage">
                    <option selected>-- Veuillez choisir le type de pointage souhaité --</option>
                    <option value="arrivee" <?php if ($filtrage === 'arrivee') echo 'selected'; ?>>Pointages d'arrivée</option>
                    <option value="sortie" <?php if ($filtrage === 'sortie') echo 'selected'; ?>>Pointages de sortie</option>
                </select>
            </div>
            <div class="col-md-4 mb-4">
                <input type="date" id="dateExp" name="dateExp" class="form-control" value="<?php echo isset($_POST['dateExp']) ? $_POST['dateExp'] : date('Y-m-d'); ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" name="filtrer" class="btn btn-primary">Filtrer</button>
            </div>
        </div>
    </form>


    <div class="row">
        <?php while ($row = mysqli_fetch_assoc($valider)) { ?>
            <div class="col-sm-3 mb-4">
                <div class="card" >
                    <div class="card-body">
                        <div class="col">
                            <input type="date" name="date" class="form-control mb-2" value="<?php echo $row['date']; ?>" readonly>
                        </div>
                        <div class="text-center">
                        <?php if ($row['profil_img']) { ?>
                            <img src="images/profile/<?php echo $row['profil_img']; ?>" alt="Photo de profil" class="card-img-top rounded card-image">
                        <?php } else { ?>
                            <img src="images/profil.png" alt="Photo de profil par défaut" class="card-img-top rounded card-image">
                        <?php } ?>     
                        </div>
                        <div class="mt-4">
                                <h5 class="card-"><?php echo $row['Nom']; ?> <?php echo $row['prenom']; ?></h5>
                        </div>
                    </div>
                    <div class="card-text ml-2 mt-2 ">
                        <?php if ($filtrage === 'arrivee') { ?>
                            <div class="mb-2">Heure d'entrée : <?php echo $row['h_entree']; ?> </div> 
                            <div class="mb-2">Heure définie par le système : <?php echo $row['h_entree_sys']; ?> </div> 
                        <?php } else if ($filtrage === 'sortie' ){ ?>
                            <div class="mb-2">Heure de sortie : <?php echo $row['h_sortie']; ?></div> 
                            <div class="mb-2">Heure définie par le système : <?php echo $row['h_sortie_sys']; ?></div> 
                        <?php }  ?>
                    </div>                        
                    <div class="card-footer d-flex justify-content-center">
                        <!-- Bouton Modifier pointage -->
                        <?php if ($filtrage === 'arrivee') { ?> 
                            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modifierModal<?php echo $row['point_id']; ?>"> 
                        Modifier pointage d'entrée 
                        <?php } else if ($filtrage === 'sortie' ){ ?>
                            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modifierModal<?php echo $row['point_id']; ?>"> 
                            Modifier pointage de sortie 
                        <?php } ?> 
                        </a>
                        <?php if (isset($_POST['filtrer'])) { ?>
                            <!-- Bouton Valider pointage -->
                            <form method="POST" action="valider_pointage.php">
                                <input type="hidden" name="employe_id" value="<?php echo $row['employe_id']; ?>">
                                <input type="hidden" name="point_id" value="<?php echo $row['point_id']; ?>">
                                <input type="hidden" name="h_entree" value="<?php echo $row['h_entree']; ?>">
                                <input type="hidden" name="h_sortie" value="<?php echo $row['h_sortie']; ?>">
                                <?php if ($filtrage === 'arrivee') { ?> 
                                    <button type="submit" name="valider_pe" class="btn btn-success ml-4">Valider pointage d'entrée</button>
                                <?php } else if ($filtrage === 'sortie' ){ ?>
                                    <button type="submit" name="valider_ps" class="btn btn-success ml-4">Valider pointage de sortie</button>
                                <?php } ?> 
                            </form>
                        <?php } ?> 
                    </div>
                </div>
            </div>
            <!-- Modal de modification -->
        <div class="modal fade" id="modifierModal<?php echo $row['point_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modifierModalLabel" aria-hidden="true">
            <!-- ... Contenu du modal de modification ... -->
            <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="clockInModalLabel">Pointer votre entrée</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Formulaire pour enregistrer l'heure d'entrée -->
        <form action="modifier_pointage.php" method="post">
        <input type="hidden" name="point_id" value="<?php echo $row['point_id']; ?>">
            <div class='row'>
              <div class="col">
                  <div class="form-group">
                    <label>Date : </label>
                    <input type="date" name="date" class="form-control" value="<?php echo isset($_POST['dateExp']) ? $_POST['dateExp'] : date('Y-m-d'); ?>" readonly>
                  </div>
              </div>
            </div>
            <?php if ($filtrage === 'arrivee') { ?> 
                <div class='row'>
                    <div class="col">
                        <div class="form-group">
                            <label>Heure définit par système </label>
                            <input type="time" name="h_entree_sys" class="form-control" value="<?php echo $row['h_entree_sys']; ?>" readonly>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Heure pointé par l'employé </label>
                            <input type="time" name="heure_actuelle" class="form-control " value="<?php echo $row['h_entree']; ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                        <label>Modifier l'Heure d'entrée : </label>
                        <input type="time" name="h_entree_chef" class="form-control current-time " >
                </div>
            <!-- Ajouter l'input caché pour l'action -->
                 <div class="text-center">
                 <button type="submit"  name="modifier_p" class="btn btn-primary">Enregistrer l'entrée</button>
                </div>
            <?php } else if ($filtrage === 'sortie' ){ ?>
                <div class='row'>
                    <div class="col">
                        <div class="form-group">
                            <label>Heure définit par système </label>
                            <input type="time" name="h_sortie_sys" class="form-control" value="<?php echo $row['h_sortie_sys']; ?>" readonly>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Heure pointé par l'employé </label>
                            <input type="time" name="heure_sortie" class="form-control " value="<?php echo $row['h_sortie']; ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                        <label>Modifier l'Heure de la sortie : </label>
                        <input type="time" name="h_sortie_chef" class="form-control " value="<?php echo $row['h_sortie']; ?>" >
                </div>
                 <div class="text-center">
                 <button type="submit"  name="modifier_ps" class="btn btn-primary">Enregistrer la sortie</button>
                </div>
            <?php } ?>
        </form>
      </div>
    </div>
  </div>
        </div>
        <?php } ?>
    </div>
</div>
<script>
  // Fonction pour obtenir l'heure locale du navigateur et la mettre à jour dans les champs de l'heure actuelle
  function updateCurrentTimeFields() {
    const currentTime = new Date().toLocaleTimeString('fr-FR', { hour12: false, timeZone: 'Africa/Casablanca', hour: '2-digit', minute: '2-digit' });
    const currentInputFields = document.querySelectorAll('.current-time');
    currentInputFields.forEach(input => (input.value = currentTime));
  }

  // Mettre à jour l'heure actuelle au chargement de la page
  updateCurrentTimeFields();

  // Mettre à jour l'heure actuelle toutes les 60 secondes pour qu'elle reste à jour
  setInterval(updateCurrentTimeFields, 60000);
</script>
<?php include('footer.php') ?>