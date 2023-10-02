<?php
include('server.php');

// Initialiser un tableau pour stocker les messages d'erreur
$msg = array();

if (isset($_POST['add_chef'])) {
    // Récupérer les données du formulaire
    $cin = $_POST['cin'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user_type = 'chef';

    // Vérifier si le chef existe dans la table employé en utilisant le CIN
    $query = "SELECT * FROM employe WHERE cin = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $cin);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $existingEmployee = mysqli_fetch_assoc($result);
    mysqli_free_result($result);

    if ($existingEmployee) {
        // Vérifier si l'email existe déjà
        $query = "SELECT * FROM login WHERE email = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $counts = mysqli_num_rows($result);
        mysqli_free_result($result);

        if ($counts > 0) {
            $msg[] = "L'email existe déjà.";
        } else {
            // Valider le mot de passe
            if (empty($password)) {
                $msg[] = "Le mot de passe est requis.";
            } else {
                // Hasher le mot de passe
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Insérer les données dans la table chef
                $sql_chef = "INSERT INTO chef (email, cin, id_entreprise) VALUES (?, ?, ?)";
                $stmt_chef = mysqli_prepare($conn, $sql_chef);
                mysqli_stmt_bind_param($stmt_chef, "ssi", $email, $cin, $entreprise_id);

                // Insérer les données dans la table login
                $sql_login = "INSERT INTO login (email, password, user_type) VALUES (?, ?, ?)";
                $stmt_login = mysqli_prepare($conn, $sql_login);
                mysqli_stmt_bind_param($stmt_login, "sss", $email, $hashedPassword, $user_type);
                
                if (mysqli_stmt_execute($stmt_chef) && mysqli_stmt_execute($stmt_login)) {
                    $successMessage = "Le chef a été ajouté avec succès.";
                } else {
                    $msg[] = "Erreur lors de l'ajout du chef : " . mysqli_error($conn);
                }

                // Fermer les déclarations préparées
                mysqli_stmt_close($stmt_login);
                mysqli_stmt_close($stmt_chef);
            }
        }
    } else {
        $msg[] = "Employé non enregistré dans votre enreprise . Invalid CIN !";
    }
}

?>

<?php include('head.php'); ?>
<header class="text-center py-5 mt-4">
    <div class="container">
        <br>
        <h3>Ajouter un chef responsable</h3>
    </div>
</header>
<div class="items  mx-auto mb-4"style="background-color:#ffffff; width: 50%; ">
<div class="container">
            <form id="AddChefForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <?php
                if (!empty($msg)) {
                    echo '<div class="alert alert-danger text-center">';
                    foreach ($msg as $error) {
                        echo '<p>' . $error . '</p>';
                    }
                    echo '</div>';
                }
                if (isset($successMessage)) {
                    echo "<script>alert(' $successMessage . ');</script>";
                    echo "<script>window.location.href='list_chef.php'</script>";

                }
                ?>
                <div class="form-group mb-4">
                    <label for="ChefCIN">CIN de l'employé : *</label>
                    <input type="text" class="form-control" id="cin" name="cin" value="<?php echo $_POST['cin']; ?>" required>
                </div>
                <div class="form-group mb-4">
                    <label for="ChefEmail">Nouvelle adresse email : *</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $_POST['email']; ?>" required>
                </div>
                <div class="form-group mb-4">
                    <label for="ChefPassword">Mot de passe : *</label>
                    <div class="input-group ">
                        <input type="password" class="form-control" id="password" name="password" value="Chef@123" required>
                        <div class="input-group-append">
                            <span class="input-group-text" id="togglePassword">
                                <i class="bi bi-eye-fill" id="toggleIcon"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="d-grid  col-6 mx-auto mb-4">
                    <button type="submit" name="add_chef" id="addChefButton" class="btn btn-success btn-block mb-2 mx-auto">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include ('footer.php') ?>
<script>
  $(document).ready(function() {
    var passwordField = $('#password');
    var toggleIcon = $('#toggleIcon');

    $('#togglePassword').on('click', function() {
        if (passwordField.attr('type') === 'password') {
            passwordField.attr('type', 'text');
            toggleIcon.removeClass('fa-eye').addClass('bi-eye-slash-fill');
        } else {
            passwordField.attr('type', 'password');
            toggleIcon.removeClass('fa-eye-slash').addClass('bi-eye-fill');
        }
    });
});
</script>