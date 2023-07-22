<?php
session_start();

// Check if the user is logged in as an employee
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'employeur') {
    // If not logged in as an employee, redirect to the login page
    header("Location: commun/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-jBq2Ei5fYBCX+W8Ol0vlpSG6IkC8eAty6Kw0Kc/IfvrpBnfnwHNK1Bm/GeTuAxvfVXqQ1zC8JGu+QGnTtXryXQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="css/landing1.css">

    <title>Welcome ADMIN</title>

</head>
<body>


<h1>Welcome, Admin!</h1>
    <h2>Déconnexion</h2>
    <p>Vous êtes sur le point de vous déconnecter de votre compte.</p>
    <form action="logout.php" method="post">
      <input type="submit" value="Déconnexion">
    </form>
  </body>
</html>



    <!-- Add your employee content here -->

    <!-- Add a logout link or button to log out -->
    <a href="logout.php">Logout</a>
</body>

</html>
