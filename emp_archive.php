<?php
session_start();
include('server.php');

// Vérifier si l'utilisateur est connecté en tant qu'employé ou employeur
if (!isset($_SESSION['user_type']) || ( $_SESSION['user_type'] !== 'employeur')) {
    // Si l'utilisateur n'est pas connecté en tant qu'employé, rediriger vers la page de connexion
    header("Location: login.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id']) && isset($_POST['new_date_sortie'])) {
        $id = $_POST['id'];
        $new_date_sortie = $_POST['new_date_sortie'];

        $updateQuery = "UPDATE employe_entreprise SET DateFin = '$new_date_sortie' WHERE id = '$id'";
        if (mysqli_query($conn, $updateQuery)) {
            // Update successful
            $_SESSION['success_'] = "La date de sortie de l'employé a été mise à jour avec succès.";
        } else {
            // Update failed
            $_SESSION['error_'] = "Une erreur s'est produite lors de la mise à jour de la date de sortie de l'employé: " . mysqli_error($conn);
        }

        header("Location: emp_archive.php");
        exit();
    }
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
    $query = "SELECT e.*, ea.DateEmbauche AS DateEmbauche, ea.DateFin AS date_fin, ea.fonction AS fonction , ea.id as id
    FROM employe_entreprise ea  LEFT JOIN employe e ON ea.employe_id = e.employe_id
    WHERE ea.id_entreprise = '$entreprise_id' AND ea.archive = '1'
    ORDER BY e.cin
    LIMIT " . (($page - 1) * $resultsPerPage) . ", $resultsPerPage";
$countQuery = "SELECT COUNT(*) AS total
    FROM employe_entreprise ea";
}

$result = mysqli_query($conn, $query);
$countResult = mysqli_query($conn, $countQuery);
$rowCount = mysqli_fetch_assoc($countResult)['total'];
$totalPages = ceil($rowCount / $resultsPerPage);

?>

<header class="text-center py-5 mt-4">
    <div class="container">
        <br>
        <h3>Employés archivés :</h3>
    </div>
</header>


<div class="container-fluid">
    <div class="row ">
        <div class="col-md-6">
            <input type="text" id="myInput" onkeyup="myFunction()" placeholder=" Chercher Employé par CIN  ou Nom" title="Type in a name" style="width:50% ; margin-left: 40px">
            <i class="bi bi-search"></i>
        </div>
    </div> 
    <?php 
 if (isset($_SESSION['success_'])) : ?>
     <div class="alert alert-success">
         <?php echo $_SESSION['success_']; ?>
     </div>
     <?php unset($_SESSION['success_']); // Clear the message after displaying it ?>
 <?php endif; ?>
 
 <!-- Check for and display error message -->
 <?php if (isset($_SESSION['error_'])) : ?>
     <div class="error-message alert alert-danger">
         <?php echo $_SESSION['error_']; ?>
     </div>
     <?php unset($_SESSION['error_']); // Clear the message after displaying it ?>
 <?php endif; ?>
    <table id="myTable" class="table  mt-4" style="width:100%">
        <thead class = "table-primary">
            <tr>
                <th > Image </th>
                <th data-search="true">CIN</th>
                <th data-search="true">Nom </th>
                <th data-orderable="false">Email</th>   
                <th data-orderable="false">Téléphone</th> 
                <th data-orderable="false">Fonction </th>               
                <th data-orderable="false">Date embauche </th>               
                <th data-orderable="false">Date sortie </th>               
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
                        <td><?php echo $row['Nom']. ' ' .$row['prenom'] ; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['tele']; ?></td>
                        <td><?php echo $row['fonction']; ?></td>
                        <td><?php echo $row['DateEmbauche']; ?></td>
                        <td>
                            <?php echo $row['date_fin']; ?>
                            <input type="date" name="date_sortie" value="<?php echo $row['date_fin']; ?>" style="display:none;">
                            <button type="button" class="btn btn-sm btn-primary" onclick="toggleInputField(this)"> Modifier</button>
                            <form action="emp_archive.php" method="POST" style="display:none;">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <input type="date" name="new_date_sortie" value="<?php echo $row['date_fin']; ?>">
                                <button type="submit" class="btn btn-sm btn-success">Enregistrer</button>
                                <button type="button" class="btn btn-sm btn-secondary" onclick="cancelAction()">Annuler</button>
                            </form>
                        </td>
                      
                        
                        
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
function toggleInputField(button) {
    var form = button.nextElementSibling;
    var input = form.querySelector('input[name="new_date_sortie"]');

    button.style.display = 'none';
    form.style.display = 'block';
    input.focus(); // Optionnel : mettre le focus sur le champ de saisie de date
}
function cancelAction() {
    window.location.href = 'emp_archive.php';
}
    </script>