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

// Constantes pour les messages d'erreur
define("ERROR_EMAIL_REQUIRED", "L'e-mail est requis");
define("ERROR_PASSWORD_REQUIRED", "Le mot de passe est requis");
define("ERROR_INVALID_EMAIL", "Le format de votre mail est invalide");
define("ERROR_EMAIL_EXISTS", "Cet email est déjà associé à un compte");
define("ERROR_WEAK_PASSWORD", "Votre mot de passe doit comporter au moins 8 caractères et doit inclure au moins une lettre majuscule, un chiffre et un caractère spécial");
define("ERROR_LOGIN_FAILED", "Nom d'utilisateur ou mot de passe incorrect");

// LOGIN USER
if (isset($_POST['login_button'])) {
    // Retrieve form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate email
    if (empty($email)) {
        $errors['email'] = ERROR_EMAIL_REQUIRED;
    }
    // Validate password
    if (empty($password)) {
        $errors['password'] = ERROR_PASSWORD_REQUIRED; 
    }
    // If there are no errors, proceed with login
    if (empty($errors)) {
        // Query to retrieve hashed password from database
        $sql = "SELECT password FROM login WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 1) {
            // Fetch hashed password from database
            $row = mysqli_fetch_assoc($result);
            $hashedPasswordFromDatabase = $row['password'];
            $hashedPasswordFromDatabase = substr($hashedPasswordFromDatabase,0,60);
         
            // Verify if submitted password matches the hashed password from the database
            if (password_verify($password, $hashedPasswordFromDatabase)) {
                // Fetch user type from database
              $sql = "SELECT user_type FROM login WHERE email = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                    $row = mysqli_fetch_assoc($result);
                    $userType = $row['user_type'];
                    // Store user type in session variable
                    $_SESSION['user_type'] = $userType;
                    // Redirect to appropriate page based on user type
                    if ($userType === 'employee') {
                        header("Location: ../employe.php");
                        exit();
                    } else if ($userType === 'employeur') {
                        header("Location: ../employeur.php");
                        exit();
                    }
                }
            } else {
                // Invalid login credentials
                $errors['login'] = ERROR_LOGIN_FAILED;
            } 
        } 
        // Close the statement
        mysqli_stmt_close($stmt);
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
            $errors['email'] = ERROR_INVALID_EMAIL ;
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
            // Prepare and bind the INSERT statement
            $sql = "INSERT INTO login (email, password, user_type) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sss", $email, $hashedPassword, $user_type);
                // Execute the statement
                if (mysqli_stmt_execute($stmt)) {
                    // Handle success
                    echo "<script>alert('Vous êtes inscrit avec succès');</script>";
                    echo "<script>window.location.href='login.php'</script>";
                } else {
                // Invalid signup credentials
                $errors['login'] = "Erreur lors de l\'enregistrement ! Veuillez rééssayer pls tard .";
                }
            // Close the statement
            mysqli_stmt_close($stmt);
        }
}

?>
