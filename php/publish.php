<?php
require 'db2.php'; // connexion à la base

// 🔐 sécurité : userId DOIT exister
if (!isset($userId)) {
    die("Utilisateur non connecté");
}

$editMode = false;
$logement = null;

/* =========================
   MODE ÉDITION
========================= */
if (isset($_GET['id'])) {
    $editMode = true;
    $logementId = (int) $_GET['id'];

    $stmt = $conn->prepare(
        "SELECT * FROM logement WHERE id = ? AND id_proprietaire = ?"
    );
    $stmt->bind_param("ii", $logementId, $userId);
    $stmt->execute();
    $logement = $stmt->get_result()->fetch_assoc();

    if (!$logement) {
        die("Accès interdit ou logement inexistant");
    }
}

/* =========================
   NETTOYAGE INPUT
========================= */
function nettoyer_input($data) {
    return htmlspecialchars(trim(stripslashes($data)), ENT_QUOTES, 'UTF-8');
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    foreach ($_POST as $key => $value) {
        $_POST[$key] = preg_replace('/[^a-zA-Z0-9À-ÿ _\-\'’]/u', '', $value);
    }
}

/* =========================
   TRAITEMENT FORMULAIRE
========================= */
if (
    $_SERVER["REQUEST_METHOD"] === "POST" &&
    isset($_POST['titre'], $_POST['type'], $_POST['adresse'], $_POST['ville'],
          $_POST['code'], $_POST['surface'], $_POST['loyer'], $_POST['description'])
) {

    $titre = $_POST['titre'];
    $type = $_POST['type'];
    $adresse = $_POST['adresse'];
    $ville = $_POST['ville'];
    $code_postal = $_POST['code'];
    $surface = (int)$_POST['surface'];
    $loyer = (int)$_POST['loyer'];
    $charges_incluses = isset($_POST['charges_incluses']) ? 1 : 0;
    $meuble = isset($_POST['meuble']) ? 1 : 0;
    $colocation = isset($_POST['colocation']) ? 1 : 0;
    $description = $_POST['description'];
    $date_disponibilite = $_POST['date_disponibilite'];

    /* ========= UPDATE ========= */
    if (isset($_POST['logement_id'])) {

        $logementId = (int) $_POST['logement_id'];

        $sql = "UPDATE logement SET
            titre = ?, description = ?, adresse = ?, ville = ?, code_postal = ?,
            TYPE = ?, surface = ?, loyer = ?, charges_incluses = ?, meuble = ?,
            colocation = ?, date_disponibilite = ?
            WHERE id = ? AND id_proprietaire = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssssssiiiiisii",
            $titre, $description, $adresse, $ville, $code_postal,
            $type, $surface, $loyer, $charges_incluses, $meuble,
            $colocation, $date_disponibilite,
            $logementId, $userId
        );

        $stmt->execute();

        header("Location: logement?id=$logementId&success=updated");
        exit;
    }

    /* ========= INSERT ========= */
    else {

        $sql = "INSERT INTO logement
        (titre, description, adresse, ville, code_postal, TYPE, surface, loyer,
         charges_incluses, meuble, id_proprietaire, colocation, date_disponibilite)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssssssiiiiisi",
            $titre, $description, $adresse, $ville, $code_postal,
            $type, $surface, $loyer,
            $charges_incluses, $meuble,
            $userId, $colocation, $date_disponibilite
        );

        $stmt->execute();
        $id_logement = $stmt->insert_id;
    }

    /* ========= UPLOAD PHOTOS ========= */
    if (!empty($_FILES['photos']['name'][0])) {

        $targetDir = "../uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        foreach ($_FILES['photos']['tmp_name'] as $key => $tmp_name) {

            if ($_FILES['photos']['error'][$key] !== UPLOAD_ERR_OK) continue;

            $ext = strtolower(pathinfo($_FILES['photos']['name'][$key], PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg','jpeg','png','webp'])) continue;

            if (!getimagesize($tmp_name)) continue;

            $fileName = uniqid('photo_', true) . '.' . $ext;
            $targetFile = $targetDir . $fileName;

            if (move_uploaded_file($tmp_name, $targetFile)) {
                $stmtPhoto = $conn->prepare(
                    "INSERT INTO photo (url_photo, description, id_logement)
                     VALUES (?, '', ?)"
                );
                $stmtPhoto->bind_param("si", $targetFile, $id_logement);
                $stmtPhoto->execute();
                $stmtPhoto->close();
            }
        }
    }

    header("Location: index?publish=success");
    exit;
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Publier une annonce - Logemangue</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
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
      <a class="nav-link " href="index">Accueil</a>
      <a class="nav-link" href="logements">Recherche</a>

      <a class="nav-link active-link" href="publish">Publier une annonce</a>
      <?php if (!$isEtudiant): ?>
      <a class="nav-link" href="logements?view=mesannonces">Mes annonces</a>        
      <?php endif; ?>

      <a class="nav-link" href="listemessagerie">Ma messagerie</a>
      <?php if ($isAdmin): ?>
      <a class="nav-link" href="admin">Admin ⚙️</a>
      <?php endif; ?>
      <a class="nav-link" href="profil">Mon profil</a>
    </nav>
  </header>
<body>
    <!-- Contenu principal -->
    <main class="flex-grow-1 p-4">
      <div class="publication-container shadow p-4 rounded-4">
        <h1 class="text-center text-dark fw-bold p-3 mb-4 publication-header">
          Publier une annonce
        </h1>
        <?php if (isset($_GET["erreur"]) && $_GET["erreur"] == "taillefichier") { ?>
            <div style="margin: 20px; margin-top: 20px;" class="alert alert-danger alert-dismissible fade show" role="alert">
                    Une image que vous avez importer est trop lourde (5mo max par image)<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>          
        <?php } ?>
        <?php if (isset($_GET["erreur"]) && $_GET["erreur"] == "typefichier") { ?>
            <div style="margin: 20px; margin-top: 20px;" class="alert alert-danger alert-dismissible fade show" role="alert">
                    Un fichier que vous avez importé n'est pas une image. Types supportés : .jpg, .jpeg, .png, .webp<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>          
        <?php } ?>        
        <form class="p-4 rounded-4 publication-form" id="formAnnonce" action="publish" method="POST" enctype="multipart/form-data">
          <?php if ($editMode): ?>
          <input type="hidden" name="logement_id"
                value="<?php echo $logement['ID']; ?>">
          <?php endif; ?>

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold">Titre de l'annonce</label>
              <input class="form-control form-field" type="text" id="titre" name="titre" value="<?php echo $editMode ? htmlspecialchars($logement['titre']) : ''; ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Type</label>
              <select class="form-select form-field" id="type" name="type" value="<?php echo $editMode ? htmlspecialchars($logement['type']) : ''; ?>" required>
                <option>Studio</option>
                <option>T1</option>
                <option>T2</option>
                <option>T3</option>
                <option>T4+</option>
              </select>
            </div>

            <div class="col-md-4">
              <label class="form-label fw-semibold">Adresse (quartier)</label>
              <input id="adresse" name="adresse" type="text" class="form-control form-field" placeholder="Quartier du Marais" value="<?php echo $editMode ? htmlspecialchars($logement['adresse']) : ''; ?>" required>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Ville</label>
              <input type="text" class="form-control form-field" placeholder="Paris" id="ville" name="ville" value="<?php echo $editMode ? htmlspecialchars($logement['ville']) : ''; ?>" required>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Code postal</label>
              <input type="text" class="form-control form-field" placeholder="75004" id="code" name="code" value="<?php echo $editMode ? htmlspecialchars($logement['code_postal']) : ''; ?>" required>
            </div>
            <ul class="list-group">
              <li class="list-group-item" style="background: linear-gradient(135deg, var(--yellow), var(--green)); border-radius: 15px; margin-bottom: 2px;" data-vicopo="#ville, #code" data-vicopo-click='{"#code": "code", "#ville": "ville"}'>
                <strong data-vicopo-code-postal></strong>
                <span data-vicopo-ville></span>
              </li>
            </ul>

            <div class="col-md-3">
              <label class="form-label fw-semibold">Surface (m²)</label>
              <input type="number" class="form-control form-field" placeholder="45" id="surface" name="surface" value="<?php echo $editMode ? htmlspecialchars($logement['surface']) : ''; ?>" required>
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold">Loyer (€ / mois)</label>
              <input type="number" class="form-control form-field" placeholder="900" id="loyer" name="loyer" value="<?php echo $editMode ? htmlspecialchars($logement['loyer']) : ''; ?>" required>
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold">Charges incluses</label>
              <input class="custom-checkbox" style="margin-top: 37px;" type="checkbox" id="charges_incluses" name="charges_incluses" value="1" <?php echo $editMode && $logement['charges_incluses'] ? 'checked' : ''; ?>>
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold">Colocation</label>
              <input class="custom-checkbox" style="margin-top: 37px;" type="checkbox" id="colocation" name="colocation" value="1" <?php echo $editMode && $logement['colocation'] ? 'checked' : ''; ?>>
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold">Meublé</label>
              <input class="custom-checkbox" style="margin-top: 37px;" type="checkbox" id="meuble" name="meuble" value="1" <?php echo $editMode && $logement['meuble'] ? 'checked' : ''; ?>>
            </div>            
            <div class="col-md-3">
              <label class="form-label fw-semibold">Date de disponibilité</label>
              <input type="date" class="form-control form-field" id="date_disponibilite" name="date_disponibilite" value="<?php echo $editMode ? htmlspecialchars($logement['date_disponibilite']) : ''; ?>" required>
            </div>

            <div class="col-12">
              <label class="form-label fw-semibold">Description</label>
              <textarea pattern="[A-Za-z0-9' ]+"  class="form-control form-field" rows="4" placeholder="Décrivez votre logement..." id="description" name="description" required ><?php echo $editMode ? htmlspecialchars($logement['description']) : ''; ?></textarea>
            </div>

          <input type="file" name="photos[]" multiple accept="image/*">
          </div>

          <!-- Boutons -->
          <div class="d-flex justify-content-between mt-4">
            <button type="submit" class="btn-login">Publier</button>
          </div>

        </form>
      </div>
    </main>
          <footer class="text-center py-3">
        <?php include 'footer.php'; ?>
      </footer>
  </div>
  <script src="../js/vicopo-vanilla.js"></script>
    <script>
    const toggle = document.getElementById("menu-toggle");
const sidebar = document.getElementById("sidebar");

toggle.addEventListener("click", () => {
  sidebar.classList.toggle("active");
});
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
