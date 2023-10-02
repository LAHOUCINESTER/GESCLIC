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
    INNER JOIN login l ON c.email = l.email
    WHERE e.id_entreprise = '$entreprise_id'
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

?>

<header class="text-center py-5 mt-4">
    <div class="container">
        <br>
        <h3>La liste des chefs</h3>
    </div>
</header>
<div class="container">
    <div class="row ">
        <div class="col-md-6">
            <input type="text" id="myInput" onkeyup="myFunction()" placeholder=" Chercher Employé par CIN  ou Nom" title="Type in a name" style="width:50% ; margin-left: 40px">
            <i class="bi bi-search"></i>
        </div>
        <?php if ($_SESSION['user_type'] == 'employeur') { ?>
            <div class="col-md-6 text-right">
              <a href="add_chef.php" class="btn btn-success mb-4"><i class="bi bi-person-plus-fill"></i> Ajouter chef</a>
            </div>
            
        <?php } ?>
    </div> 
    <?php 
 if (isset($_SESSION['success_chef'])) : ?>
     <div class="alert alert-success">
         <?php echo $_SESSION['success_chef']; ?>
     </div>
     <?php unset($_SESSION['success_chef']); // Clear the message after displaying it ?>
 <?php endif; ?>
 
 <!-- Check for and display error message -->
 <?php if (isset($_SESSION['error_chef'])) : ?>
     <div class="error-message alert alert-danger">
         <?php echo $_SESSION['error_chef']; ?>
     </div>
     <?php unset($_SESSION['error_chef']); // Clear the message after displaying it ?>
 <?php endif; ?>
    <table id="myTable" class="table mt-4" style="width:100%">
        <thead class = "table-primary">
            <tr>
                <th > Image </th>
                <th data-search="true">CIN</th>
                <th data-search="true">Nom </th>
                <th data-search="true">Prénom </th>
                <th data-orderable="false">Email</th>               
                <?php if ($_SESSION['user_type'] == 'employeur') { ?>
                    <th data-orderable="false">Désactiver compte </th>
                <?php } ?>
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
                                <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal<?php echo $row['chef']; ?>"><i class="bi bi-person-dash"> </i> Désactiver compte </button>
                            </td>
                        </tr>
                         <!-- Modale de suppression pour chaque employé -->
                         <div class="modal fade" id="deleteModal<?php echo $row['chef']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel<?php echo $row['employe_id']; ?>" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel<?php echo $row['chef']; ?>">Désactiver compte de : <?php echo $row['Nom'] . ' ' . $row['prenom']; ?></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Êtes-vous sûr de vouloir désactiver ce compte  ?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <form method="POST" action="delete_chef.php?id=<?php echo $row['chef']; ?>">
                                            <input type="hidden"  id="chef_email" name="chef_email">
                                            <button type="submit" class="btn btn-danger" name="supprimer_chef">Supprimer</button>
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
<script>
    
function myFunction() {
  var input, filter, table, tr, td1, td2, i, txtValue1, txtValue2;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");
  
  for (i = 0; i < tr.length; i++) {
    td1 = tr[i].getElementsByTagName("td")[1]; // First column
    td2 = tr[i].getElementsByTagName("td")[2]; // Second column
    
    if (td1 && td2) {
      txtValue1 = td1.textContent || td1.innerText;
      txtValue2 = td2.textContent || td2.innerText;
      
      // Check if either of the columns contains the search query
      if (txtValue1.toUpperCase().indexOf(filter) > -1 || txtValue2.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}
    </script>