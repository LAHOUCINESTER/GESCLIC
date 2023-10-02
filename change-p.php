<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inclure votre fichier de configuration de la base de données
include('server.php');
include('db.php');

// Vérification si le formulaire a été soumis
if (isset($_POST['change_p'])) {
    $user_type = $_SESSION['user_type'];

    // Récupérer les données du formulaire
    $old_password = $_POST['op'];
    $new_password = $_POST['np'];
    $confirm_new_password = $_POST['c_np'];

    // Vérifier si les nouveaux mots de passe correspondent
    if ($new_password !== $confirm_new_password) {
        $password_error = "Les nouveaux mots de passe ne correspondent pas";
    }

    // Valider la force du mot de passe
    $uppercase = preg_match('@[A-Z]@', $new_password);
    $lowercase = preg_match('@[a-z]@', $new_password);
    $number    = preg_match('@[0-9]@', $new_password);
    $specialChars = preg_match('@[^\w]@', $new_password);

    if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($new_password) < 8) {
        $password_error = "Le mot de passe doit contenir au moins 8 caractères et inclure des majuscules, des minuscules, des chiffres et des caractères spéciaux.";
    }
    if (isset($password_error)) {
        // Rediriger avec le message d'erreur
        if ($user_type === "employee") {
            header("Location: employe.php?error=" . urlencode($password_error) . "#password");
            exit();
        } elseif ($user_type === "chef") {
            header("Location: chef_p.php?error=" . urlencode($password_error) . "#password");
            exit();
        } elseif ($user_type === "employeur") {
            header("Location: societe.php?error=" . urlencode($password_error) . "#password");
            exit();
        }
    }
        // Récupérer l'employe_id de l'utilisateur
        $user_id = $_SESSION['user_id'];


        if ($user_type === "employee") {
            $query_email = "SELECT email FROM employe WHERE employe_id = ?";

        }elseif ($user_type === "chef") {
            $query_email = "SELECT email FROM chef WHERE chef_id = ?";

        }elseif ($user_type === "employeur") {
            $query_email = "SELECT email FROM administrateurs WHERE id_admin = ?";

        }
        $stmt_email = mysqli_prepare($conn, $query_email);
        mysqli_stmt_bind_param($stmt_email, "i", $user_id);
        mysqli_stmt_execute($stmt_email);
        $result_email = mysqli_stmt_get_result($stmt_email);
        $employe_data = mysqli_fetch_assoc($result_email);
        $email = $employe_data['email'];

        // Récupérer le mot de passe actuel de la base de données
        $query = "SELECT password FROM login WHERE email = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $login_data = mysqli_fetch_assoc($result);

        // Vérifier le mot de passe hashé
        if (password_verify($old_password, $login_data['password'])) {
            // Mettre à jour le mot de passe dans la table "login"
            $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_query = "UPDATE login SET password = ? WHERE email = ?";
            $update_stmt = mysqli_prepare($conn, $update_query);
            mysqli_stmt_bind_param($update_stmt, "ss", $hashed_new_password, $email);
            mysqli_stmt_execute($update_stmt);
            // Rediriger avec le message de succès           
            // Rediriger avec le message de succès
            if ($user_type === "employee") {
                header("Location: employe.php?success=Le mot de passe a été modifié avec succès#password");
                exit();
            } elseif ($user_type === "chef") {
                header("Location: chef_p.php?success=Le mot de passe a été modifié avec succès#password");
                exit();
            } elseif ($user_type === "employeur") {
                header("Location: societe.php?success=Le mot de passe a été modifié avec succès#password");
                exit();
            }
        } else {
            $password_error = "Ancien mot de passe incorrect";
            // Rediriger avec le message d'erreur
            if ($user_type === "employee") {
                header("Location: employe.php?error=" . urlencode($password_error) . "#password");
                exit();
            } elseif ($user_type === "chef") {
                header("Location: chef_p.php?error=" . urlencode($password_error) . "#password");
                exit();
            } elseif ($user_type === "employeur") {
                header("Location: societe.php?error=" . urlencode($password_error) . "#password");
                exit();
            }
        }
    } else {
        // Rediriger en cas de tentative d'accès direct
        if ($user_type === "employee") {
            header("Location: employe.php");
            exit();
        } elseif ($user_type === "chef") {
            header("Location: chef_p.php");
            exit();
        } elseif ($user_type === "employeur") {
            header("Location: societe.php");
            exit();
        } 
    }
?>
