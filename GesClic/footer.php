</div><br><hr>
<footer class="text-center text-white">
<div class="footer">
        <div class="container "><br>
          <section class="mb-4">
            <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i class="bi bi-facebook"></i></a>
            <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i class="bi bi-linkedin"></i></a>
            <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i class="bi bi-google"></i></a>
            <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i class="bi bi-github"></i></a>
          </section>
        </div>      
        <!-- Copyright -->
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">© 2023 Copyright:<a class="text-white" href="#"></a></div>
</div>
</footer>

<!-- Scripts -->
<!-- Inclure les fichiers JavaScript de Bootstrap -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>


</body>
</html>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Inclure jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Inclure DataTables -->
<script src="https://cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.13.5/i18n/fr-FR.json"></script>

<script>
$(document).ready(function() {
    $('#table_dmd').DataTable({
        "order": [[7, "asc"], [0, "desc"]],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/fr-FR.json',
        }
    });
});
        /** Afficher autres cases selon types demandes (remplire formulaire) */
$(document).ready(function() {
            $("#type_dmd").on("change", function() {
            var selectedValue = $(this).val();
            if (selectedValue === "Congé") {
                $("#congeFields").show();
                $("#bltnPaieFields").hide();
            } else if (selectedValue === "Bulletin de paie") {
                $("#congeFields").hide();
                $("#bltnPaieFields").show();
            } else {
                $("#congeFields").hide();
                $("#bltnPaieFields").hide();
            }
            });
});
function updateDateFields() {
    const periodeInput = document.getElementById("periode");
    const dateDebutInput = document.getElementById("DateDebut_conge");
    const dateFinInput = document.getElementById("DateFin_conge");

    const selectedPeriode = parseInt(periodeInput.value);

    if (!isNaN(selectedPeriode)) {
        const currentDate = new Date(dateDebutInput.value);
        const endDate = new Date(currentDate.getTime() + (selectedPeriode - 1) * 24 * 60 * 60 * 1000);

        dateFinInput.value = formatDate(endDate);
    }
}

function updatePeriod() {
    const dateDebutInput = document.getElementById("DateDebut_conge");
    const dateFinInput = document.getElementById("DateFin_conge");
    const periodeInput = document.getElementById("periode");

    const dateDebut = new Date(dateDebutInput.value);
    const dateFin = new Date(dateFinInput.value);

    if (!isNaN(dateDebut) && !isNaN(dateFin)) {
        const diffTime = Math.abs(dateFin - dateDebut);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

        periodeInput.value = diffDays;
    } else {
        periodeInput.value = "";
    }
}
function formatDate(date) {
    const year = date.getFullYear();
    const month = (date.getMonth() + 1).toString().padStart(2, "0");
    const day = date.getDate().toString().padStart(2, "0");
    return `${year}-${month}-${day}`;
}

let isConfirmed = false;

document.getElementById("confirmationButton").addEventListener("click", function() {
    if (confirm("Êtes-vous sûr de vouloir envoyer cette demande ?")) {
        isConfirmed = true;
        document.getElementById("dmd_form").submit();
    } else {
        // L'utilisateur a annulé, ne faites rien
    }
});

// Avant de soumettre le formulaire, vérifiez si la confirmation a été donnée
document.getElementById("dmd_form").addEventListener("submit", function(event) {
    if (!isConfirmed) {
        event.preventDefault(); // Empêche l'envoi du formulaire
    }
});

        /** for active-link  */
$(document).ready(function() {
            const url = window.location.href;
            $('.navbar-nav .nav-item .nav-link').each(function() {
                if (url.includes($(this).attr('href'))) {
                    $(this).addClass('active-link');
                }
            });
});
  
$(document).ready(function() {
            $('.item').hover(function() {
                $(this).toggleClass('active');
            });
});
       
    // Restore "Remember Me" checkbox state and fill in username from local storage, if available
document.addEventListener('DOMContentLoaded', function() {
        const rememberMeCheckbox = document.getElementById('rememberMe');
        const EmailInput = document.getElementById('email');
        const rememberMeValue = localStorage.getItem('rememberMe');
        const savedEmail = localStorage.getItem('email');

        if (rememberMeValue === 'true') {
            rememberMeCheckbox.checked = true;
        }

        if (rememberMeCheckbox.checked && savedEmail) {
            EmailInput.value = savedEmail;
        }
});

        // Save "Remember Me" checkbox state and username to local storage
    const rememberMeCheckbox = document.getElementById('rememberMe');
    const EmailInput = document.getElementById('email');

        rememberMeCheckbox.addEventListener('change', function() {
        localStorage.setItem('rememberMe', this.checked);
        if (!this.checked) {
            localStorage.removeItem('email');
        }
        });

        EmailInput.addEventListener('input', function() {
        if (rememberMeCheckbox.checked) {
            localStorage.setItem('email', this.value);
        }
        });

var activeSection = 'personal';  // Section par défaut
function showSection(section) {
    // Masquer la section active
    document.getElementById(activeSection).style.display = 'none';

    // Afficher la nouvelle section
    document.getElementById(section).style.display = 'block';
    activeSection = section;
}



function showSection(sectionId) {
    // Masquer toutes les sections
    const sections = document.querySelectorAll('.main-content > div');
    sections.forEach(section => {
        section.style.display = 'none';
    });

    // Afficher la section sélectionnée
    const selectedSection = document.getElementById(sectionId);
    selectedSection.style.display = 'block';

    // Masquer les messages d'erreur dans toutes les sections
    const errorMessages = document.querySelectorAll('.error-message');
    errorMessages.forEach(errorMessage => {
        errorMessage.style.display = 'none';
    });

    // Afficher le message d'erreur uniquement dans la section de changement de mot de passe
    if (sectionId === 'password') {
        const passwordError = document.getElementById('password-error');
        passwordError.style.display = 'block';
    }

    // Ajouter la classe active au bouton cliqué
    const navButtons = document.querySelectorAll('.nav-button');
    navButtons.forEach(button => {
        if (button.getAttribute('onclick').includes(sectionId)) {
            button.classList.add('active');
        } else {
            button.classList.remove('active');
        }
    });
}
document.addEventListener('DOMContentLoaded', function() {
        const passwordSection = document.getElementById('password');
        const errorMessage = passwordSection.querySelector('.error-message');

        // Afficher la section de changement de mot de passe
        passwordSection.style.display = 'block';

        // Afficher les messages d'erreur s'ils existent
        if (errorMessage.textContent !== '') {
            errorMessage.style.display = 'block';
        }
});
      
    </script>
   
</body>
</html>
