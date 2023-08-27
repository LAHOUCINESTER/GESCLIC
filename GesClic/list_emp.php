<?php
session_start();
include('server.php');
// Check if the user is logged in as an employee or employeur 
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'chef' && $_SESSION['user_type'] !== 'employeur') {
    // If not logged in as an employee, redirect to the login page
    header("Location: commun/login.php");
    exit();
}?>
<?php include('head.php') ?>

  <header class=" text-center py-5 mt-5 " >
    <div class="container">
      <br><h4 >La liste des employés </h4> 
    </div>
  </header>
  <div class="container">
    <table id="table_dmd" class="table table-striped table-hover custom-table" style="width:100%">
        <thead>
            <tr>
               <th data-search="true">Matricule</th>
                <th data-search="true">Nom complet </th>
                <th data-order="true">Poste</th>
                <th data-order="true">Date Embauche </th>
                <th data-orderable="false" >Email</th>
                <?php if ($role == 'employeur ') { ?> <th data-orderable="false" >CHef_id</th> <?php } ?>
                <th data-orderable="false" >Détails</th>

            </tr>
        </thead>
        <tbody>
        <?php
          if ($role == 'employeur') {
            // Parcourir les résultats de la requête et afficher chaque enregistrement dans une ligne du tableau
              while ($row = mysqli_fetch_assoc($rsult)) {
                echo '<tr>';

                echo '<td>' . $row['employe_id'] . '</td>';
                echo '<td>' . $row['Nom'] . '</td>';
                echo '<td>' . $row['Poste'] . '</td>';
                echo '<td>' . $row['DateEmbauche'] . '</td>';
                echo '<td>' . $row['email'] . '</td>';
                echo '<td>' . $row['chef_id'] . '</td>';

                echo ' <td><i class="bi bi-eye-fill"></i></td> ';
                echo '</tr>';
              }
          } else  if ($role == 'chef') {
          // Parcourir les résultats de la requête et afficher chaque enregistrement dans une ligne du tableau
          while ($row = mysqli_fetch_assoc($rsltat)) {
              echo '<tr>';
              echo '<td>' . $row['employe_id'] . '</td>';
              echo '<td>' . $row['Nom'] . '</td>';
              echo '<td>' . $row['Poste'] . '</td>';
              echo '<td>' . $row['DateEmbauche'] . '</td>';
              echo '<td>' . $row['email'] . '</td>';

              echo ' <td><i class="bi bi-eye-fill"></i></td> ';
              echo '</tr>';
          }}
        ?>
    </tbody>
</table>
       
</div>

   </div>
   </div>
   </div>
   <br> <br><br>
   
   <?php include('footer.php') ; ?>