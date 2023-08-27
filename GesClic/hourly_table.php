
<h4 id="hourlyTitle" class="text-center hidden mb-4">Pointage Horaire</h4>
<form method="post" class="mb-4" id="hourlyForm">
<div class="form-row align-items">
        <div class="col-4 ">
            <div class="form-group">
                <label for="yearSelect">Sélectionnez une année:</label>
                <select class="form-control" id="yearSelect" name="year" onchange="this.form.submit()">
                    <?php
                    // Générer les options pour le select
                    for ($i = date('Y'); $i >= 2020; $i--) {
                        $selected = ($i == $currentYear) ? 'selected' : '';
                        echo "<option value='$i' $selected>$i</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
</form>
<table id="hourlyTable" class="hidden ">
    <!-- Hourly attendance table content -->
    <thead>
        <tr class="table-primary">
            <th>Mois</th>
            <?php
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, 1, date("Y")); // Nombre de jours en janvier
            for ($day = 1; $day <= $daysInMonth; $day++) {
                echo "<th>{$day}</th>";
            }
            ?>
            <th>Total heures travaillées</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Tableau pour stocker les noms des mois
        $months = array(
            1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril', 5 => 'Mai', 6 => 'Juin',
            7 => 'Juillet', 8 => 'Août', 9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
        );

        // Tableau pour stocker les totaux des heures travaillées par mois
        $monthlyTotals = array();

        // Initialiser les totaux à zéro pour chaque mois
        foreach ($months as $monthNumber => $monthName) {
            $monthlyTotals[$monthNumber] = 0;
        }

        // Boucle à travers chaque mois
        foreach ($months as $monthNumber => $monthName) {
            echo "<tr>";
            echo "<td class='table-primary'>{$monthName}</td>";

            // Boucle à travers chaque jour du mois
            for ($day = 1; $day <= $daysInMonth; $day++) {
                // Format de la date courante en format 'Y-m-d' (Année-Mois-Jour)
                $currentDate = date('Y-m-d', mktime(0, 0, 0, $monthNumber, $day, date('Y')));

                // Effectuer une requête SQL pour obtenir les heures travaillées pour chaque jour
                if ($_SESSION['user_type'] === 'employee') {
                    // If the user is an employee, get the attendance record for that employee only
                    if (isset($_SESSION['user_id'])) {
                        $employe_id = $_SESSION['user_id'];
                        $sql = "SELECT h_entree, h_sortie FROM pointer WHERE date = '$currentDate' AND employe_id = '$employe_id'";
                    }
                } else if ($_SESSION['user_type'] === 'employeur') {
                    // If the user is an employeur, get the attendance records for all employees
                    $sql = "SELECT h_entree, h_sortie FROM pointer WHERE date = '$currentDate'";
                }

                $result = mysqli_query($conn, $sql);

                // Vérifier si la requête a réussi et qu'il y a des enregistrements
                if ($result && mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $heureEntree = $row['h_entree'];
                    $heureSortie = $row['h_sortie'];

                    // Calculer les heures travaillées pour la journée
                    $heureEntree = strtotime($heureEntree);
                    $heureSortie = strtotime($heureSortie);
                    $dureeTravaillee = round(($heureSortie - $heureEntree) / 3600, 2); // Convertir en heures
                    if ($dureeTravaillee < 0) {
                        $dureeTravaillee = 0;
                    }
                    // Ajouter les heures travaillées du jour au total du mois correspondant
                    $monthlyTotals[$monthNumber] += $dureeTravaillee;

                    // Afficher les heures travaillées pour la journée
                    if ($dureeTravaillee > 0) {
                        echo "<td>{$dureeTravaillee}</td>";
                    } else {
                        // Afficher "N/A" si aucune heure n'est disponible pour la journée
                        echo "<td class='na-cell'>N</td>";
                    }
                } else {
                    // Afficher "N/A" si aucune heure n'est disponible pour la journée
                    echo "<td class='na-cell'> 0</td>";
                }
            }

            // Afficher le total des heures travaillées pour le mois en cours
            echo "<td>{$monthlyTotals[$monthNumber]} h</td>";

            echo "</tr>";
        }
        ?>
    </tbody>
</table>
