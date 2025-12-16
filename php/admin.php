<?php
require "db2.php";

if (!isset($user["is_admin"]) && $user["is_admin"] != 1) {
    header("Location: index.php");
    exit();
}

// Traitement de l'approbation d'un logement
if (isset($_POST["approve"])) {
    $id = (int)$_POST["logement_id"];

    $sql = "UPDATE logement SET status = 'Approved' WHERE id = :id AND status = 'Waiting'";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();

    // Redirection pour éviter la resoumission du formulaire et conserver la recherche/pagination
    $redirect = 'admin.php';
    $params = [];
    if (!empty($_GET['q'])) {
        $params['q'] = $_GET['q'];
    }
    if (!empty($_GET['page'])) {
        $params['page'] = (int)$_GET['page'];
    }
    // Indiquer le succès pour pouvoir afficher un message après redirection
    $params['message'] = 'approved';
    if (!empty($params)) {
        $redirect .= '?' . http_build_query($params);
    }
    header('Location: ' . $redirect);
    exit();
}


// Recherche et pagination pour les logements à approuver
$q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = 6; // Nombre de logements par page
$offset = ($page - 1) * $perPage;

// Préparer la clause de filtre
$searchClause = '';
$params = [];
if ($q !== '') {
    $searchClause = " AND (l.titre LIKE :q OR l.description LIKE :q OR l.adresse LIKE :q)";
    $params[':q'] = "%$q%";
}

// Compter le nombre total pour la pagination
$countSql = "SELECT COUNT(*) as total FROM logement l WHERE l.status = 'Waiting'" . $searchClause;
$countStmt = $pdo->prepare($countSql);
foreach ($params as $k => $v) {
    $countStmt->bindValue($k, $v, PDO::PARAM_STR);
}
$countStmt->execute();
$totalRows = (int)$countStmt->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = (int)ceil($totalRows / $perPage);

// Récupérer les logements de la page courante
$sql = "SELECT l.*,
        (SELECT url_photo
         FROM photo
         WHERE photo.id_logement = l.ID
         ORDER BY id_photo ASC
         LIMIT 1) AS photo_url
        FROM logement l
        WHERE l.status = 'Waiting'" . $searchClause . " ORDER BY l.ID DESC LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
foreach ($params as $k => $v) {
    $stmt->bindValue($k, $v, PDO::PARAM_STR);
}
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$logements = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
$organismes = 0;
$etudiants = 0;
$totalUsers = 0;

foreach ($userTypes as $type) {
    $count = (int)$type['count'];
    $totalUsers += $count;
    
    if (strtolower($type['type_utilisateur']) === 'proprietaire') {
        $proprietaires = $count;
    } elseif (strtolower($type['type_utilisateur']) === 'etudiant') {
        $etudiants = $count;
    } elseif (strtolower($type['type_utilisateur']) === 'organisme') {
        $organismes = $count;
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
  <link rel="stylesheet" type="text/css" href="../css/admin.css">
  <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body>
<button id="menu-toggle" class="hamburger">☰</button>

<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
      <div>
        <a href="index.php">
          <img class="sidebar-logo" src="../png/Aberent.png" alt="Logo">
        </a>
        <nav class="nav flex-column">
          <a class="nav-link" href="index.php">Accueil</a>
          <a class="nav-link" href="logements.php">Recherche</a>

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
            <li><a class="nav-link" href="logements.php?view=mesannonces">Mes annonces</a></li>
          <?php endif; ?>  
          <a class="nav-link" href="listemessagerie.php">Ma messagerie</a>

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
                                    <input class="form-control me-2" type="search" name="q" placeholder="Recherche (titre, adresse...)" aria-label="Search" value="<?php echo htmlspecialchars($q); ?>">
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
                                    <div class="col-md-4 mb-4">                        
                                        <div class="logement-card">                                
                                            <a href="logement.php?id=<?php echo (int)$row['ID']; ?>" class="logement-link">
                                                <img src="<?php echo htmlspecialchars($row['photo_url'] ?: 'placeholder.jpg'); ?>" alt="<?php echo htmlspecialchars($row['titre']); ?>">
                                            </a>
                                            <div class="info">                                    
                                                <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($row['titre']); ?></h6>                                    
                                                <p class="text-muted mb-0"><?php echo htmlspecialchars($row['loyer']); ?> € / mois</p>                                    
                                                <p class="small text-muted">Disponible : <?php echo $row['disponible'] == 1 ? 'Oui' : 'Non'; ?></p>
                                                <div class="mt-2">
                                                    <form method="post" class="d-inline">
                                                        <input type="hidden" name="logement_id" value="<?php echo (int)$row['ID']; ?>">
                                                        <button type="submit" class="btn-approved btn btn-sm btn-success" name="approve">Approuver</button>
                                                    </form>
                                                    <a href="logement.php?id=<?php echo (int)$row['ID']; ?>" class="btn btn-sm btn-outline-secondary ms-2">Voir</a>
                                                </div>
                                            </div>                           
                                        </div>                        
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


