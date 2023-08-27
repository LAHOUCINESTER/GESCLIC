<?php
session_start();
include('server.php');
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'employee') {
    header("Location: commun/login.php");
    exit();
}

include('head.php');

ob_start();
require('alertes.php');
ob_end_clean();

if (isset($alertes)) {
    $maVariable = $alertes;
}
?>

<header class="text-center py-5 mt-5">
    <div class="container">
        <?php
        if (!empty($maVariable)) {
            echo '<div class="alert alert-warning mb-4 mt-2" role="alert">';
            echo 'Vous avez des nouvelles alertes ! <br><br> <a href="alertes.php" class="alert-link">Cliquez ici pour les consulter</a>';
            echo '</div>';
        } else {
            echo '<div class="alert alert-primary mb-4 mt-2" role="alert">';
            echo 'Aucune alerte !';
            echo '</div>';
        }
        ?>
    </div>
</header>

<section>
    <div class="container">
        <?php
        if ($result_vu && mysqli_num_rows($result_vu) > 0) {
            $row = mysqli_fetch_assoc($result_vu);
            $count = $row['count'];

            if ($count > 0) {
                echo '<div class="card mb-3 text-center">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">Vous avez ' . $count . ' nouvelles demandes reçues.</h5>';
                echo '<p class="card-text">Cliquez sur le bouton ci-dessous pour les consulter.</p>';
                echo '<a href="demandes_recues.php" class="btn btn-orange">Nouvelles demandes reçues !</a>';
                echo '</div>';
                echo '</div>';
            } else {
               
                echo '<div class="card mb-3 text-center">';
                echo '<div class="card-body">';
                echo '<div class="alert alert-info text-center">';
                echo 'Vous n\'avez aucune  demande reçue pour le moment.';
                echo '</div>';
                echo '<a href="demandes_recues.php" class="btn btn-orange">Mes demandes reçues !</a>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<div class="alert alert-info text-center">';
            echo 'Aucune nouvelle demande reçue pour le moment.';
            echo '</div>';
        }
        ?>
    </div>
</section>

<?php include('footer.php'); ?>