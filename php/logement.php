<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mon profil - Logemangue</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
</head>

<?php
  require 'db2withoutlogin.php';
  if (isset($_POST['valider'])) {
      $nom = $_POST['nom'];
      $telephone = $_POST['telephone'];
      $genre = $_POST['genre'];
      $date_naissance = $_POST['date_naissance'];
      $type_utilisateur = $_POST['type_utilisateur'];
      $biography=$_POST['biography'];

      // Validation du numéro de téléphone
      if (!preg_match("/^[0-9]{10}$/", $telephone)) {
          echo "<p>Le numéro de téléphone doit contenir exactement 10 chiffres.</p>";
      }
      // Validation de la date de naissance
      else if (empty($date_naissance)) {
          echo "<p>La date de naissance est requise.</p>";
      }
      // Validation du type d'utilisateur
      else if (empty($type_utilisateur)) {
          echo "<p>Le type d'utilisateur est requis.</p>";
      }
      else {
          // Mettre à jour les champs de l'utilisateur
          $updateSql = "UPDATE users SET nom=?, telephone=?, genre=?, date_naissance=?, type_utilisateur=?,biography=? WHERE id=?";
          $stmt = $conn->prepare($updateSql);
          $stmt->bind_param("ssssssi", $nom, $telephone, $genre, $date_naissance, $type_utilisateur,$biography, $userId);

          if ($stmt->execute()) {
              header("Location: profil.php?update=success");
          } else {
              echo "<p>Erreur lors de la mise à jour des informations.</p>";
          }
      }
  } 
  if (isset($_GET['update']) && $_GET['update'] == 'success') {
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
    <a href="index.php" class="topbar-logo">
      <img src="../png/topbar.png" onresize="3000" alt="Logo" />
    </a>

    <nav class="topbar-nav">
      <a class="nav-link" href="index.php">Accueil</a>
      <a class="nav-link" href="logements.php">Recherche</a>

      <a class="nav-link" href="publish.php">Publier une annonce</a>
      <a class="nav-link" href="logements.php?view=mesannonces">Mes annonces</a>

      <a class="nav-link" href="listemessagerie.php">Ma messagerie</a>
      <?php if ($isAdmin): ?> 
          <a class="nav-link" href="admin.php">Admin ⚙️</a>
      <?php endif; ?>

      <a class="nav-link " href="profil.php">Mon profil</a>
    </nav>
  </header>

<body>
<!-- ====== BLOC PRINCIPAL ====== -->
<!-- ====== BLOC PRINCIPAL ====== -->
<div class="container py-4">

    <div class="row">
        <!-- Grande image -->
        <div class="col-lg-9">
            <img style="box-shadow: 4px 4px 0 #e1e1e1;" src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=1000&h=730&fit=crop" class="banner-img" alt="logement">
        </div>

        <!-- Miniatures -->
        <div class="col-lg-3 d-flex flex-column justify-content-between">
            <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2" class="thumb-img" alt="miniature">
            <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2" class="thumb-img" alt="miniature">
            <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2" class="thumb-img" alt="miniature">
        </div>
    </div>

    <!-- ===== TEXTE + BOUTONS ACTION CÔTE À CÔTE ===== -->
    <div class="row mt-4">
        <!-- Bloc texte (onglets) -->
        <div class="col-lg-9">
          <!-- BOUTONS D'ONGLETS -->
          <div class="mb-3 tab-buttons">
              <button class="tab-btn active" data-tab="description">Description</button>
              <button class="tab-btn" data-tab="localisation">Localisation</button>
              <button class="tab-btn" data-tab="message">Message du propriétaire</button>
          </div>

          <!-- CONTENU DES ONGLETS -->
          <div class="tab-content">
              <div class="tab-pane fade show active info-box" id="description">
                  <ul>
                      <li>À propos de ce logement</li>
                      <li>Prix Loyer / Charges</li>
                      <li>Date de disponibilité</li>
                      <li>Caractéristiques</li>
                  </ul>
              </div>

              <div class="tab-pane fade info-box" id="localisation">
                  <p>📍 Adresse du logement</p>
                  <p>Carte ou informations de localisation ici.</p>
                      <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d2086.8370832268038!2d2.2432445618470607!3d48.777118574786144!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sfr!2sfr!4v1765278973850!5m2!1sfr!2sfr" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
              </div>

              <div class="tab-pane fade info-box" id="message">
                  <p>Message personnalisé du propriétaire ici.</p>
              </div>
          </div>



        </div>

        <!-- Bloc boutons d’action -->
        <div class="col-lg-3">
            <div class="action-card">
                <button class="action-btn">🏢 Organisme</button>
                <button class="action-btn">✉️ Contact</button>
                <button class="action-btn">📄 Candidater</button>
                <button class="action-btn">⭐ Favoris</button>
                <button class="action-btn">📤 Partager</button>
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
<script>
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const targetId = button.getAttribute('data-tab');

            // 1️⃣ Activer le bouton cliqué
            tabButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');

            // 2️⃣ Afficher le contenu correspondant et cacher les autres
            tabPanes.forEach(pane => {
                if(pane.id === targetId){
                    pane.classList.add('show', 'active');
                } else {
                    pane.classList.remove('show', 'active');
                }
            });
        });
    });
</script>
</body>

</html>