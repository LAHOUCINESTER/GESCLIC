<?php
session_start();
include('server.php');
include('head.php');

// Vérifier si l'utilisateur est connecté en tant qu'employé
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'employeur' && $_SESSION['user_type'] !== 'chef') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Récupérer le mois actuel et l'année actuelle
$currentMonth = date('n');
$currentYear = date('Y');
// Variables pour filtrer les données
$selectedMonth = isset($_POST['month']) ? $_POST['month'] : $currentMonth;
$selectedYear = isset($_POST['year']) ? $_POST['year'] : $currentYear;
$selectedEmployee = isset($_POST['employee']) ? $_POST['employee'] : '';
$todayOnly = isset($_POST['todayOnly']);

if ( $_SESSION['user_type'] == 'employeur' ) {
// Requête SQL de base pour récupérer les données
$sql = "SELECT pointer.*, employe.nom AS employe_nom, chef.nom AS chef_nom 
        FROM pointer 
         JOIN employe ON pointer.employe_id = employe.employe_id
         JOIN employe AS chef ON pointer.chef_id = chef.employe_id
        WHERE MONTH(pointer.date) = $selectedMonth AND YEAR(pointer.date) = $selectedYear  AND employe.id_entreprise = '$entreprise_id'";
}elseif ($_SESSION['user_type'] == 'chef') {
    $sql = "SELECT pointer.*, employe.nom AS employe_nom
        FROM pointer 
         JOIN employe ON pointer.employe_id = employe.employe_id
        WHERE MONTH(pointer.date) = $selectedMonth AND YEAR(pointer.date) = $selectedYear  AND  employe.chef_id =$user_id";
}
// Ajouter le filtre d'employé si sélectionné
if (!empty($selectedEmployee)) {
    $selectedEmployee = mysqli_real_escape_string($conn, $selectedEmployee);
    $sql .= " AND employe.employe_id = '$selectedEmployee'";
}

// Ajouter le filtre pour les enregistrements d'aujourd'hui seulement
if ($todayOnly) {
    $today = date('Y-m-d');
    $sql .= " AND pointer.date = '$today'";
}
$sql .= " ORDER BY pointer.date";
// Exécuter la requête SQL
$result = mysqli_query($conn, $sql);

?>

<header class="text-center py-5">
    <div class="container">
        <!-- Contenu de l'en-tête si nécessaire -->
    </div>
</header>

<div class="container">
    <div class="menu">
        <a href="test_pointage.php" >
            <button id="monthly" class="active-button">Pointage journalier</button>
        </a>
        <a href="pt_mensuel.php" >
            <button id="monthly" class="">Pointage Mensuel</button>
        </a>
        <a href="pt_annuel.php">
            <button id="Annual">Pointage Annuel</button>
        </a>
        <a href="pt_horaire.php">
            <button id="hourly">Pointage Horaire</button>
        </a>
    </div>
    <h4 id="hourlyTitle" class="text-center mt-2 mb-4">Pointage journalier</h4> 

    <form method="post" class="ml-2 mb-4 mt-4" id="monthlyForm">
        <div class="form-row align-items-center">
                        <!-- Sélectionner l'employé -->
            <div class="col-md-4">
                <label class="sr-only" for="employee">Employé</label>
                <select class="form-control mb-2" id="employee" name="employee" onchange="this.form.submit()">
                    <option value="">Tous les employés</option>
                    <?php
                    if ( $_SESSION['user_type'] == 'employeur') {
                        $employeeSql = "SELECT nom, employe_id FROM employe WHERE employe.id_entreprise = '$entreprise_id'";
                    } elseif ($_SESSION['user_type'] == 'chef') {
                        $employeeSql = "SELECT nom, employe_id FROM employe WHERE employe.chef_id = '$user_id'";
                    }
                    $employeeResult = mysqli_query($conn, $employeeSql);
                    while ($employeeRow = mysqli_fetch_assoc($employeeResult)) {
                        $employeeId = $employeeRow['employe_id'];
                        $employeeName = $employeeRow['nom'];
                        $selected = ($selectedEmployee == $employeeId) ? 'selected' : '';
                        echo "<option value='$employeeId' $selected>$employeeName</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Sélectionner le mois -->
            <div class="col-md-2">
                <label class="sr-only" for="month">Mois</label>
                <select class="form-control mb-2" id="month" name="month" onchange="this.form.submit()">
                    <?php
                    $frenchMonths = array(
                        1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril', 5 => 'Mai', 6 => 'Juin',
                        7 => 'Juillet', 8 => 'Août', 9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
                    );
                    foreach ($frenchMonths as $monthNumber => $monthName) {
                        $selected = ($selectedMonth == $monthNumber) ? 'selected' : '';
                        echo "<option value='$monthNumber' $selected>$monthName</option>";
                    }
                    ?>
                </select>
            </div>
            <!-- Sélectionner l'année -->
            <div class="col-md-2">
                <label class="sr-only" for="year">Année</label>
                <select class="form-control mb-2" id="year" name="year" onchange="this.form.submit()">
                    <?php
                    $currentYear = date('Y');
                    for ($year = $currentYear; $year >= 2020; $year--) {
                        $selected = ($selectedYear == $year) ? 'selected' : '';
                        echo "<option value='$year' $selected>$year</option>";
                    }
                    ?>
                </select>
            </div>
            </div>

            <!-- Filtrer les enregistrements d'aujourd'hui seulement -->
            <div class="col-auto mt-2">
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="todayOnly" name="todayOnly" <?php if ($todayOnly) echo "checked"; ?> onchange="this.form.submit()">
                    <label class="form-check-label" for="todayOnly">Enregistrements d'aujourd'hui seulement</label>
                </div>
            </div>
    </form>

    <table class="table table-bordered">
        <thead class="table-primary">
            <tr>
                <th>Date</th>
                <th>Employé  </th>
                <th>Heure d'entrée</th>
                <th>Heure de sortie</th>
                <th>Heures travaillées</th>
                <?php if ( $_SESSION['user_type'] == 'employeur' ) { ?>
                <th>Chef responsable </th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
        <?php
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $heure_arrivee = new DateTime($row['h_entree_chef']);
                    $heure_depart = $row['h_sortie_chef'] ? new DateTime($row['h_sortie_chef']) : null;
                    
                    if ($heure_depart !== null) {
                        $interval = $heure_arrivee->diff($heure_depart);
                        $heures_travaillees = $interval->format('%h h %i min');
                    } else {
                        $heures_travaillees = '--';
                    }
                    
                    echo "<tr>";
                    echo "<td>{$row['date']}</td>";
                    echo "<td>{$row['employe_nom']}</td>";
                    echo "<td>{$row['h_entree_chef']}</td>";
                    echo "<td>{$row['h_sortie_chef']}</td>";
                    echo "<td>$heures_travaillees</td>";
                    if ( $_SESSION['user_type'] == 'employeur' ) { 
                        echo "<td>{$row['chef_nom']}</td>";
                     }
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>Aucun enregistrement trouvé.</td></tr>";
            }
        ?>
        </tbody>
    </table>
    <?php 
    // Pagination
$resultsPerPage = 7; // Nombre d'enregistrements par page
$totalResults = mysqli_num_rows($result);
$totalPages = ceil($totalResults / $resultsPerPage); // Nombre total de pages

// Vérifier la page actuelle
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $currentPage = (int)$_GET['page'];
} else {
    $currentPage = 1; // Page par défaut
}

// Vérifier si la page est en dehors de la plage valide
if ($currentPage < 1) {
    $currentPage = 1;
} elseif ($currentPage > $totalPages) {
    $currentPage = $totalPages;
}

// Calculer l'offset pour la requête SQL
$offset = ($currentPage - 1) * $resultsPerPage;
$sql .= " LIMIT $offset, $resultsPerPage";
$result = mysqli_query($conn, $sql);

    ?>
 <!-- Pagination -->
 <nav aria-label="Pagination">
            <ul class="pagination justify-content-center">
                <?php if ($totalPages > 1) : ?>
                    <?php if ($currentPage > 1) : ?>
                        <li class="page-item">
                            <a class="page-link" href="test_pointage.php?page=<?php echo $currentPage - 1; ?>">Précédent</a>
                        </li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                        <li class="page-item <?php echo ($i === $currentPage) ? 'active' : ''; ?>">
                            <a class="page-link" href="test_pointage.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <?php if ($currentPage < $totalPages) : ?>
                        <li class="page-item">
                            <a class="page-link" href="test_pointage.php?page=<?php echo $currentPage + 1; ?>">Suivant</a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</div>
<?php include('footer.php'); ?>
