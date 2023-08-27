<?php
session_start();
include('server.php');
// Check if the user is logged in as an employee or employeur 
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'employee' && $_SESSION['user_type'] !== 'employeur') {
    // If not logged in as an employee, redirect to the login page
    header("Location: commun/login.php");
    exit();
}?>
<?php include('head.php') ?>
  <header class=" text-center py-5 mt-5 " >
    <div class="container">
      <br><h4 >Mes demandes effectuées  !</h4>
    </div>
  </header>
  <div class="container">
    <table id="table_dmd" class="table table-striped table-hover custom-table" style="width:100%">
        <thead>
            <tr>
            <th data-order="desc">Date de la demande</th>
                <th data-orderable="false">Type de la demande</th>
                <th data-orderable="false">Etat</th>
                <th data-orderable="false">Statut </th>
                <th data-orderable="false" >Consulter</th>
            </tr>
        </thead>
        <tbody>
        <?php
        // Parcourir les résultats de la requête et afficher chaque enregistrement dans une ligne du tableau
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>' . $row['DateSoumission'] . '</td>';
            echo '<td>' . $row['type_dmd'] . '</td>';
            echo '<td>' . $row['etat'] . '</td>';
            echo '<td>' . $row['Statut'] . '</td>';
            echo '<td><button class="btn btn-link btn-details" data-request-id="' . $row['N_dmd'] . '"><i class="bi bi-eye-fill"></i></button></td>';
            echo '</tr>';
        }
        ?>
    </tbody>
</table>    
</div>
   </div>
   </div>
   </div>
    <!-- Modal pour details de demandes -->
    <div class="modal fade" id="requestDetailsModal" tabindex="-1" aria-labelledby="requestDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="requestDetailsModalLabel">Détails de votre demande</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
            <div class="modal-body" id="requestDetailsContent">
                <!-- Contenu des détails de la demande sera chargé ici -->
     
            </div>
        </div>
    </div>
</div>

   <br> <br><br>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = new bootstrap.Modal(document.getElementById('requestDetailsModal'));
        const modalContent = document.getElementById('requestDetailsContent');

        const btnDetails = document.querySelectorAll('.btn-details');

        btnDetails.forEach(btn => {
            btn.addEventListener('click', function () {
                const requestId = this.getAttribute('data-request-id');
                fetch('fetch_request_details.php?id=' + requestId)
                    .then(response => response.text())
                    .then(data => {
                        modalContent.innerHTML = data;
                        modal.show();
                    });
            });
        });
    });
</script>


   <?php include('footer.php') ; ?>