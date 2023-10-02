<?php
session_start();

$errors = array();
$servername = 'localhost';
$username = 'root';
$password = '';
$db = 'gesclic';
// On établit la connexion
$conn = mysqli_connect($servername, $username, $password, $db);
if (!$conn) {
    echo "Connexion interrompue";
}
date_default_timezone_set('Africa/Casablanca');

// Constantes pour les messages d'erreur
define("ERROR_EMAIL_REQUIRED", "L'e-mail est requis");
define("ERROR_PASSWORD_REQUIRED", "Le mot de passe est requis");
define("ERROR_INVALID_EMAIL", "Le format de votre mail est invalide");
define("ERROR_EMAIL_EXISTS", "Cet email est déjà associé à un compte");
define("ERROR_WEAK_PASSWORD", "Votre mot de passe doit comporter au moins 8 caractères et doit inclure au moins une lettre majuscule, un chiffre et un caractère spécial");
define("ERROR_WRONG_PASS", "Les nouveaux mots de passe ne correspondent pas");
define("ERROR_LOGIN_FAILED", "Nom d'utilisateur ou mot de passe incorrect");

// LOGIN USER

if (isset($_POST['login_button'])) {
    // Retrieve form data
    $email = $_POST['email'];
    $password = $_POST['password'];
    $remember_me = isset($_POST['rememberMe']) ? true : false;

    // If there are no errors, proceed with login
    if (empty($errors)) {
        // Query to retrieve hashed password and user type from login table
        $sql = "SELECT password, user_type FROM login WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 1) {
            // Fetch hashed password and user type from database
            $row = mysqli_fetch_assoc($result);
            $hashedPasswordFromDatabase = $row['password'];
            $userType = $row['user_type'];

            // Verify if submitted password matches the hashed password from the database
            if (password_verify($password, $hashedPasswordFromDatabase)) {
                // Store user type in session variable
                $_SESSION['user_type'] = $userType;
                $_SESSION['email'] = $email;

                // Query to retrieve relevant ID based on user type
                if ($userType === 'employee' ) {
                    $idColumn = 'employe_id';
                    $table = 'employe';
                } else if ($userType === 'employeur') {
                    $idColumn = 'id_admin';
                    $table = 'administrateurs';
                }else if ($userType === 'chef') {
                    $idColumn = 'chef_id';
                    $table = 'chef';
                }

                $sql_id = "SELECT $idColumn FROM $table WHERE email = ?";
                $stmt_id = mysqli_prepare($conn, $sql_id);
                mysqli_stmt_bind_param($stmt_id, "s", $email);
                mysqli_stmt_execute($stmt_id);
                $result_id = mysqli_stmt_get_result($stmt_id);

                if (mysqli_num_rows($result_id) == 1) {
                    // Fetch the relevant ID from database
                    $row_id = mysqli_fetch_assoc($result_id);
                    $userId = $row_id[$idColumn];

                    // Store the ID in session variable
                    $_SESSION['user_id'] = $userId;
                    if ($remember_me) {
                        // Définir le cookie avec une durée de validité plus longue, par exemple 30 jours
                        setcookie('email', $email, time() + (30 * 24 * 60 * 60));
                        setcookie('password', $password, time() + (30 * 24 * 60 * 60));
                    }
            

                    // Redirect to appropriate page based on user type
                    if ($userType === 'employee') {
                        header("Location: pointer.php");
                        exit();
                    } else if ($userType === 'employeur') {
                        header("Location: employeur.php");
                        exit();
                    } else if ($userType === 'chef') {
                        header("Location: chef.php");
                        exit();
                    }
                } else {
                    $errors['login'] = "Utilisateur introuvable";
                }

                // Close the statement for fetching the relevant ID
                mysqli_stmt_close($stmt_id);
            } else {
                // Invalid login credentials
                $errors['login'] = "Nom d'utilisateur ou mot de passe incorrect";
            }
        } else {
            $errors['login'] = "Vous n'êtes pas inscrit ! Veuillez créer un nouveau compte .";
        }
        // Close the statement for fetching hashed password and user type
        mysqli_stmt_close($stmt);
    } else {
        $errors['login'] = "Erreur lors de la connexion. Veuillez réessayer plus tard.";
    }
}



// Sign up USER
if (isset($_POST['signup_button'])) {
    // Retrieve form data
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user_type = $_POST['user_type'];

    if (empty($user_type)) {
        $errors['user_type'] = "Veuillez indiquer votre rôle !";
    }
    // Validate email
    if (empty($email)) {
        $errors['email'] = ERROR_INVALID_EMAIL;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = ERROR_INVALID_EMAIL;
    } else {
        // Check if email already exists
        $query = "SELECT * FROM login WHERE email = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $counts = mysqli_num_rows($result);
        mysqli_free_result($result);
        if ($counts > 0) {
            $errors['email'] = ERROR_EMAIL_EXISTS;
        }
    }
    // Validate password
    if (empty($password)) {
        $errors['password'] = ERROR_PASSWORD_REQUIRED;
    } else {
        // Validate password strength
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number    = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@[^\w]@', $password);

        if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
            $errors['password'] = ERROR_WEAK_PASSWORD;
        }
    }
    // If there are no validation errors, proceed to insert data into the database
    if (empty($errors)) {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Function to insert data into administrateurs table
        function insertAdminData($conn, $login_id, $email)
        {
            $sql_admin = "INSERT INTO administrateurs (id_login, email) VALUES (?, ?)";
            $stmt_admin = mysqli_prepare($conn, $sql_admin);
            mysqli_stmt_bind_param($stmt_admin, "is", $login_id, $email);

            if (mysqli_stmt_execute($stmt_admin)) {
                // Get the inserted admin ID
                $admin_id = mysqli_insert_id($conn);

                mysqli_stmt_close($stmt_admin);
                return $admin_id;
            } else {
                // Error handling for administrateurs table
                mysqli_stmt_close($stmt_admin);
                return false;
            }
        }
        // Function to insert data into entreprise table
        function insertEntrepriseData($conn, $id_entreprise)
        {
            $sql_entreprise = "INSERT INTO entreprises (id_entreprise) VALUES (?)";
            $stmt_entreprise = mysqli_prepare($conn, $sql_entreprise);
            mysqli_stmt_bind_param($stmt_entreprise, "i", $id_entreprise);

            if (mysqli_stmt_execute($stmt_entreprise)) {
                // Success
                mysqli_stmt_close($stmt_entreprise);
                return true;
            } else {
                // Error handling for entreprise table
                mysqli_stmt_close($stmt_entreprise);
                return false;
            }
        }
        // Function to insert data into employe table
        function insertEmployeeData($conn, $login_id, $email)
        {
            $sql_employe = "INSERT INTO employe (id_login, email) VALUES (?, ?)";
            $stmt_employe = mysqli_prepare($conn, $sql_employe);
            mysqli_stmt_bind_param($stmt_employe, "is", $login_id, $email);

            if (mysqli_stmt_execute($stmt_employe)) {
                // Success
                mysqli_stmt_close($stmt_employe);
                return true;
            } else {
                // Error handling for employe table
                mysqli_stmt_close($stmt_employe);
                return false;
            }
        }

        // Prepare and bind the INSERT statement for login table
        $sql_login = "INSERT INTO login (email, password, user_type) VALUES (?, ?, ?)";
        $stmt_login = mysqli_prepare($conn, $sql_login);
        mysqli_stmt_bind_param($stmt_login, "sss", $email, $hashedPassword, $user_type);

        // Execute the statement for login table
        if (mysqli_stmt_execute($stmt_login)) {
            // Get the inserted login ID
            $login_id = mysqli_insert_id($conn);

            // Prepare and execute the appropriate statement based on user type
           // Prepare and execute the appropriate statement based on user type
            if ($user_type === "employeur") {
                $admin_id = insertAdminData($conn, $login_id, $email);
                if ($admin_id && insertEntrepriseData($conn, $login_id)) {
                    // Prepare and execute the statement for admin_entreprise table
                    $sql_admin_entreprise = "INSERT INTO admin_entreprise (id_admin, id_entreprise) VALUES (?, ?)";
                    $stmt_admin_entreprise = mysqli_prepare($conn, $sql_admin_entreprise);
                    mysqli_stmt_bind_param($stmt_admin_entreprise, "ii", $admin_id, $login_id);

                    if (mysqli_stmt_execute($stmt_admin_entreprise)) {
                        // Success message
                        echo "<script>alert('Vous êtes inscrit avec succès en tant qu\'employeur.');</script>";
                    } else {
                        // Error handling for admin_entreprise table
                        $errors['signup'] = "Erreur lors de l'enregistrement table admin_entreprise!";
                    }
                } else {
                    // Error handling for administrateurs or entreprise table
                    $errors['signup'] = "Erreur lors de l'enregistrement table administrateurs ou entreprise!";
                }
            } elseif ($user_type === "employee") {
                if (insertEmployeeData($conn, $login_id, $email)) {
                    // Success message
                    echo "<script>alert('Vous êtes inscrit avec succès en tant qu\'employé.');</script>";
                } else {
                    // Error handling for employetable
                    $errors['signup'] = "Erreur lors de l'enregistrement table employe!";
                }
            }

            // Close the statement for login table
            mysqli_stmt_close($stmt_login);

            // Success message and redirection
            echo "<script>window.location.href='login.php'</script>";
            exit();
        } else {
            // Error handling for login table
            $errors['signup'] = "Erreur lors de l'enregistrement ! Veuillez réessayer plus tard.";
        }

        // Close the statement for login table
        mysqli_stmt_close($stmt_login);
    }
}

$admin_id = $_SESSION['user_id'];
    $entreprise = "SELECT id_entreprise FROM admin_entreprise WHERE id_admin = '$admin_id'";
    $result_ste = mysqli_query($conn, $entreprise);

    if ($result_ste && mysqli_num_rows($result_ste) > 0) {
        $row = mysqli_fetch_assoc($result_ste);
        $entreprise_id = $row['id_entreprise'];

        // Stockez l'entreprise_id dans la variable de session
        $_SESSION['id_entreprise'] = $entreprise_id;
    } else {
        // Gérer le cas où l'id_entreprise n'est pas trouvé
        $entreprise_id = '' ;    
    }


             // SQL  request 
if (isset($_SESSION['user_id'])) {
    $employe_id = $_SESSION['user_id'];
    // Effectuer une requête SELECT pour récupérer les demandes effectuées par l'employé actif
    $dm_effe = "SELECT * FROM demande WHERE employe_id = '$employe_id' ORDER BY DateSoumission DESC" ;
    $result = mysqli_query($conn, $dm_effe);
    $result_admin = mysqli_query($conn, "SELECT * FROM demande WHERE id_admin = '$employe_id' ORDER BY DateSoumission DESC" );
   

     // Effectuer une requête SELECT pour récupérer les demandes recue par l'employé actif
     $dm_rec = "SELECT * FROM demande WHERE employeID_destinataire = '$employe_id' ORDER BY vu ASC, DateSoumission DESC";
     $res_rec = mysqli_query($conn, $dm_rec);
}
$employe_id = $_SESSION['user_id'];
    $dmnd = "SELECT d.*, e.Nom AS nom_employe , e.prenom AS prenom_employe 
    FROM demande d 
    JOIN employe e ON d.employe_id = e.employe_id
    WHERE e.id_entreprise = '$entreprise_id'
    ORDER BY DateSoumission DESC" ;
    $rslt = mysqli_query($conn, $dmnd);

	// h_entree	h_entree_sys	h_entree_chef	h_sortie	h_sortie_sys	h_sortie_chef
    $empCc = "SELECT e.Nom , h_entree ,h_entree_sys ,h_entree_chef,h_sortie,h_sortie_sys,h_sortie_chef
    FROM employe e , pointer
    where e.employe_id = pointer.employe_id ";
    $rsltaaat = mysqli_query($conn, $empCc) ;




?>
