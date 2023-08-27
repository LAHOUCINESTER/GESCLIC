<?php

include('server.php');

// Check if the user is logged in as an employee
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'employee'){
    // If not logged in as an employee, redirect to the login page
    header("Location: commun/login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
// Construire la requête SQL pour récupérer les informations de l'employé, de la CIN, du permis et de la banque
$query = "SELECT e.*, c.*, p.*, b.*
            FROM employe AS e
            LEFT JOIN (
                SELECT *
                FROM cin
                WHERE employe_id = ?
                ORDER BY date_update_cin DESC
                LIMIT 1
            ) AS c ON e.employe_id = c.employe_id
            LEFT JOIN (
                SELECT *
                FROM permis
                WHERE employe_id = ?
                ORDER BY date_update_permis DESC
                LIMIT 1
            ) AS p ON e.employe_id = p.employe_id
            LEFT JOIN (
                SELECT *
                FROM banque
                WHERE employe_id = ?
                ORDER BY date DESC LIMIT 1
            ) AS b ON e.employe_id = b.employe_id
            WHERE e.employe_id = ?";

// Exécuter la requête SQL
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "iiii", $user_id, $user_id, $user_id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user_profile = mysqli_fetch_assoc($result);
?>

<?php include('head.php') ?>
    <div class="profile-container py-2 mt-5">
        <div class="sidebar ">
            <div class="user-profile">
                <?php if ($user_profile['profil_img']) { ?>
                    <img src="images/profile/<?php echo $user_profile['profil_img']; ?>" alt="Photo de profil" class="profile-image">
                <?php } else { ?>
                    <img src="images/profil.png" alt="Photo de profil par défaut" class="profile-image">
                <?php } ?>           
            </div>
            <button class="nav-button " onclick="showSection('personal')">Informations Personnelles</button>
            <button class="nav-button" onclick="showSection('bank')">Informations Bancaires</button>
            <button class="nav-button" onclick="showSection('password')">Changer le mot de passe </button>
            <!-- Ajoutez d'autres boutons de navigation au besoin -->
        </div>
        <div class="main-content">
            <!-- Contenu de la section affichée ici -->
            <div id="personal" class="section mt-4">
                <h4 class="text-center mt-4 mb-4">Mes informations personnelles :</h4>
                    <?php if (!$user_profile || empty($user_profile['Nom']) || empty($user_profile['adresse']) || empty($user_profile['cin']) || empty($user_profile['tele']) || isset($_POST['update'])) { ?>
                        <form method="post" action="add_profil.php" class="mt-4 form1" enctype="multipart/form-data" onsubmit="return validateForm()">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="profil_img">Photo personnelle :</label>
                                        <input type="file" id="profil_img" name="profil_img" value=" <?php $user_profile['profil_img'] ?>" >
                                        <?php if (!empty($user_profile['profil_img'])): ?>
                                                <img src="images/profile/<?php echo $user_profile['profil_img']; ?>" alt="CIN Image" class="cin-image">
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label  >Nom  :</label>
                                        <input type="text" id="Nom" name="Nom" class="form-control" value="<?php echo $user_profile['Nom']; ?>" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="address">Prénom :</label>
                                        <input type="text" id="prenom" name="prenom" class="form-control" value="<?php echo $user_profile['prenom']; ?>"required >
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="phone">Sexe:</label>
                                        <select class="form-select"  id="sexe" name="sexe" value="<?php echo $user_profile['sexe']; ?>" required>
                                        <option value="Féminin">Féminin</option>
                                        <option value="Masculin">Masculin</option>
                                        </select>
                                    </div>
                                </div>
                                </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="phone">Civilité :</label>
                                        <select class="form-select"  id="civilite" name="civilite" value="<?php echo $user_profile['civilite']; ?>" required>
                                        <option value="Mlle">Mlle</option>
                                        <option value="Mme">Mme</option>
                                        <option value="Mr">Mr</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="phone">Situation familiale :</label>
                                        <select class="form-select"  id="situation_fam" name="situation_fam" value="<?php echo $user_profile['situation_fam']; ?>" required>
                                        <option value="Célibataire">Célibataire</option>
                                        <option value="Marié">Marié</option>
                                        <option value="Veuf">Veuf </option>
                                        <option value="Divorcé">Divorcé  </option>
                                        <option value="Séparé">Séparé  </option>

                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="phone">Téléphone :</label>
                                        <input type="tel" id="tele" name="tele" class="form-control" value="<?php echo $user_profile['tele']; ?>" pattern="[0-9]{10}" title="Numéro de téléphone doit être composé de 10 chiffres (ex. 0600123456)" required inputmode="numeric">

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label  >Email personnelle :</label>
                                        <input type="email" id="email_perso" name="email_perso" class="form-control" value="<?php echo $user_profile['email_perso']; ?>" >
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label  >Email professionnelle :</label>
                                        <input type="email" id="email" class="form-control" value="<?php echo $user_profile['email']; ?>" readonly>
                                    </div>
                                </div>
                            </div>  
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label  >Ville  de résidence :</label>
                                        <input type="text" id="ville" name="ville" class="form-control" value="<?php echo $user_profile['ville']; ?>" required >
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label  >Code postale :</label>
                                        <input type="text" id="code_postal" name="code_postal" class="form-control" value="<?php echo $user_profile['code_postal']; ?>" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label  >Adresse :</label>
                                        <input type="text" id="adresse" name="adresse"  class="form-control" value="<?php echo $user_profile['adresse']; ?>" required>
                                    </div>
                                </div>
                                </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label  >Lieu de naissance :</label>
                                        <input type="text" id="lieu_naissance" name="lieu_naissance" class="form-control" value="<?php echo $user_profile['lieu_naissance']; ?>" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label Date de naissance :</label>
                                        <input type="date" id="date_naissance" name="date_naissance" class="form-control" value="<?php echo $user_profile['date_naissance']; ?>" required>
                                    </div>
                                </div>
                                    <div class="col">
                                <div class="form-group">
                                        <label Nationalité :</label>
                                        <input type="text" id="nationalite" name="nationalite" class="form-control" value="<?php echo $user_profile['nationalite']; ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label  >CIN  :</label>
                                        <input type="text" id="cin" name="cin" class="form-control" value="<?php echo $user_profile['cin']; ?>"pattern=".{8}" title="CIN doit contenir 8 caractères" required maxlength="8">
                                    </div>
                                </div>
                                
                                <div class="col">
                                    <div class="form-group">
                                        <label for="address">Date d'expération cin  :</label>
                                        <input type="date" id="date_exp_cin" name="date_exp_cin" class="form-control" value="<?php echo $user_profile['date_exp_cin']; ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label>Image CIN recto :</label>
                                        <input type="file" id="cin_img" name="cin_img" value="<?php $user_profile['cin_img'] ?>" >
                                            <?php if (!empty($user_profile['cin_img'])): ?>
                                                <img src="images/profile/<?php echo $user_profile['cin_img']; ?>" alt="CIN Image" class="cin-image">
                                            <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label>Image CIN verso :</label>
                                        <input type="file" id="cin_img2" name="cin_img2">
                                            <?php if (!empty($user_profile['cin_img2'])): ?>
                                                <img src="images/profile/<?php echo $user_profile['cin_img2']; ?>" alt="CIN Image" class="cin-image">
                                            <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="phone">Type permis si existe :</label>
                                        <select class="form-select"  id="type_permis" name="type_permis" value="<?php echo $user_profile['type_permis']; ?>" >
                                            <option value="A">A</option>
                                            <option value="AM">AM</option>
                                            <option value="A1">A1</option>
                                            <option value="B">B</option>
                                            <option value="EB">EB</option>
                                            <option value="C">C</option>
                                            <option value="EC">EC</option>
                                            <option value="D">D</option>
                                            <option value="ED">ED</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="phone">Permis :</label>
                                        <input type="text" id="permis" name="permis" class="form-control" value="<?php echo $user_profile['permis']; ?>" >
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="phone">Date fin permis :</label>
                                        <input type="date" id="date_exp_permis" name="date_exp_permis" class="form-control" value="<?php echo $user_profile['date_exp_permis']; ?>" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label >Image permis recto :</label>
                                            <input type="file" id="permis_img" name="permis_img" value=" <?php $user_profile['permis_img'] ?>">
                                            <?php if (!empty($user_profile['permis_img'])): ?>
                                                <img src="images/profile/<?php echo $user_profile['permis_img']; ?>" alt="permis Image" class="cin-image">
                                            <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label>Image Permis verso :</label>
                                        <input type="file" id="permis_img2" name="permis_img2">
                                        <?php if (!empty($user_profile['permis_img2'])): ?>
                                            <img src="images/profile/<?php echo $user_profile['permis_img2']; ?>" alt="Permis Image" class="cin-image">
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>    
                            <div class="text-center">
                                        <button type="submit" name="addInfo_perso"  class="btn btn-primary" >Enregistrer les informations </button>
                            </div>
                        </form>
                        <div class="text-center">
                            <a href="employe.php"><button type="reset" class="btn btn-success" onclick="return confirm('Vos données ne seront pas enregistrées !')">Annuler </button></a>
                        </div>
                    <?php } else { ?>
                        <div class="items  mx-auto "style="background-color:#ffffff; width: 80%; ">
                        <div class="container ">         
                        <form method="post"  class="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label  >Nom  :</label>
                                        <input type="text" id="Nom" class="form-control" value="<?php echo $user_profile['Nom']; ?>" readonly>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="address">Prénom :</label>
                                        <input type="text" id="prenom" class="form-control" value="<?php echo $user_profile['prenom']; ?>" readonly>
                                    </div>
                                </div> 
                                </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label >Civilité :</label>
                                        <input type="text" id="civilite" class="form-control" value="<?php echo $user_profile['civilite']; ?>" readonly>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label >Situation familiale :</label>
                                        <input type="text" id="situation_fam" class="form-control" value="<?php echo $user_profile['situation_fam']; ?>" readonly>
                                    </div>
                                </div>  
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label>Téléphone :</label>
                                        <input type="text" id="tele" class="form-control" value="<?php echo $user_profile['tele']; ?>" readonly>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label >Email personnelle :</label>
                                        <input type="email" id="email_perso" class="form-control" value="<?php echo $user_profile['email_perso']; ?>" readonly>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label >Email professionnelle :</label>
                                        <input type="email" id="email" class="form-control" value="<?php echo $user_profile['email']; ?>" readonly>
                                    </div>
                                </div>
                            </div>  
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label  >Ville  de résidence :</label>
                                        <input type="text" id="ville" class="form-control" value="<?php echo $user_profile['ville']; ?>" readonly>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label  >Code postale :</label>
                                        <input type="text" id="code_postal" class="form-control" value="<?php echo $user_profile['code_postal']; ?>" readonly>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label  >Adresse :</label>
                                        <input type="text" id="adresse" class="form-control" value="<?php echo $user_profile['adresse']; ?>" readonly>
                                    </div>
                                </div>
                                </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label  >Lieu de naissance :</label>
                                        <input type="text" id="lieu_naissance" class="form-control" value="<?php echo $user_profile['lieu_naissance']; ?>" readonly>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="address">Date de naissance :</label>
                                        <input type="text" id="date_naissance" class="form-control" value="<?php echo $user_profile['date_naissance']; ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label  >CIN  :</label>
                                        <input type="text" id="cin" class="form-control" value="<?php echo $user_profile['cin']; ?>" readonly>
                                    </div>
                                </div>
                                
                                <div class="col">
                                    <div class="form-group">
                                        <label for="address">Date d'expération cin  :</label>
                                        <input type="text" id="	date_exp_cin" class="form-control" value="<?php echo $user_profile['date_exp_cin']; ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="phone">Permis :</label>
                                        <input type="text" id="permis" class="form-control" value="<?php echo $user_profile['permis']; ?>" readonly>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="phone">Date fin permis :</label>
                                        <input type="text" id="date_exp_permis" class="form-control" value="<?php echo $user_profile['date_exp_permis']; ?>" readonly>
                                    </div>
                                </div>        
                            </div>
                            <div class="text-center mt-4">
                                <button type="submit" name="update"  class="btn btn-primary" > Modifier mes informations </button>
                            </div>
                        </form> 
                        </div>
                        </div>
                    <?php } ?>
            </div>
            <div id="bank" class="section">
                <!-- Afficher les informations bancaires ici -->
                <h4 class="text-center mt-4 mb-2" >Mes informations bancaires : </h4>
                <div class="items  mx-auto "style="background-color:#ffffff; width: 80%; ">
                    <div class="container ">         
                    <form method="post" class="form1" action="add_profil.php">
                        <div class="form-group">
                            <label>Nom de la banque :</label>
                            <input type="text" name="nom_banque" class="form-control" value="<?php echo $user_profile['nom_banque']; ?>" <?php echo isset($_POST['update_banque']) ? '' : 'readonly'; ?>>
                        </div>
                        <div class="form-group">
                            <label for="address">RIB :</label>
                            <input type="text" name="rib" class="form-control" value="<?php echo $user_profile['rib']; ?>" <?php echo isset($_POST['update_banque']) ? '' : 'readonly'; ?>>
                        </div>
                        <div class="form-group">
                            <label>Code IBAN :</label>
                            <input type="text" name="iban" class="form-control" value="<?php echo $user_profile['iban']; ?>" <?php echo isset($_POST['update_banque']) ? '' : 'readonly'; ?>>
                        </div>
                        <div class="text-center mt-4">
                            <button type="button" id="editButton" class="btn btn-primary">Modifier mes informations</button>
                            <button type="submit" name="update_banque" id="submitButton" class="btn btn-success" style="display: none;">Enregistrer</button>
                        </div>    
                    </form>
                    </div>
                </div>
            </div>
            <div id="password" class="section">
                <h4 class="text-center mt-4 mb-4">Changer le mot de passe :</h4>
                <div class="items mx-auto" style="background-color:#ffffff; width: 80%;">
                    <div class="container">
                        <!-- Afficher les messages d'erreur uniquement si la section password est active -->
                        <?php if (isset($_GET['error']) && $_GET['section'] === 'password') { echo $_GET['error']; } ?>
                        <form action="change-p.php" method="post" id="password-form">
                            <?php if (isset($_GET['error']) || isset($_GET['success'])) { ?>
                                <div class="alert <?php echo isset($_GET['error']) ? 'alert-danger' : 'alert-success'; ?>" role="alert">
                                    <?php echo isset($_GET['error']) ? $_GET['error'] : $_GET['success']; ?>
                                </div>
                            <?php } ?>
                            <div class="row form-group">
                                <div class="col-sm-6">
                                    <label>Ancien mot de passe :</label>
                                </div>
                                <div class="col-sm-6">
                                    <input class="form-control" type="password" name="op" placeholder="Ancien mot de passe" required>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-6">
                                    <label>Nouveau mot de passe :</label>
                                </div>
                                <div class="col-sm-6">
                                    <input class="form-control" type="password" name="np" placeholder="Nouveau mot de passe" >
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-6">
                                    <label>Confirmer le mot de passe :</label>
                                </div>
                                <div class="col-sm-6">
                                    <input class="form-control" type="password" name="c_np" placeholder="Confirmer le nouveau mot de passe:" >
                                </div>
                            </div>
                            <div class="modal-footer justify-content-center mt-2">
                                <button type="submit" name="change_p" class="btn btn-primary">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
     <?php include('footer.php') ; ?>
     <script>
    const editButton = document.getElementById("editButton");
    const submitButton = document.getElementById("submitButton");
    const inputFields = document.querySelectorAll(".form-control");

    editButton.addEventListener("click", () => {
        inputFields.forEach(field => {
            field.removeAttribute("readonly");
        });
        editButton.style.display = "none";
        submitButton.style.display = "block";
    });
</script>