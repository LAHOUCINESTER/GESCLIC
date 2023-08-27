<?php
session_start();
include('server.php');
// Check if the user is logged in as an employee
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'employee' && $_SESSION['user_type'] !== 'employeur') {
    header("Location: commun/login.php");
    exit();
}
function getEmployeeCompany($employe_id, $conn) {
  // Effectuer une requête SQL pour récupérer le nom de l'entreprise associée à l'employé
  // Remplacez 'your_query_here' par la requête appropriée pour obtenir le nom de l'entreprise
  $query = "SELECT entreprises.Nom_Entreprise FROM employe INNER JOIN entreprises ON employe.id_entreprise = entreprises.id_entreprise WHERE employe.employe_id = ?";
  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, "i", $employe_id);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $row = mysqli_fetch_assoc($result);
  
  return $row['Nom_Entreprise'];
}

?>
<?php include('head.php') ?>
<div class="container ">
    <header class=" text-center py-5 mt-5 " >     
    </header>
    <div class="items mt-2 mx-auto "style="background-color:#ffffff; width: 80%; ">
    
                <h4 style="text-align : center " >Veuillez choisir la demande à effectuer  !</h4><br>
                <div class="container ">
                <form method="POST" action="Add_dmd.php" class="form1" id="dmd_form">
                    <div class="row ">
                      <div class="col-6 mb-4 ">
                          <div class="form-group ">
                            <label>      Date de la demande        *</label>
                            <input type="date"  value="<?php echo date('Y-m-d');?>" class="form-control text-center" id="DateSoumission" name="DateSoumission" onkeyup='check();' readonly="readonly"  required/>
                          </div>
                      </div>
                      <div class="col-md-6 mb-3">
                            <label for="entreprise" class="form-roup ">Entreprise :</label>
                            <input type="text" class="form-control" id="entreprise" name="entreprise" value="<?php echo getEmployeeCompany($_SESSION['user_id'], $conn); ?>" readonly>
                      </div>
                    </div>
                    <div class="input-container">
                        <select class="form-select custom-select py-0" style="background-color: #edf3ff;" id="type_dmd" name="type_dmd"  required>
                          <option value="Autres demandes" selected>Autres demandes </option>
                          <option value="Attestation de travail">Attestation de travail </option>
                          <option value="Attestation de domicialiation de salaire">Attestation de domicialiation de salaire  </option>
                          <option value="Informations">Informations </option>
                          <option value="Récupération">Récupération </option>
                          <option value="Congé">Congé </option>
                          <option value="Bulletin de paie">Bulletin de paie </option>
                        </select>
                    </div>
                        <!-- Additional fields for "Congé" request -->
                        <div id="congeFields" style="display: none;">
                            
                            <div class="row">
                                <div class="col-6 mb-3">
                                  <label for="DateDebut_conge" class="form-label">Date Début :</label>
                                  <input type="date" class="form-control " id="DateDebut_conge" name="DateDebut_conge" onchange="updatePeriod()">
                                </div>                         
                                <div class="col-6 mb-3">
                                  <label for="DateFin_conge" class="form-label">Date Fin :</label>
                                  <input type="date" class="form-control" id="DateFin_conge" name="DateFin_conge" onchange="updatePeriod()">
                                </div> 
                            </div> 
                            <div class="row"> 
                                <div class="col mb-3 mt-2 ">
                                  <label for="start_date" class="form-label">La période souhaitée pour votre congé :</label>
                                </div>
                                <div class="col mb-3">
                                  <input type="number" class="form-control" id="periode" name="periode" onchange="updateDateFields()">
                                </div> 
                            </div> 
                            <div class="row"> 
                                <div class="col-6 mb-3 mt-2 ">
                                  <label class="form-label " >Type de congé : </label>
                                </div>
                                <div class="col mb-3 ">
                                  <select class="form-select custom-select"  id="type_conge" name="type_conge"  required>
                                      <option value="autre" selected>Autres  </option>
                                      <option value=""></option>
                                  </select>
                                </div>
                            </div>
                         </div>
                          <!-- Additional fields for "Bulletin de paie" request (mois) -->
                        <div id="bltnPaieFields" style="display: none;">
                          <div class="row">
                              <div class="col-md-6 mt-2 mb-3">
                                <label for="start_date" class="form-label">Veuillez choisir un mois :</label>
                              </div>
                              <div class="col-md-6  mb-3">
                              <select class="form-select custom-select" id="mois" name="mois">
                                  <option value="" selected>Choisissez un mois</option>
                                    <?php $mois_fr = array(
                                        1 => "Janvier",
                                        2 => "Février",
                                        3 => "Mars",
                                        4 => "Avril",
                                        5 => "Mai",
                                        6 => "Juin",
                                        7 => "Juillet",
                                        8 => "Août",
                                        9 => "Septembre",
                                        10 => "Octobre",
                                        11 => "Novembre",
                                        12 => "Décembre"
                                    );

                                    foreach ($mois_fr as $numero => $nom) {
                                        echo "<option value=\"$nom\">$numero- $nom</option>";
                                    }
                                    ?>
                              </select>
                              </div>
                          </div>
                        </div>
                      <div class=" mt-2 ">
                          <label class="form-label">Veuillez décrire votre demande  :</label>
                          <textarea class="form-control"  id="dscr_dmd" name="dscr_dmd" rows="5"></textarea>
                    </div><br>
                    <div class="text-center">
                          <button type="submit" name="envoi_dmd" id="confirmationButton" class="btn btn-primary">Confirmer ma demande</button>
                    </div>
                </form>
                </div>
    </div>
</div>

<?php include('footer.php') ?>
