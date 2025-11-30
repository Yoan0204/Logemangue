<?php
require 'db2.php';

// Requête pour récupérer tous les logements
$sql = "SELECT * FROM logement WHERE status='Approved'";
$result = $conn->query($sql);
if (isset($_POST['delete'])) {
    $id = $_POST['logement_id'];

    $sql = "UPDATE logement SET status = 'Waiting' WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    echo "Le logement a été marqué comme supprimé !";
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recherche de logements - Logemangue</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
  <header class="topbar">
    <a href="index.php" class="topbar-logo">
      <img src="../png/topbar.png" onresize="3000" alt="Logo" />
    </a>

    <nav class="topbar-nav">
      <a class="nav-link " href="index.php">Accueil</a>
      <a class="nav-link active-link" href="recherche.html">Recherche</a>

      <a class="nav-link" href="publish.html">Publier une annonce</a>
      <a class="nav-link " href="mesannonces.php">Mes annonces</a>

      <a class="nav-link" href="listemessagerie.php">Ma messagerie</a>

      <a class="nav-link" href="admin.php">Admin ⚙️</a>

      <a class="nav-link " href="profil.php">Mon profil</a>
    </nav>
  </header>
<body>
    <!-- CONTENU PRINCIPAL -->
    <main class="flex-grow-1 p-4">

      <!-- BARRE DE RECHERCHE PLEINE LARGEUR -->
      <div class="search-bar p-3 rounded-14 shadow-sm mb-4">
        <div class="search-grid">
          <input type="text" class="form-control search-input"
            placeholder="Rechercher un logement, une ville, un type...">
          <input type="text" class="form-control" placeholder="Ville">
          <select class="form-select">
            <option>Type</option>
            <option>Studio</option>
            <option>T1</option>
            <option>T2</option>
            <option>Colocation</option>
          </select>
          <button id="toggleFilters" class="btn btn-filters">Plus de filtres non? ⚙️</button>
        </div>
      </div>


      <!-- FILTRES AVANCÉS -->
      <div id="filtersSection" class="filters p-4 rounded-4 shadow-sm mb-4" style="display:none;">
        <div class="row g-3 align-items-center">
          <div class="col-md-6">
            <label class="form-label fw-semibold">Budget (€)</label>
            <input type="range" class="form-range custom-range" min="0" max="2000" step="50" id="rangeBudget">
            <div class="d-flex justify-content-between small fw-semibold">
              <span>0€</span><span>1350€</span><span>2000€</span>
            </div>
          </div>
          <div class="col-md-3">
            <input type="date" class="form-control" placeholder="Date de disponibilité">
          </div>
          <div class="col-md-3">
            <input type="number" class="form-control" placeholder="Surface (m²)">
          </div>
        </div>

        <div class="row g-3 mt-3">
          <div class="col-md-2 d-flex align-items-center gap-2">
            <input type="checkbox" id="meuble" class="custom-checkbox">
            <label for="meuble" class="form-label mb-0">Meublé</label>
          </div>
          <div class="col-md-2 d-flex align-items-center gap-2">
            <input type="checkbox" id="coloc" class="custom-checkbox">
            <label for="coloc" class="form-label mb-0">Colocation</label>
          </div>
          <div class="col-md-4">
            <select class="form-select">
              <option>Proposé par (Agence, Particulier...)</option>
              <option>Agence</option>
              <option>Particulier</option>
            </select>
          </div>
          <div class="col-md-4">
            <input type="text" class="form-control" placeholder="Mots-clés">
          </div>
        </div>

        <div class="row mt-3 align-items-center">
          <div class="col-md-4">
            <label class="form-label fw-semibold">Note minimale :</label>
            <div class="stars" id="starRating">
              <span class="star" data-value="1">★</span>
              <span class="star" data-value="2">★</span>
              <span class="star" data-value="3">★</span>
              <span class="star" data-value="4">★</span>
              <span class="star" data-value="5">★</span>
            </div>
          </div>
        </div>
      </div>

      <hr class="my-4 border-2 opacity-100" style="color: var(--green);">

      <!-- LOGEMENTS -->    
      <div class="d-flex">      
        <div class="container-fluid" style="max-width: 2000px;">        
          <div class="row g-4">              
            <div class="container py-4">        
              <h1 class="text-center mb-4">Liste des Logements</h1>        
              <div class="row justify-content-center">            
                <?php            
                // Afficher les résultats            
                if ($result->num_rows > 0) {                
                  while($row = $result->fetch_assoc()) {                    
                  ?>                    
                  <div class="col-md-4">                        
                    <a href="logement.php?id=<?php echo $row['ID']; ?>" class="logement-link">                            
                      <div class="logement-card">                                
                        <img src="test.webp" alt="<?php echo $row['titre']; ?>">                                
                        <div class="info">                                    
                          <h6 class="fw-bold mb-1"><?php echo $row['titre']; ?></h6>                                    
                          <p class="text-muted mb-0"><?php echo $row['loyer']; ?> € / mois</p>                                    
                          <p class="small text-muted">                                        
                            Disponible : <?php echo ($row['disponible'] == 1) ? 'Oui' : 'Non'; ?>                                    
                          </p>
                          <?php
                          // Supposons que $user contient les informations de l'utilisateur actuel
                          // et que $user['is_admin'] vaut 1 pour un admin
                          if ($user['is_admin'] == 1) {
                          ?>
                              <!-- Formulaire HTML avec le bouton visible seulement pour l'admin -->
                              <form method="post">
                                  <input type="hidden" name="logement_id" value=<?php echo $row['ID']; ?>> <!-- Remplace 1 par l'ID du logement -->
                                  <button type="submit" class="btn-unapproved" name="delete">Supprimer</button>
                              </form>
                          <?php
                          }
                          ?>                                                          
                        </div>                            
                      </div>                        
                    </a>                    
                  </div>                   
                  <?php                
                }            
              } else {                
                echo "<div class='col-12'><p class='text-center'>Aucun logement trouvé.</p></div>";            
              }            
            ?>        
          </div>    
        </div>        
      </div>      
    </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Toggle affichage filtres
    const toggleBtn = document.getElementById('toggleFilters');
    const filtersSection = document.getElementById('filtersSection');
    toggleBtn.addEventListener('click', () => {
      filtersSection.style.display = filtersSection.style.display === 'none' ? 'block' : 'none';
    });

    // Sélecteur d'étoiles
    const stars = document.querySelectorAll('#starRating .star');
    stars.forEach(star => {
      star.addEventListener('click', () => {
        const value = parseInt(star.dataset.value);
        stars.forEach(s => {
          s.classList.toggle('selected', parseInt(s.dataset.value) <= value);
        });
      });
    });
  </script>
    <script>
    const toggle = document.getElementById("menu-toggle");
const sidebar = document.getElementById("sidebar");

toggle.addEventListener("click", () => {
  sidebar.classList.toggle("active");
});
  </script>
</body>

</html>