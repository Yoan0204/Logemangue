<?php

class AdminView {
    public function renderDashboard(array $data) {
        // Provide access to session/global user info used in templates
        $user = $GLOBALS['user'] ?? null;
        $userId = $GLOBALS['userId'] ?? null;

        // Make variables available as local variables for ease of template porting
        $q = $data['q'];
        $page = $data['page'];
        $totalPages = $data['totalPages'];
        $logements = $data['logements'];
        $newUsers = $data['newUsers'];
        $totalLogements = $data['totalLogements'];
        $totalUsers = $data['totalUsers'];
        $proprietaires = $data['proprietaires'];
        $etudiants = $data['etudiants'];
        $organismes = $data['organismes'];
        
        ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="icon" type="image/x-icon" href="../png/icon.png" />
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Logemangue</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="../css/style.css">
  <link rel="stylesheet" type="text/css" href="../css/admin.css">
</head>
<body>
<button id="menu-toggle" class="hamburger">☰</button>

<div class="d-flex">
        <!-- SIDEBAR -->
    <div class="sidebar" id="sidebar">
        <div>
            <a href="index">
                <img class="sidebar-logo" src="../png/Aberent.png" alt="Logo">
            </a>

            <nav class="nav flex-column">
                <a class="nav-link" href="index">Accueil</a>
                <a class="nav-link" href="logements">Recherche</a>

                <hr>

                <?php if (
                    isset($user["type_utilisateur"]) &&
                    ($user["type_utilisateur"] == "Proprietaire" || $user["type_utilisateur"] == "Organisme")
                ): ?>
                    <a class="nav-link" href="publish">Publier une annonce</a>
                    <a class="nav-link" href="logements?view=mesannonces">Mes annonces</a>
                <?php endif; ?>

                <a class="nav-link" href="listemessagerie">Ma messagerie</a>

                <hr>

                <a class="nav-link" href="index?page=faq">FAQ</a>
                <a class="nav-link" href="admin_contact">Contact</a>

                <hr>

                <a class="nav-link active-link" href="admin.php">Admin ⚙️</a>
                <a class="nav-link " href="admin_users.php">Gestion utilisateurs</a>
                <a class="nav-link" href="admin_faq.php">Gestion FAQ</a>

                <a class="nav-link" href="profil">Mon profil</a>
            </nav>
        </div>
    </div>
  
    <!-- CONTENU PRINCIPAL -->
    <main class="flex-grow-1 p-2">
    <div class="dashboard-container">
        <!-- Header -->
        <div class="header">
            <div>
                <h1>Dashboard</h1>
                <p class="mb-0">Vue d'ensemble des statistiques Logemangue</p>
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
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h1 class="text-center mb-0">Liste des Logements à approuver</h1>
                                <form method="get" class="d-flex" role="search">
                                    <input class="form-control me-2" style="width: 500px;" type="search" name="q" placeholder="Recherche (titre, adresse...)" aria-label="Search" value="<?php echo htmlspecialchars_decode($q); ?>">
                                    <button class="btn btn-outline-primary" type="submit">Rechercher</button>
                                </form>
                            </div>

                            <?php if (isset($_GET['message']) && $_GET['message'] === 'approved'): ?>
                                <div class="alert alert-success" role="alert">Le logement a été approuvé avec succès.</div>
                            <?php endif; ?>

                            <div class="row justify-content-center">            
                                <?php // Afficher les résultats
                                if (count($logements) > 0) {
                                        foreach ($logements as $row) { ?>                    
                                    <div class="col-md-4">                        
                    <a href="logement?id=<?php echo $row[
                        "ID"
                    ]; ?>" class="logement-link">                            
                      <div class="logement-card">                                
                        <img src="<?php echo $row['photo_url'] ?: 'placeholder.jpg'; ?>" 
     alt="<?php echo $row['titre']; ?>">                                
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

                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                        <nav aria-label="Pagination">
                            <ul class="pagination justify-content-center">
                                <?php
                                $baseParams = [];
                                if ($q !== '') {
                                        $baseParams['q'] = $q;
                                }
                                // Previous
                                $prevPage = max(1, $page - 1);
                                $prevDisabled = $page <= 1 ? ' disabled' : '';
                                $params = array_merge($baseParams, ['page' => $prevPage]);
                                ?>
                                <li class="page-item<?php echo $prevDisabled; ?>">
                                    <a class="page-link" href="<?php echo 'admin.php?' . http_build_query($params); ?>">&laquo; Préc</a>
                                </li>

                                <?php
                                $start = max(1, $page - 2);
                                $end = min($totalPages, $page + 2);
                                if ($start > 1) {
                                        $p = 1; $params = array_merge($baseParams, ['page' => $p]);
                                        echo '<li class="page-item"><a class="page-link" href="admin.php?' . http_build_query($params) . '">1</a></li>';
                                        if ($start > 2) echo '<li class="page-item disabled"><span class="page-link">&hellip;</span></li>';
                                }
                                for ($i = $start; $i <= $end; $i++) {
                                        $params = array_merge($baseParams, ['page' => $i]);
                                        $active = $i === $page ? ' active' : '';
                                        echo '<li class="page-item' . $active . '"><a class="page-link" href="admin.php?' . http_build_query($params) . '">' . $i . '</a></li>';
                                }
                                if ($end < $totalPages) {
                                        if ($end < $totalPages - 1) echo '<li class="page-item disabled"><span class="page-link">&hellip;</span></li>';
                                        $p = $totalPages; $params = array_merge($baseParams, ['page' => $p]);
                                        echo '<li class="page-item"><a class="page-link" href="admin.php?' . http_build_query($params) . '">' . $p . '</a></li>';
                                }

                                // Next
                                $nextPage = min($totalPages, $page + 1);
                                $nextDisabled = $page >= $totalPages ? ' disabled' : '';
                                $params = array_merge($baseParams, ['page' => $nextPage]);
                                ?>
                                <li class="page-item<?php echo $nextDisabled; ?>">
                                    <a class="page-link" href="<?php echo 'admin.php?' . http_build_query($params); ?>">Suiv &raquo;</a>
                                </li>
                            </ul>
                        </nav>
                    <?php endif; ?>
          </div>    
        </div>        
      </div>      
    </div>
    
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
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
            etudiants: <?php echo $etudiants; ?>,
            organismes: <?php echo $organismes; ?>
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
                createChart(statsData.proprietaires, statsData.etudiants, statsData.organismes);
            }, 10);
        });

        // Création du graphique
        function createChart(proprietaires, etudiants, organismes) {
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
                    labels: ['Propriétaires', 'Étudiants', 'Organismes'],
                    datasets: [{
                        data: [proprietaires, etudiants, organismes],
                        backgroundColor: [
                            '#f97720ff',
                            '#f1d130ff',
                            '#25721aff'
                        ],
                        borderColor: [
                            '#fff',
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


<?php
    }
}
