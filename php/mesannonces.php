<?php
require 'db2.php';

// Requête pour récupérer tous les logements
$sql = "SELECT * FROM logement WHERE id_proprietaire = $userId";
$result = $conn->query($sql);


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
<style>

</style>
  <header class="topbar">
    <a href="index.php" class="topbar-logo">
      <img src="../png/topbar.png" onresize="3000" alt="Logo" />
    </a>

    <nav class="topbar-nav">
      <a class="nav-link " href="index.php">Accueil</a>
      <a class="nav-link" href="logements.php">Recherche</a>

      <a class="nav-link" href="publish.html">Publier une annonce</a>
      <a class="nav-link active-link" href="logements.php?view=mesannonces">Mes annonces</a>

      <a class="nav-link" href="listemessagerie.php">Ma messagerie</a>
      <a class="nav-link" href="admin.php">Admin ⚙️</a>
      <a class="nav-link " href="profil.php">Mon profil</a>
    </nav>
  </header>
<body>

    <!-- CONTENU PRINCIPAL -->
    <main class="flex-grow-1 p-4">

      <!-- LOGEMENTS -->
      <div class="d-flex">      
        <div class="container-fluid" style="max-width: 2200px;">        
          <div class="row g-4">              
            <div class="container py-4">        
              <h1 class="text-center mb-4">Liste de vos Logements</h1>  
              <hr class="my-4 border-2 opacity-100" style="color: var(--green);">                    
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
                          <p class="small text-muted mb-0">                                        
                            Disponible : <?php echo ($row['disponible'] == 1) ? 'Oui' : 'Non'; ?>                                    
                          </p>
                          <p class="small text-muted">
                            <?php 
                              $status = $row['status'];
                              $color = '';

                              if ($status === 'Approved') {
                                $color = 'text-success'; // vert
                              } elseif ($status === 'Waiting') {
                                $color = 'text-warning'; // orange
                              } else {
                                $color = 'text-muted'; // gris par défaut
                              }
                            ?>
                            Status : <span class="<?php echo $color; ?> fw-bold"><?php echo htmlspecialchars($status); ?></span>
                          </p>

                          
                              <!-- Formulaire HTML avec le bouton visible seulement pour l'admin -->
                              <form method="post">
                                  <input type="hidden" name="logement_id" value=<?php echo $row['ID']; ?>> <!-- Remplace 1 par l'ID du logement -->
                                  <button type="submit" class="btn-unapproved" name="delete">Supprimer</button>
                              </form>                                                        
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

    </main>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    const toggle = document.getElementById("menu-toggle");
const sidebar = document.getElementById("sidebar");

toggle.addEventListener("click", () => {
  sidebar.classList.toggle("active");
});
  </script>
</body>

</html>
