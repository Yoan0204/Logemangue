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
    /* Style spécifique à la page profil */
    .profile-header {
      background: linear-gradient(90deg, #ffb300, #ff7a00);
      color: #000;
      border-radius: 12px;
      display: flex;
      align-items: center;
      padding: 1rem 1.5rem;
      box-shadow: 4px 4px 0 #e6d7c1;
      font-weight: 700;
      margin-bottom: 2rem;
    }

    .profile-header .avatar {
      background-color: #fff;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      font-size: 1.2rem;
      margin-right: 1rem;
      border: 1px solid #ddd;
    }

    .info-box {
      background: #f9f6ee;
      border: 1px solid #000;
      border-radius: 10px;
      padding: 2rem;
    }

    .info-box input,
    .info-box select,
    .info-box textarea {
      border-radius: 10px;
      border: 1px solid #aaa;
      background-color: #fff;
    }

    .info-box textarea {
      resize: none;
      height: 120px;
    }

    .info-title {
      font-weight: 700;
      margin-bottom: 1.5rem;
    }
    .vertical-center {
  margin: 0;
  position: absolute;
  top: 50%;
  -ms-transform: translateY(-50%);
  transform: translateY(-50%);
    }
    .modal-content {
        border-radius: 15px;
        border: none;
    }
    .modal-header, .modal-footer {
        border: none;
    }
    .custom-modal {
        background-color: #ff8c00; /* Couleur orange */
        color: white;
    }
    .custom-modal .btn-close {
        filter: invert(1);
    }
  </style>
</head>
<?php
require "db2.php";

if (isset($_POST["logout"])) {
    // Démarrer la session
    session_start();

    // Supprimer toutes les variables de session
    $_SESSION = array();

    // Détruire la session
    session_destroy();

    // Rediriger vers la page de connexion
    header('Location: login.html');
    exit();
}

if (isset($_POST["valider"])) {
    $nom = $_POST["nom"];
    $telephone = $_POST["telephone"];
    $genre = $_POST["genre"];
    $date_naissance = $_POST["date_naissance"];
    $type_utilisateur = $_POST["type_utilisateur"];
    $biography = $_POST["biography"];
    $facile = $_POST["facile"];

    // Validation du numéro de téléphone
    if (!preg_match("/^[0-9]{10}$/", $telephone)) {
        echo "<p>Le numéro de téléphone doit contenir exactement 10 chiffres.</p>";
    }
    // Validation de la date de naissance
    elseif (empty($date_naissance)) {
        echo "<p>La date de naissance est requise.</p>";
    }
    // Validation du type d'utilisateur
    elseif (empty($type_utilisateur)) {
        echo "<p>Le type d'utilisateur est requis.</p>";
    } else {
        // Mettre à jour les champs de l'utilisateur
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
            header("Location: profil.php?update=success");
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
   <header class="topbar">
    <a href="index" class="topbar-logo">
      <img src="../png/topbar.png" onresize="3000" alt="Logo" />
    </a>

    <nav class="topbar-nav">
      <a class="nav-link" href="index">Accueil</a>
      <a class="nav-link" href="logements">Recherche</a>

      <?php if (!$isEtudiant): ?>
      <a class="nav-link" href="publish">Publier une annonce</a>
      <?php endif; ?>
      <a class="nav-link" href="logements?view=mesannonces">Mes annonces</a>

      <a class="nav-link" href="listemessagerie">Ma messagerie</a>
      <?php if ($isAdmin): ?>
      <a class="nav-link" href="admin">Admin ⚙️</a>
      <?php endif; ?>
      <a class="nav-link active-link" href="profil">Mon profil</a>
    </nav>
  </header>

<body>
    <!-- Contenu principal -->
    <div class="flex-grow-1 p-5">
      <h1 class="fw-bold text-center mb-5">Mon profil</h1>

      <div class="container">
        <div class="profile-header">
          <div class="avatar">C</div>
          <div><?php echo $user["nom"]; ?></div>
        </div>

            <div class="info-box">
              <div class="info-title text-center">Mes informations</div>

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
                    <input type="date" name="date_naissance" class="form-control" placeholder="Date de naissance" value="<?php echo $user[
                        "date_naissance"
                    ]; ?>" required>
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
                    <input type="text" name="facile" class="form-control" placeholder="URL de votre dossier FACILE" value=<?php if(isset($user["facile"])) {echo $user["facile"];}?>>
                  </div>                  
                <?php
                }
                ?> <br>
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
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const toggle = document.getElementById("menu-toggle");
const sidebar = document.getElementById("sidebar");

toggle.addEventListener("click", () => {
  sidebar.classList.toggle("active");
});
  </script>
</body>
<footer class="text-center py-3">
  <?php include "footer.php"; ?>
</footer>
</html>