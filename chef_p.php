<?php

include('server.php');

// Check if the user is logged in as an employee
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'chef'){
    // If not logged in as an employee, redirect to the login page
    header("Location: login.php");
    exit();
}
$chef_id = $_SESSION['user_id']; 
$email = $_SESSION['email']; 

?>
<?php include('head.php') ?>
<header class="text-center py-5 mt-4">
    <div class="container">
        <br>
        <h4 class="text-center mt-4 mb-4">Changer le mot de passe :</h4>
    </div>
</header>
<div id="password" >
                <div class="items mx-auto" style="background-color:#ffffff; width: 80%;">
                    <div class="container">
                        <!-- Afficher les messages d'erreur uniquement si la section password est active -->
                        <?php if (isset($_GET['error']) && $_GET['section'] === 'password') { echo $_GET['error']; } ?>
                        <form action="change-p.php" method="post" id="password-form">
                        <?php
                            if (isset($_GET['error'])) {
                                $error_message = urldecode($_GET['error']);
                                echo '<div class="alert alert-danger">' . $error_message . '</div>';
                            } elseif (isset($_GET['success'])) {
                                $success_message = urldecode($_GET['success']);
                                echo '<div class="alert alert-success">' . $success_message . '</div>';
                            }
                        ?>
                            <div class="row form-group">
                                    <div class="col-sm-6">
                                        <label>Votre E-mail :</label>
                                    </div>
                                    <div class="col-sm-6">
                                        <input class="form-control" type="text" name="email" value="<?php echo $email ?>" ReadOnly>
                                    </div>
                            </div>
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
    </div>
<?php include('footer.php') ?>