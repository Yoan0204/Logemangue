<?php
require 'db2.php';

if (
    isset($_POST['titre']) &&
    isset($_POST['type']) &&
    isset($_POST['adresse']) &&
    isset($_POST['ville']) &&
    isset($_POST['code_postal']) &&
    isset($_POST['surface']) &&
    isset($_POST['loyer']) &&
    isset($_POST['description'])
) {
    $titre = $_POST['titre'];
    $type = $_POST['type'];
    $adresse = $_POST['adresse'];
    $ville = $_POST['ville'];
    $code_postal = $_POST['code_postal'];
    $surface = $_POST['surface'];
    $loyer = $_POST['loyer'];
    $charges_incluses = isset($_POST['charges_incluses']) ? 1 : 0;
    $meuble = isset($_POST['meuble']) ? 1 : 0;
    $description = $_POST['description'];
    $id_proprietaire = $userId; // À remplacer par l'ID de l'utilisateur connecté

    // Requête préparée pour éviter les injections SQL
    $sql = "INSERT INTO logement (titre, description, adresse, ville, code_postal, TYPE, surface, loyer, charges_incluses, meuble, id_proprietaire)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssdiiiii", $titre, $description, $adresse, $ville, $code_postal, $type, $surface, $loyer, $charges_incluses, $meuble, $id_proprietaire);

    if ($stmt->execute()) {
        echo "L'annonce a été publiée avec succès!";
    } else {
        echo "Erreur: " . $stmt->error;
    }

    $stmt->close();
} else {
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Publier une annonce - Logemangue</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/Style.css">
</head>
  <header class="topbar">
    <a href="index.php" class="topbar-logo">
      <img src="../png/topbar.png" onresize="3000" alt="Logo" />
    </a>

    <nav class="topbar-nav">
      <a class="nav-link " href="index.php">Accueil</a>
      <a class="nav-link" href="logements.php">Recherche</a>

      <a class="nav-link active-link" href="publish.php">Publier une annonce</a>
      <a class="nav-link" href="logements.php?view=mesannonces">Mes annonces</a>

      <a class="nav-link" href="listemessagerie.php">Ma messagerie</a>

      <a class="nav-link" href="admin.php">Admin ⚙️</a>

      <a class="nav-link" href="profil.php">Mon profil</a>
    </nav>
  </header>
<body>
    <!-- Contenu principal -->
    <main class="flex-grow-1 p-4">
      <div class="publication-container shadow p-4 rounded-4">
        <h1 class="text-center text-dark fw-bold p-3 mb-4 publication-header">
          Publier une annonce
        </h1>

        <form class="p-4 rounded-4 publication-form" id="formAnnonce" action="publish.php" method="POST" enctype="multipart/form-data">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold">Titre de l'annonce</label>
              <input class="form-control form-field" type="text" id="titre" name="titre" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Type</label>
              <select class="form-select form-field" id="type" name="type" required>
                <option>Studio</option>
                <option>T1</option>
                <option>T2</option>
                <option>T3</option>
                <option>T4+</option>
              </select>
            </div>

            <div class="col-md-4">
              <label class="form-label fw-semibold">Adresse (quartier)</label>
              <input id="adresse" name="adresse" type="text" class="form-control form-field" placeholder="Quartier du Marais" required>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Ville</label>
              <input type="text" class="form-control form-field" placeholder="Paris" id="ville" name="ville" required>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Code postal</label>
              <input type="text" class="form-control form-field" placeholder="75004" id="code_postal" name="code_postal" required>
            </div>

            <div class="col-md-3">
              <label class="form-label fw-semibold">Surface (m²)</label>
              <input type="number" class="form-control form-field" placeholder="45" id="surface" name="surface" required>
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold">Loyer (€ / mois)</label>
              <input type="number" class="form-control form-field" placeholder="900" id="loyer" name="loyer" required>
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold">Charges incluses</label>
              <input class="custom-checkbox" style="margin-top: 37px;" type="checkbox" id="charges_incluses" name="charges_incluses" value="1">
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold">Meublé</label>
              <input class="custom-checkbox" style="margin-top: 37px;" type="checkbox" id="meuble" name="meuble" value="1">
            </div>

            <div class="col-12">
              <label class="form-label fw-semibold">Description</label>
              <textarea class="form-control form-field" rows="4" placeholder="Décrivez votre logement..." id="description" name="description" required></textarea>
            </div>

          <input type="file" name="photos[]" multiple>
          </div>

          <!-- Boutons -->
          <div class="d-flex justify-content-between mt-4">
            <button type="submit" class="btn-login">Publier</button>
          </div>
        </form>
      </div>
    </main>
  </div>
    <script>
    const toggle = document.getElementById("menu-toggle");
const sidebar = document.getElementById("sidebar");

toggle.addEventListener("click", () => {
  sidebar.classList.toggle("active");
});
  </script>
</body>
</html>
