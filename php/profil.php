<?php
require "db2.php";

if (isset($_POST["logout"])) {
    session_start();
    $_SESSION = [];
    session_destroy();
    header("Location: login.html");
    exit();
}

if (isset($_POST["valider"])) {
    $nom = $_POST["nom"];
    $telephone = $_POST["telephone"];
    $genre = $_POST["genre"];
    $date_naissance = $_POST["date_naissance_value"] ?? null;
    $type_utilisateur = $_POST["type_utilisateur"];
    $biography = $_POST["biography"];
    $facile = $_POST["facile"];

    if (!preg_match("/^[0-9]{10}$/", $telephone)) {
        echo "<p>Le numéro de téléphone doit contenir exactement 10 chiffres.</p>";
    } elseif (empty($date_naissance)) {
        echo "<p>La date de naissance est requise.</p>";
    } elseif (
        !str_starts_with(
            $facile,
            "locataire.dossierfacile.logement.gouv.fr/public-file/"
        ) &&
        !$facile == null
    ) {
        header("Location: profil?erreur=badurl");
        exit();
    } elseif (empty($type_utilisateur)) {
        echo "<p>Le type d'utilisateur est requis.</p>";
    } else {
        $updateSql =
            "UPDATE users SET nom=?, telephone=?, genre=?, date_naissance=?, type_utilisateur=?,biography=?, facile=? WHERE id=?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param(
            "sssssssi",
            $nom,
            $telephone,
            $genre,
            $date_naissance,
            $type_utilisateur,
            $biography,
            $facile,
            $userId
        );

        if ($stmt->execute()) {
            header("Location: profil?update=success");
        } else {
            echo "<p>Erreur lors de la mise à jour des informations.</p>";
        }
    }
}
if (isset($_GET["update"]) && $_GET["update"] == "success") {
    echo "
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
            myModal.show();
        });
    </script>
    ";
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mon profil - Logemangue</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/profil.css">
  <style>
    /* CALENDRIER PERSONNALISÉ */
    .calendar-modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      z-index: 1000;
      justify-content: center;
      align-items: center;
    }

    .calendar-modal.active {
      display: flex;
    }

    .calendar-container {
      background: white;
      border-radius: 15px;
      padding: 25px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
      max-width: 400px;
      width: 90%;
      animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
      from {
        transform: translateY(-30px);
        opacity: 0;
      }
      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    .calendar-header {
      display: flex;
      flex-direction: column;
      gap: 15px;
      margin-bottom: 20px;
      padding-bottom: 15px;
      border-bottom: 3px solid;
      border-image: linear-gradient(90deg, #ffd700, #ffa500) 1;
    }

    .calendar-month-nav {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .calendar-title {
      font-size: 20px;
      font-weight: bold;
      background: linear-gradient(135deg, #ffd700, #ffa500);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .calendar-nav {
      display: flex;
      gap: 10px;
    }

    .calendar-year-nav {
      display: flex;
      gap: 10px;
      align-items: center;
      justify-content: center;
    }

    .year-selector {
      padding: 8px 15px;
      border: 2px solid #ffa500;
      border-radius: 8px;
      background: white;
      font-size: 16px;
      font-weight: 600;
      color: #333;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .year-selector:focus {
      outline: none;
      border-color: #ffd700;
      box-shadow: 0 0 0 3px rgba(255, 165, 0, 0.2);
    }

    .year-btn {
      width: 35px;
      height: 35px;
      border: none;
      border-radius: 50%;
      background: linear-gradient(135deg, #ffd700, #ffa500);
      color: white;
      cursor: pointer;
      font-size: 16px;
      font-weight: bold;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s ease;
      box-shadow: 0 4px 10px rgba(255, 165, 0, 0.3);
    }

    .year-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 15px rgba(255, 165, 0, 0.4);
    }

    .nav-btn {
      width: 35px;
      height: 35px;
      border: none;
      border-radius: 50%;
      background: linear-gradient(135deg, #ffd700, #ffa500);
      color: white;
      cursor: pointer;
      font-size: 18px;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s ease;
      box-shadow: 0 4px 10px rgba(255, 165, 0, 0.3);
    }

    .nav-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 15px rgba(255, 165, 0, 0.4);
    }

    .calendar-grid {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      gap: 6px;
      margin-bottom: 10px;
    }

    .day-header {
      text-align: center;
      font-weight: 600;
      color: #666;
      padding: 8px 0;
      font-size: 12px;
    }

    #calendar-days {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      gap: 6px;
      grid-column: 1 / -1;
    }

    .day-cell {
      aspect-ratio: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s ease;
      font-weight: 500;
      font-size: 14px;
    }

    .day-cell:hover:not(.empty):not(.disabled) {
      background: linear-gradient(135deg, #ffd700, #ffa500);
      color: white;
      transform: scale(1.1);
    }

    .day-cell.today {
      background: linear-gradient(135deg, #ffd700, #ffa500);
      color: white;
      font-weight: bold;
      box-shadow: 0 4px 12px rgba(255, 165, 0, 0.4);
    }

    .day-cell.selected {
      background: linear-gradient(135deg, #ff8c00, #ff6b00);
      color: white;
      font-weight: bold;
      box-shadow: 0 4px 12px rgba(255, 108, 0, 0.5);
    }

    .day-cell.empty {
      cursor: default;
      color: #ccc;
    }

    .day-cell.disabled {
      cursor: not-allowed;
      color: #ddd;
      background: #f5f5f5;
    }

    .calendar-actions {
      display: flex;
      gap: 10px;
      margin-top: 20px;
    }

    .calendar-btn {
      flex: 1;
      padding: 10px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .calendar-btn.validate {
      background: linear-gradient(135deg, #ffd700, #ffa500);
      color: white;
    }

    .calendar-btn.validate:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(255, 165, 0, 0.4);
    }

    .calendar-btn.cancel {
      background: #f0f0f0;
      color: #666;
    }

    .calendar-btn.cancel:hover {
      background: #e0e0e0;
    }

    .age-warning {
      background: linear-gradient(135deg, #fff5e6, #ffe6cc);
      border-left: 4px solid #ffa500;
      padding: 12px;
      border-radius: 6px;
      margin-top: 15px;
      font-size: 13px;
      color: #666;
      text-align: left;
    }

    .age-error {
      background: #ffe6e6;
      border-left: 4px solid #e74c3c;
      padding: 12px;
      border-radius: 6px;
      margin-top: 15px;
      font-size: 13px;
      color: #c0392b;
      text-align: left;
      display: none;
    }

    .age-error.show {
      display: block;
    }

    input[name="date_naissance"] {
      cursor: pointer;
    }
  </style>
</head> 
   <header class="topbar">
    <a href="index" class="topbar-logo">
      <img src="../png/topbar.png" onresize="3000" alt="Logo" />
    </a>
  <div class="burger-menu">
    <span></span>
    <span></span>
    <span></span>
  </div>
    <nav class="topbar-nav">
      <a class="nav-link" href="index">Accueil</a>
      <a class="nav-link" href="logements">Recherche</a>

      <?php if (!$isEtudiant): ?>
      <a class="nav-link" href="publish">Publier une annonce</a>
      <?php endif; ?>
      <?php if (!$isEtudiant): ?>
      <a class="nav-link" href="logements?view=mesannonces">Mes annonces</a>        
      <?php endif; ?>
      <?php if ($isEtudiant): ?>
      <a class="nav-link" href="candidatures">Mes candidatures</a>        
      <?php endif; ?>
      <a class="nav-link" href="listemessagerie">Ma messagerie</a>
      <?php if ($isAdmin): ?>
      <a class="nav-link" href="admin">Admin ⚙️</a>
      <?php endif; ?>
      <a class="nav-link active-link" href="profil">Mon profil</a>
    </nav>
  </header>

<body>
    <div class="flex-grow-1 p-5">
      <h1 class="fw-bold text-center mb-5">Mon profil</h1>

      <div class="container">
        <div class="profile-header">
          <div class="avatar">C</div>
          <div><?php echo $user["nom"]; ?></div>
        </div>

            <div class="info-box">
              <div class="info-title text-center">Mes informations</div>
              <?php if (
                  isset($_GET["erreur"]) &&
                  $_GET["erreur"] == "badurl"
              ) { ?>
      <div style="margin: 20px; margin-top: 20px;" class="alert alert-danger alert-dismissible fade show" role="alert">
              L'URL de votre dossier FACILE n'est pas bonne.                    
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div> 
        <?php } ?>

              <form method="post" action="">
                <div class="row g-3 mb-3">
                  <div class="col-md-6">
                    <input type="text" name="nom" class="form-control" placeholder="Nom" value="<?php echo $user[
                        "nom"
                    ]; ?>" required>
                  </div>
                  <div class="col-md-6">
                    <input type="text" name="telephone" class="form-control" placeholder="Numéro de téléphone" value="<?php echo $user[
                        "telephone"
                    ]; ?>" required>
                  </div>
                </div>

                <div class="row g-3 mb-3">
                  <div class="col-md-6">
                    <select name="genre" class="form-select" required>
                        <option value="">Sélectionnez un genre</option>
                        <option value="Homme" <?php if (
                            $user["genre"] == "Homme"
                        ) {
                            echo "selected";
                        } ?>>Homme</option>
                        <option value="Femme" <?php if (
                            $user["genre"] == "Femme"
                        ) {
                            echo "selected";
                        } ?>>Femme</option>
                        <option value="Autre" <?php if (
                            $user["genre"] == "Autre"
                        ) {
                            echo "selected";
                        } ?>>Autre</option>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <input type="text" name="date_naissance" id="date_naissance" class="form-control" placeholder="Date de naissance (cliquez pour choisir)" value="<?php echo $user[
                        "date_naissance"
                    ]; ?>" readonly required>
                    <input type="hidden" id="date-hidden" name="date_naissance_value" value="<?php echo $user[
                        "date_naissance"
                    ]; ?>">
                  </div>
                </div>
                <div class="row g-3 mb-3">
                  <div class="col-md-6">
                    <select name="type_utilisateur" class="form-select" required>
                        <option value="">Sélectionnez un type</option>
                        <option value="Etudiant" <?php if (
                            $user["type_utilisateur"] == "Etudiant"
                        ) {
                            echo "selected";
                        } ?>>Étudiant</option>
                        <option value="Proprietaire" <?php if (
                            $user["type_utilisateur"] == "Proprietaire"
                        ) {
                            echo "selected";
                        } ?>>Propriétaire</option>
                        <option value="Organisme" <?php if (
                            $user["type_utilisateur"] == "Organisme"
                        ) {
                            echo "selected";
                        } ?>>Organisme</option>
                    </select>
                  </div>
                </div>
                <?php if ($user["type_utilisateur"] == "Etudiant") { ?>
                  <div>
                    <input type="text" name="facile" class="form-control" placeholder="URL de votre dossier FACILE ( locataire.dossierfacile.logement.gouv.fr/public-file/... )" value=<?php if (
                        isset($user["facile"])
                    ) {
                        echo $user["facile"];
                    } ?>>
                  </div>                  
                <?php } ?> <br>
                <textarea class="form-control text-center mb-3" name="biography" placeholder="<?php echo $user[
                    "biography"
                ]; ?>"></textarea> 
                
                <div class="d-flex justify-content-between align-items-center">
                  <button type="submit" class="btn-login" name="valider">Valider</button> 
                  <button class="btn-login" type="submit" name="logout">Se déconnecter</button>
              </form>
                </div>
            </div>

            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content custom-modal">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Changement de vos informations</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>
                        <div class="modal-body">
                            Les mises à jour ont bien été effectuées.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
                        </div>
                    </div>
                </div>
            </div>
      </div>                 
    </div>

    <!-- CALENDRIER MODAL -->
    <div class="calendar-modal" id="calendar-modal">
      <div class="calendar-container">
        <div class="calendar-header">
          <div class="calendar-month-nav">
            <h2 class="calendar-title" id="monthYear"></h2>
            <div class="calendar-nav">
              <button type="button" class="nav-btn" onclick="changeMonth(-1)">‹</button>
              <button type="button" class="nav-btn" onclick="changeMonth(1)">›</button>
            </div>
          </div>
          
          <div class="calendar-year-nav">
            <button type="button" class="year-btn" onclick="changeYear(-1)">-</button>
            <select class="year-selector" id="year-selector" onchange="changeToYear()"></select>
            <button type="button" class="year-btn" onclick="changeYear(1)">+</button>
          </div>
        </div>
        
        <div class="calendar-grid">
          <div class="day-header">Lun</div>
          <div class="day-header">Mar</div>
          <div class="day-header">Mer</div>
          <div class="day-header">Jeu</div>
          <div class="day-header">Ven</div>
          <div class="day-header">Sam</div>
          <div class="day-header">Dim</div>
        </div>
        
        <div id="calendar-days"></div>

        <div class="age-warning">
          ℹ️ Vous devez avoir au moins 16 ans
        </div>

        <div class="age-error" id="age-error">
          ❌ Cette date correspond à moins de 16 ans. Veuillez choisir une date antérieure.
        </div>
        
        <div class="calendar-actions">
          <button type="button" class="calendar-btn cancel" onclick="closeCalendar()">Annuler</button>
          <button type="button" class="calendar-btn validate" onclick="validateDate()">Valider</button>
        </div>
      </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const toggle = document.getElementById("menu-toggle");
const sidebar = document.getElementById("sidebar");

if (toggle && sidebar) {
  toggle.addEventListener("click", () => {
    sidebar.classList.toggle("active");
  });
}
  </script>

  <!-- CALENDRIER SCRIPT -->
  <script>
    let currentDate = new Date();
    let selectedDate = null;
    const months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

    const today = new Date();
    const maxDate = new Date(today.getFullYear() - 16, today.getMonth(), today.getDate());

    document.getElementById('date_naissance').addEventListener('click', function() {
      document.getElementById('calendar-modal').classList.add('active');
      populateYearSelector();
      renderCalendar();
    });

    function populateYearSelector() {
      const yearSelector = document.getElementById('year-selector');
      yearSelector.innerHTML = '';
      
      const currentYear = new Date().getFullYear();
      const startYear = currentYear - 100;
      const endYear = currentYear - 16;
      
      for (let year = endYear; year >= startYear; year--) {
        const option = document.createElement('option');
        option.value = year;
        option.textContent = year;
        if (year === currentDate.getFullYear()) {
          option.selected = true;
        }
        yearSelector.appendChild(option);
      }
    }

    function changeYear(increment) {
      currentDate.setFullYear(currentDate.getFullYear() + increment);
      document.getElementById('year-selector').value = currentDate.getFullYear();
      renderCalendar();
    }

    function changeToYear() {
      const selectedYear = parseInt(document.getElementById('year-selector').value);
      currentDate.setFullYear(selectedYear);
      renderCalendar();
    }

    function renderCalendar() {
      const year = currentDate.getFullYear();
      const month = currentDate.getMonth();
      
      document.getElementById('monthYear').textContent = `${months[month]} ${year}`;
      
      const firstDay = new Date(year, month, 1);
      const lastDay = new Date(year, month + 1, 0);
      const daysInMonth = lastDay.getDate();
      const startingDayOfWeek = (firstDay.getDay() + 6) % 7;
      
      const calendarDays = document.getElementById('calendar-days');
      calendarDays.innerHTML = '';
      
      for (let i = 0; i < startingDayOfWeek; i++) {
        const emptyCell = document.createElement('div');
        emptyCell.className = 'day-cell empty';
        calendarDays.appendChild(emptyCell);
      }
      
      for (let day = 1; day <= daysInMonth; day++) {
        const dayCell = document.createElement('div');
        dayCell.className = 'day-cell';
        dayCell.textContent = day;
        
        const cellDate = new Date(year, month, day);
        
        if (cellDate > maxDate) {
          dayCell.classList.add('disabled');
        } else {
          if (day === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
            dayCell.classList.add('today');
          }
          
          dayCell.onclick = () => selectDate(day, month, year, dayCell, cellDate);
        }
        
        calendarDays.appendChild(dayCell);
      }
    }

    function selectDate(day, month, year, element, cellDate) {
      if (cellDate > maxDate) {
        document.getElementById('age-error').classList.add('show');
        return;
      }
      
      document.getElementById('age-error').classList.remove('show');
      
      document.querySelectorAll('.day-cell.selected').forEach(cell => {
        cell.classList.remove('selected');
      });
      
      element.classList.add('selected');
      selectedDate = new Date(year, month, day);
    }

    function changeMonth(increment) {
      currentDate.setMonth(currentDate.getMonth() + increment);
      renderCalendar();
    }

    function validateDate() {
      if (!selectedDate) {
        alert('Veuillez sélectionner une date');
        return;
      }
      
      if (selectedDate > maxDate) {
        document.getElementById('age-error').classList.add('show');
        return;
      }
      
      const day = String(selectedDate.getDate()).padStart(2, '0');
      const month = String(selectedDate.getMonth() + 1).padStart(2, '0');
      const year = selectedDate.getFullYear();
      
      const formattedDate = `${day}/${month}/${year}`;
      const isoDate = `${year}-${month}-${day}`;
      
      document.getElementById('date_naissance').value = formattedDate;
      document.getElementById('date-hidden').value = isoDate;
      
      closeCalendar();
    }

    function closeCalendar() {
      document.getElementById('calendar-modal').classList.remove('active');
      selectedDate = null;
    }

    document.getElementById('calendar-modal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeCalendar();
      }
    });
  </script>
</body>
<footer class="text-center py-3">
  <?php include "footer.php"; ?>
</footer>
</html>