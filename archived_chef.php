<?php
session_start();
include('server.php');

// Vérifier si l'utilisateur est connecté en tant qu'employé ou employeur
if (!isset($_SESSION['user_type']) || ( $_SESSION['user_type'] !== 'employeur')) {
    // Si l'utilisateur n'est pas connecté en tant qu'employé, rediriger vers la page de connexion
    header("Location: login.php");
    exit();
}

include('head.php');
?>

<?php
$chef_id = $_SESSION['user_id'];
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$resultsPerPage = 10;
// Requête pour récupérer tous les employés avec leur chef

// Effectuer la requête de recherche avec pagination
if ($_SESSION['user_type'] == 'employeur') {
    $query = "SELECT e.*, c.chef_id AS chef, c.email AS chef_email
    FROM employe e
    INNER JOIN chef c ON e.cin = c.cin
    WHERE c.id_entreprise = '$entreprise_id' AND archive = '1'
    ORDER BY e.cin
    LIMIT " . (($page - 1) * $resultsPerPage) . ", $resultsPerPage";
$countQuery = "SELECT COUNT(*) AS total
    FROM chef c
    INNER JOIN login l ON c.email = l.email";
}

$result = mysqli_query($conn, $query);
$countResult = mysqli_query($conn, $countQuery);
$rowCount = mysqli_fetch_assoc($countResult)['total'];
$totalPages = ceil($rowCount / $resultsPerPage);

if (isset($_POST['activer'])) {
    // Récupérez l'e-mail du chef sélectionné
    $emailChef = $_POST['chef_email'];
    $usertype = 'chef';

    // Vérifiez si l'e-mail existe déjà
    $emailExistsQuery = "SELECT * FROM login WHERE email = ?";
    $stmt_emailExists = mysqli_prepare($conn, $emailExistsQuery);
    mysqli_stmt_bind_param($stmt_emailExists, "s", $emailChef);
    mysqli_stmt_execute($stmt_emailExists);
    $emailExistsResult = mysqli_stmt_get_result($stmt_emailExists);

    if (mysqli_num_rows($emailExistsResult) > 0) {
        // L'e-mail existe déjà
        $_SESSION['error_archive'] = "L'e-mail existe déjà.";
    } else {
        // Générez un mot de passe par défaut (par exemple, "password123")
        $motDePasseParDefaut = password_hash('Chef@123', PASSWORD_DEFAULT);

        // Insérez l'utilisateur dans la table "login"
        $insertLoginQuery = "INSERT INTO login (email, password, user_type) VALUES (?, ?, ?)";
        $stmt_insertLogin = mysqli_prepare($conn, $insertLoginQuery);
        mysqli_stmt_bind_param($stmt_insertLogin, "sss", $emailChef, $motDePasseParDefaut, $usertype);

        if (mysqli_stmt_execute($stmt_insertLogin)) {
            // Utilisateur ajouté avec succès dans la table "login"

            // Mettez à jour la table "chef" pour activer le compte
            $updateChefQuery = "UPDATE chef SET archive = '0' WHERE email = ?";
            $stmt_updateChef = mysqli_prepare($conn, $updateChefQuery);
            mysqli_stmt_bind_param($stmt_updateChef, "s", $emailChef);

            if (mysqli_stmt_execute($stmt_updateChef)) {
                // Compte chef activé avec succès
                $_SESSION['success_archive'] = "Compte chef activé avec succès.";
            } else {
                // Gestion des erreurs lors de la mise à jour de la table "chef"
                $_SESSION['error_archive'] = "Une erreur s'est produite lors de l'activation du compte .";
            }

            mysqli_stmt_close($stmt_updateChef);
        } else {
            // Gestion des erreurs lors de l'insertion dans la table "login"
            $_SESSION['error_archive'] = "Une erreur s'est produite ! Veuillez réessayer ultérieurement ";

        }

        mysqli_stmt_close($stmt_insertLogin);
    }

    mysqli_stmt_close($stmt_emailExists);
  
}

// ... (votre code existant)

?>

<header class="text-center py-5 mt-4">
    <div class="container">
        <br>
        <h3>Comptes des chefs désactivés  </h3>
    </div>
</header>


<div class="container">
    <div class="row ">
        <div class="col-md-6">
            <input type="text" id="myInput" onkeyup="myFunction()" placeholder=" Chercher Employé par CIN  ou Nom" title="Type in a name" style="width:50% ; margin-left: 40px">
            <i class="bi bi-search"></i>
        </div>
    </div> 
    <?php 
 if (isset($_SESSION['success_archive'])) : ?>
     <div class="alert alert-success">
         <?php echo $_SESSION['success_archive']; ?>
     </div>
     <?php unset($_SESSION['success_archive']); // Clear the message after displaying it ?>
 <?php endif; ?>
 
 <!-- Check for and display error message -->
 <?php if (isset($_SESSION['error_archive'])) : ?>
     <div class="error-message alert alert-danger">
         <?php echo $_SESSION['error_archive']; ?>
     </div>
     <?php unset($_SESSION['error_archive']); // Clear the message after displaying it ?>
 <?php endif; ?>
    <table id="myTable" class="table mt-4" style="width:100%">
        <thead class="table-primary">
            <tr>
                <th > Image </th>
                <th data-search="true">CIN</th>
                <th data-search="true">Nom </th>
                <th data-search="true">Prénom </th>
                <th data-orderable="false">Email</th>  
                <th data-orderable="false"></th>               
             
            </tr>
        </thead>
        <tbody>
            <?php
            // Parcourir les résultats de la requête et afficher chaque enregistrement dans une ligne du tableau
            while ($row = mysqli_fetch_assoc($result)) { ?>
             <tr>
                        <td> <?php if ($row['profil_img']) { ?>
                                <img src="images/profile/<?php echo $row['profil_img']; ?>" alt="Photo de profil" class="emp-image" >
                            <?php } else { ?>
                                <img src="images/profil.png" alt="Photo de profil par défaut" class="emp-image">
                            <?php } ?>   
                        </td>
                        <td><?php echo $row['cin']; ?></td>
                        <td><?php echo $row['Nom']; ?></td>
                        <td><?php echo $row['prenom']; ?></td>
                        <td><?php echo $row['chef_email']; ?></td>
                        <td>
                        <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#deleteModal<?php echo $row['chef']; ?>"><i class="bi bi-person-check"> </i> Activer compte </button>

                            
                        </td>
                        </tr>
                         <!-- Modale de suppression pour chaque employé -->
                         <div class="modal fade" id="deleteModal<?php echo $row['chef']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel<?php echo $row['employe_id']; ?>" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel<?php echo $row['chef']; ?>">Activer compte de : <?php echo $row['Nom'] . ' ' . $row['prenom']; ?></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Êtes-vous sûr de vouloir activer ce compte  ?</p>
                                    </div>
                                    <div class="modal-footer">
                                    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                        <input type="hidden" name="chef_email" id="chef_email" value="<?php echo $row['chef_email']; ?>">
                                        <button type="submit" class="btn btn-sm btn-success" name="activer">Activer compte </button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                    </form>
                                    </div>
                                </div>
                            </div>
                        </div>
          </tr>
            <?php }
            ?>
        </tbody>
    </table>

    <?php if ($totalPages > 1) { ?>
    <nav aria-label="Pagination">
        <ul class="pagination justify-content-center mt-4">
            <?php if ($page > 1) { ?>
                <li class="page-item">
                    <a class="page-link" href="?search=<?php echo $search; ?>&page=<?php echo $page - 1; ?>">Précédent</a>
                </li>
            <?php } ?>

            <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                    <a class="page-link" href="?search=<?php echo $search; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php } ?>

            <?php if ($page < $totalPages) { ?>
                <li class="page-item">
                    <a class="page-link" href="?search=<?php echo $search; ?>&page=<?php echo $page + 1; ?>">Suivant</a>
                </li>
            <?php } ?>
        </ul>
    </nav>
<?php } ?>
</div>






<?php include('footer.php'); ?>
