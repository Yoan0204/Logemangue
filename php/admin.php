<?php
require "db2.php";

if (!isset($user["is_admin"]) && $user["is_admin"] != 1) {
    header("Location: index.php");
    exit();
}

if (isset($_POST["approve"])) {
    $id = $_POST["logement_id"];

    $sql =
        "UPDATE logement SET status = 'Approved' WHERE id = :id AND status = 'Waiting'";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();

    echo "Le logement a été approuvé !";
}


// Requête pour récupérer tous les logements
$sql = "SELECT * FROM logement WHERE status='Waiting'";
$result = $conn->query($sql);

// 1. Compter les nouveaux utilisateurs des 7 derniers jours
$stmt = $pdo->prepare("
    SELECT COUNT(*) as count 
    FROM users 
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
");
$stmt->execute();
$newUsers = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

// 2. Compter le nombre total de logements
$stmt = $pdo->prepare("SELECT COUNT(*) as count FROM logement");
$stmt->execute();
$totalLogements = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

// 3. Compter les utilisateurs par type
$stmt = $pdo->prepare("
    SELECT 
        type_utilisateur,
        COUNT(*) as count 
    FROM users 
    GROUP BY type_utilisateur
");
$stmt->execute();
$userTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Organiser les données par type
$proprietaires = 0;
$etudiants = 0;
$totalUsers = 0;

foreach ($userTypes as $type) {
    $count = (int)$type['count'];
    $totalUsers += $count;
    
    if (strtolower($type['type_utilisateur']) === 'proprietaire') {
        $proprietaires = $count;
    } elseif (strtolower($type['type_utilisateur']) === 'etudiant') {
        $etudiants = $count;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recherche de logements - Logemangue</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="../css/css.css">
</head>
<style>

    /* --- SIDEBAR --- */
.sidebar {
  width: 230px;
  height: 100vh;
  min-width: 230px;
  background-color: #fff;
  border-right: 1px solid #eee;
  overflow-y: auto;
  scrollbar-width: none;
  -ms-overflow-style: none;
  padding: 2rem 1rem;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  text-align: center;
  position: fixed;
  left: 0;
  top: 0;
  box-shadow: 4px 0 12px rgba(0, 0, 0, 0.03);
  z-index: 999;
}

.sidebar::-webkit-scrollbar {
  display: none;
}

/* Hamburger (mobile) */
.hamburger {
  display: none;
  font-size: 2rem;
  position: fixed;
  top: 15px;
  left: 15px;
  z-index: 1100;
  background: none;
  border: none;
  cursor: pointer;
  color: var(--green);
}

.flex-grow-1 {
  margin-left: 230px;
}


/* Navigation links */
.sidebar .nav-link {
  color: #000;
  font-weight: 500;
  text-decoration: none;
  margin-bottom: 0.8rem;
  padding: 1rem;
  border-radius: 8px;
  transition: all 0.3s ease;
  position: relative;
}

.sidebar .nav-link::before {
  content: "";
  position: absolute;
  inset: 0;
  background: linear-gradient(90deg, var(--yellow), var(--orange));
  opacity: 0;
  border-radius: 8px;
  transition: opacity 0.3s ease;
  z-index: -1;
}

.sidebar .nav-link:hover::before {
  opacity: 0.15;
}

.sidebar .nav-link:hover {
  color: var(--orange);
  transform: translateX(4px);
}

/* Separator lines */
.sidebar hr {
  width: 60%;
  border: 1px solid var(--green);
  margin: 1rem 0;
  align-self: center;
}

/* Logo */
.sidebar-logo {
  display: block;
  width: 150px;
  height: auto;
  margin: -25px auto 20px;
  object-fit: contain;
  cursor: pointer;
  transition: transform 0.2s;
}

.sidebar-logo:hover {
  transform: scale(1.05);
}

/* Active link */
.sidebar .active-link,
.sidebar .active-profile {
  background: linear-gradient(90deg, var(--yellow), var(--orange));
  color: black !important;
  font-weight: 600;
  border-radius: 8px;
  box-shadow: var(--shadow);
}

.sidebar .active-link:hover::before,
.sidebar .active-profile:hover::before {
  opacity: 0;
}

          .dashboard-container {
            padding: 30px;
        }
        
        .header {
            background: linear-gradient(135deg, #f97720ff 0%, #ffeb13ff 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;  
            box-shadow: 0 10px 10px rgba(0,0,0,0.1);
        }
        
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            background: linear-gradient(135deg, #f97720ff 0%, #ffeb13ff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .stat-label {
            color: #666;
            font-size: 1rem;
            margin-top: 10px;
        }
        
        .chart-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .icon-box {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            background: linear-gradient(135deg, #f97720ff 0%, #ffeb13ff 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            margin-bottom: 15px;
        }
</style>
<body>
<button id="menu-toggle" class="hamburger">☰</button>

<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
      <div>
        <a href="index.php">
          <img class="sidebar-logo" src="Aberent.png" alt="Logo">
        </a>
        <nav class="nav flex-column">
          <a class="nav-link" href="index.php">Accueil</a>
          <a class="nav-link" href="recherche.php">Recherche</a>

          <hr>
          <?php if (
              isset($user["type_utilisateur"]) &&
                  $user["type_utilisateur"] == "Proprietaire" or
              $user["type_utilisateur"] == "Organisme"
          ): ?>
            <li><a class="nav-link" href="publish.php">Publier une annonce</a></li>
          <?php endif; ?>  
          <?php if (
              isset($user["type_utilisateur"]) &&
                  $user["type_utilisateur"] == "Proprietaire" or
              $user["type_utilisateur"] == "Organisme"
          ): ?>
            <li><a class="nav-link" href="mesannonces.php">Mes annonces</a></li>
          <?php endif; ?>  
          <a class="nav-link" href="#">Ma messagerie</a>

          <hr>
          <a class="nav-link" href="#">FAQ</a>
          <a class="nav-link" href="#">Contact</a>

          <hr>
          <?php if (isset($user["is_admin"]) && $user["is_admin"] == 1): ?>
            <li><a class="nav-link active-link " href="admin.php">Admin ⚙️</a></li>
          <?php endif; ?> 
          <a class="nav-link " href="profil.php">Mon profil</a>
          <a class="nav-link" href="login.html">Connexion</a>
        </nav>
      </div>
    </div>
  
    <!-- CONTENU PRINCIPAL -->
    <main class="flex-grow-1 p-2">
    <div class="dashboard-container">
        <!-- Header -->
        <div class="header">
            <div>
                <h1>Dashboard Administrateur</h1>
                <p class="mb-0">Vue d'ensemble des statistiques de la plateforme Logemangue</p>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="row">
            <!-- Nouveaux utilisateurs -->
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="icon-box">
                        👤
                    </div>
                    <div class="stat-number" id="newUsers">0</div>
                    <div class="stat-label">Nouveaux utilisateurs (7 derniers jours)</div>
                </div>
            </div>

            <!-- Total logements -->
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="icon-box">
                        🏠
                    </div>
                    <div class="stat-number" id="totalLogements">0</div>
                    <div class="stat-label">Logements disponibles</div>
                </div>
            </div>

            <!-- Total utilisateurs -->
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="icon-box">
                        👥
                    </div>
                    <div class="stat-number" id="totalUsers">0</div>
                    <div class="stat-label">Total utilisateurs</div>
                </div>
            </div>
        </div>

        <!-- Graphique -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="chart-card">
                    <h4 class="mb-4"><i class="fas fa-chart-pie me-2"></i>Répartition des types d'utilisateurs</h4>
                    <canvas id="userTypeChart"></canvas>
                </div>
            </div>
        </div>
    </div>
      
      <!-- LOGEMENTS -->    
      <div class="d-flex">      
        <div class="container-fluid" style="max-width: 2500px;">        
          <div class="row g-4">              
            <div class="container py-4">        
              <h1 class="text-center mb-4">Liste des Logements à approuver</h1>        
              <div class="row justify-content-center">            
                <?php // Afficher les résultats
                if ($result->num_rows > 0) {
                    while (
                        $row = $result->fetch_assoc()
                    ) { ?>                    
                  <div class="col-md-4">                        
                    <a href="logement.php?id=<?php echo $row[
                        "ID"
                    ]; ?>" class="logement-link">                            
                      <div class="logement-card">                                
                        <img src="test.webp" alt="<?php echo $row[
                            "titre"
                        ]; ?>">                                
                        <div class="info">                                    
                          <h6 class="fw-bold mb-1"><?php echo $row[
                              "titre"
                          ]; ?></h6>                                    
                          <p class="text-muted mb-0"><?php echo $row[
                              "loyer"
                          ]; ?> € / mois</p>                                    
                          <p class="small text-muted">                                        
                            Disponible : <?php echo $row["disponible"] == 1
                                ? "Oui"
                                : "Non"; ?>                                    
                          </p>     
                        <form method="post">
                          <input type="hidden" name="logement_id" value=<?php echo $row[
                              "ID"
                          ]; ?>> <!-- Remplace 1 par l'ID du logement -->
                          <button type="submit" class="btn-approved" name="approve">Approuver</button>
                        </form>                                                      
                        </div>                           
                      </div>                        
                    </a>                    
                  </div>                    
                  <?php }
                } else {
                    echo "<div class='col-12'><p class='text-center'>Aucun logement trouvé.</p></div>";
                } ?>        
          </div>    
        </div>        
      </div>      
    </div>
    
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
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
      <script>
        // Données PHP passées à JavaScript
        const statsData = {
            newUsers: <?php echo $newUsers; ?>,
            totalLogements: <?php echo $totalLogements; ?>,
            totalUsers: <?php echo $totalUsers; ?>,
            proprietaires: <?php echo $proprietaires; ?>,
            etudiants: <?php echo $etudiants; ?>
        };

        // Animation des compteurs
        function animateCounter(elementId, target) {
            const element = document.getElementById(elementId);
            const duration = 1000;
            const steps = 60;
            const increment = target / steps;
            let current = 0;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    element.textContent = target;
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(current);
                }
            }, duration / steps);
        }

        // Affichage des statistiques avec animation
        window.addEventListener('DOMContentLoaded', function() {
            console.log('Données chargées:', statsData);
            
            animateCounter('newUsers', statsData.newUsers);
            animateCounter('totalLogements', statsData.totalLogements);
            animateCounter('totalUsers', statsData.totalUsers);
            
            // Attendre un peu avant de créer le graphique pour être sûr que tout est chargé
            setTimeout(() => {
                createChart(statsData.proprietaires, statsData.etudiants);
            }, 10);
        });

        // Création du graphique
        function createChart(proprietaires, etudiants) {
            const ctx = document.getElementById('userTypeChart');
            
            if (!ctx) {
                console.error('Canvas non trouvé');
                return;
            }
            
            // Vérifier que nous avons des données
            if (proprietaires === 0 && etudiants === 0) {
                ctx.parentElement.innerHTML = '<p class="text-center text-muted">Aucune donnée à afficher</p>';
                return;
            }
            
            console.log('Création du graphique avec:', proprietaires, 'propriétaires et', etudiants, 'étudiants');
            
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Propriétaires', 'Étudiants'],
                    datasets: [{
                        data: [proprietaires, etudiants],
                        backgroundColor: [
                            '#f97720ff',
                            '#f1d130ff'
                        ],
                        borderColor: [
                            '#fff',
                            '#fff'
                        ],
                        borderWidth: 2,
                        hoverOffset: 15
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    aspectRatio: 3,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                font: {
                                    size: 14
                                },
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>
</body>

</html>