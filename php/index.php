<?php
if (isset($_GET["publish"]) && $_GET["publish"] === "success") {?>
            <div style="margin: 20px; margin-top: 20px;" class="alert alert-success alert-dismissible fade show" role="alert">
                    Le logement a été publié avec succès !                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
<?php
};

if (isset($_GET["registered"]) && $_GET["registered"] === "1") {?>
            <div style="margin: 20px; margin-top: 20px;" class="alert alert-success alert-dismissible fade show" role="alert">
                    Inscription réussie ! Vous pouvez maintenant vous <a class="alert-link" href="login.html">connecter</a>.                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
<?php
};
// Simple router: use ?page=cgu or ?page=faq to view MVC pages
$page = isset($_GET["page"]) ? $_GET["page"] : "home";
if ($page === "cgu") {
    require_once __DIR__ . "/../MVC/Model/CGUmodel.php";
    require_once __DIR__ . "/../MVC/Controller/CGUcontroller.php";
    $model = new CGUModel();
    $controller = new CGUController($model);
    $controller->showCGU();
    exit();
}
if ($page === "faq") {
    require_once __DIR__ . "/../MVC/Model/FAQmodel.php";
    require_once __DIR__ . "/../MVC/Controller/FAQcontroller.php";
    $model = new FAQModel();
    $controller = new FAQController($model);
    $controller->showFAQ();
    exit();
}

if ($page === "login") {
    require_once __DIR__ . "/../MVC/View/Loginview.php";
    $view = new LoginView();
    $view->render();
    exit();
}

if ($page === "register") {
    require_once __DIR__ . "/../MVC/View/Loginview.php";
    $view = new RegisterView();
    $view->render();
    exit();
}

if ($page === "messagerie" || $page === "listemessagerie") {
    require_once __DIR__ . "/db.php";
    $userId = $_SESSION["user_id"] ?? 1;
    require_once __DIR__ . "/../MVC/Model/Messageriemodel.php";
    require_once __DIR__ . "/../MVC/Controller/Messageriecontroller.php";
    $model = new MessagerieModel($pdo);
    $controller = new MessagerieController($model);
    $controller->showMessagerie($userId);
    exit();
}

if ($page === "candidatures") {
    require_once __DIR__ . "/db.php";
    $userId = $_SESSION["user_id"] ?? 1;
    require_once __DIR__ . "/../MVC/Model/CandidaturesModel.php";
    require_once __DIR__ . "/../MVC/Controller/CandidaturesController.php";
    require_once __DIR__ . "/../MVC/View/Candidaturesview.php";
    $model = new CandidaturesModel($pdo);
    $controller = new CandidaturesController($model);
    $candidatures = $controller->showCandidatures($userId);
    $view = new CandidaturesView();
    $view->render($candidatures);
    exit();
}

if ($page === "admin") {
    require_once __DIR__ . "/db2.php";

  if (!isset($user["is_admin"]) || $user["is_admin"] != 1) {
      header("Location: index");
      exit();
  }

  require_once __DIR__ . "/../MVC/Controller/Admincontroller.php";

  $controller = new Admincontroller($pdo);
  $controller->index();
  exit;
}

if ($page === "profil") {
    require_once __DIR__ . "/db.php";
    $userId = $_SESSION["user_id"] ?? 1;
    require_once __DIR__ . "/../MVC/Model/Profilmodel.php";
    require_once __DIR__ . "/../MVC/Controller/Profilcontroller.php";
    $model = new Profilmodel($pdo);
    $controller = new Profilcontroller($model);
    $profile = $controller->viewProfile($userId);
    require_once __DIR__ . "/../MVC/View/Profilview.php";
    $view = new Profilview();
    $view->renderProfile($profile);
    exit();
}

// default: render home HTML below
?>
<!doctype html>
<html lang="fr">
  <!-- Formated by Astral v1 -->
  <!-- Test de Merge -->
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/x-icon" href="../png/icon.png" />
    <title>Accueil - Logemangue</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" type="text/css" href="../css/style.css" />
  </head>

    <?php require "db2withoutlogin.php"; ?>

    <?php
      // Charger logements pour le carrousel — ne garder que ceux qui ont une photo
      require_once '../MVC/Model/LogementModel.php';
      $logementModel = new LogementModel($conn, $pdo);
      // On récupère plus de résultats et on filtrera côté PHP pour garantir d'avoir 4 logements AVEC photo
      $carouselResult = $logementModel->getFilteredLogements([], 20, 0);
      $carouselLogements = [];
      if ($carouselResult && $carouselResult->num_rows > 0) {
        while ($row = $carouselResult->fetch_assoc()) {
          if (!empty(trim((string)($row['photo_url'] ?? '')))) {
            $carouselLogements[] = $row;
          }
          if (count($carouselLogements) >= 4) break;
        }
      }
    ?>

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
      <a class="nav-link active-link" href="index">Accueil</a>
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

      <a class="nav-link " href="profil">Mon profil</a>   
    </nav>
  </header>

  <body>
    <!-- Contenu principal -->
    <div>
      <section class="hero text-center text-white">
        <h1 class="display-5 fw-bold mb-3">
          Trouvez votre logement étudiant idéal
        </h1>
        <p class="lead mb-5">
          La plateforme qui connecte étudiants et propriétaires pour trouver le
          logement parfait
        </p>

        <form action="logements" method="GET" class="d-flex justify-content-center align-items-center mb-5">
          <input
            type="text"
            name="search"
            class=" search-input"
            placeholder="Ville, quartier, type de logement..."
          />
          <button type="submit" class="btn-search">Rechercher →</button>
        </form>

        <div class="container carousel-container" style="margin-bottom: 0px">
          <div
            id="carouselLogements"
            class="carousel slide"
            data-bs-ride="carousel"
            data-bs-interval="3500"  
            data-bs-pause="hover"    
          >
            <!-- Indicateurs -->
            <div class="carousel-indicators">
              <?php if (!empty($carouselLogements)): ?>
                <?php foreach ($carouselLogements as $i => $lg): ?>
                  <button type="button" data-bs-target="#carouselLogements" data-bs-slide-to="<?php echo $i; ?>" <?php echo $i === 0 ? 'class="active"' : ''; ?>></button>
                <?php endforeach; ?>
              <?php else: ?>
                <!-- Fallback: 4 indicateurs statiques -->
                <button type="button" data-bs-target="#carouselLogements" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#carouselLogements" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#carouselLogements" data-bs-slide-to="2"></button>
                <button type="button" data-bs-target="#carouselLogements" data-bs-slide-to="3"></button>
              <?php endif; ?>
            </div>

            <!-- Images du carousel -->
            <div class="carousel-inner">
              <?php if (!empty($carouselLogements)): ?>
                <?php foreach ($carouselLogements as $i => $lg): ?>
                  <div class="carousel-item <?php echo $i === 0 ? 'active' : ''; ?>">
                    <a href="logement?id=<?php echo intval($lg['ID']); ?>" title="<?php echo htmlspecialchars($lg['titre'] ?: 'Voir le logement'); ?>" style="display:block; color:inherit; text-decoration:none; cursor:pointer;">
                      <img src="<?php echo htmlspecialchars($lg['photo_url'] ?: 'https://via.placeholder.com/1000x500?text=No+Photo'); ?>" class="d-block w-100" alt="<?php echo htmlspecialchars($lg['titre'] ?: 'Logement'); ?>" />
                      <div class="carousel-caption">
                        <h5 class="property-title"><?php echo htmlspecialchars($lg['titre'] ?: 'Titre'); ?></h5>
                        <p class="property-info"><?php echo htmlspecialchars($lg['ville'] ?? ''); ?> • <?php echo htmlspecialchars($lg['surface'] ?? ''); ?>m² • <?php echo htmlspecialchars($lg['loyer'] ?? ''); ?>€/mois</p>
                      </div>
                    </a>
                  </div>
                <?php endforeach; ?>
              <?php else: ?>
                <!-- Fallback static items if DB empty -->
                <div class="carousel-item active">
                  <a href="logements" style="display:block; color:inherit; text-decoration:none; cursor:pointer;">
                    <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=1000&h=500&fit=crop" class="d-block w-100" alt="Appartement moderne" />
                    <div class="carousel-caption">
                      <h5 class="property-title">Appartement Moderne - Centre Ville</h5>
                      <p class="property-info">3 chambres • 2 salles de bain • 95m² • 1 200€/mois</p>
                    </div>
                  </a>
                </div>
                <div class="carousel-item">
                  <a href="logements" style="display:block; color:inherit; text-decoration:none; cursor:pointer;">
                    <img src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=1000&h=500&fit=crop" class="d-block w-100" alt="Maison avec jardin" />
                    <div class="carousel-caption">
                      <h5 class="property-title">Maison avec Jardin - Banlieue</h5>
                      <p class="property-info">4 chambres • 3 salles de bain • 150m² • 1 800€/mois</p>
                    </div>
                  </a>
                </div>
                <div class="carousel-item">
                  <a href="logements" style="display:block; color:inherit; text-decoration:none; cursor:pointer;">
                    <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=1000&h=500&fit=crop" class="d-block w-100" alt="Studio lumineux" />
                    <div class="carousel-caption">
                      <h5 class="property-title">Studio Lumineux - Quartier Résidentiel</h5>
                      <p class="property-info">1 chambre • 1 salle de bain • 35m² • 650€/mois</p>
                    </div>
                  </a>
                </div>
                <div class="carousel-item">
                  <a href="logements" style="display:block; color:inherit; text-decoration:none; cursor:pointer;">
                    <img src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=900&h=500&fit=crop" class="d-block w-100" alt="Loft contemporain" />
                    <div class="carousel-caption">
                      <h5 class="property-title">Loft Contemporain - Zone Artistique</h5>
                      <p class="property-info">2 chambres • 2 salles de bain • 110m² • 1 500€/mois</p>
                    </div>
                  </a>
                </div>
              <?php endif; ?>
            </div>

            <!-- Contrôles précédent/suivant -->
            <button
              class="carousel-control-prev"
              type="button"
              data-bs-target="#carouselLogements"
              data-bs-slide="prev"
            >
              <span
                class="carousel-control-prev-icon"
                aria-hidden="true"
              ></span>
              <span class="visually-hidden">Précédent</span>
            </button>
            <button
              class="carousel-control-next"
              type="button"
              data-bs-target="#carouselLogements"
              data-bs-slide="next"
            >
              <span
                class="carousel-control-next-icon"
                aria-hidden="true"
              ></span>
              <span class="visually-hidden">Suivant</span>
            </button>
          </div>
        </div>
      </section>

      <section class="features text-center">
        <h2 class="fw-bold mb-4">Pourquoi choisir Logemangue ?</h2>
        <p class="text-muted mb-5">
          Une plateforme moderne conçue pour faciliter votre recherche de
          logement
        </p>

        <div class="container">
          <div class="row g-4">
            <div class="col-md-4">
              <div class="p-4 feature-card">
                <div class="feature-icon">🔍</div>
                <h5 class="fw-bold">Recherche Facile</h5>
                <p>
                  Trouvez votre logement idéal en quelques clics sur Logemangue
                </p>
              </div>
            </div>

            <div class="col-md-4">
              <div class="p-4 feature-card">
                <div class="feature-icon">✔️</div>
                <h5 class="fw-bold">Logements Vérifiés</h5>
                <p>
                  Annonces vérifiées de particuliers et organismes par nos
                  modérateurs spécialisés.
                </p>
              </div>
            </div>

            <div class="col-md-4">
              <div class="p-4 feature-card">
                <div class="feature-icon">👥</div>
                <h5 class="fw-bold">Colocation</h5>
                <p>
                  Trouvez des colocataires en formant un groupe de candidatures.
                </p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Derniers Logements -->
      <section class="latest-logements text-center">
        <h2 class="fw-bold mb-4">Dernières annonces publiées</h2>
        <p class="text-muted mb-5">
          Découvrez les dernières annonces de logements disponibles sur
          Logemangue
        </p>
        <div class="container">
          <div class="row g-4" id="latest-logements-container">
            <?php
              $latestResult = $logementModel->getFilteredLogements([], 6, 0);
              if ($latestResult && $latestResult->num_rows > 0) {
                while ($row = $latestResult->fetch_assoc()) {
                  $photoUrl = !empty(trim((string)($row['photo_url'] ?? ''))) ? $row['photo_url'] : 'https://via.placeholder.com/400x300?text=No+Photo';
                  ?>
                  <div class="col-md-4">
                    <a href="logement?id=<?php echo intval($row['ID']); ?>" class="logement-link">
                      <div class="logement-card">
                        <img src="<?php echo htmlspecialchars($photoUrl); ?>" alt="<?php echo htmlspecialchars($row['titre'] ?? 'Logement'); ?>" />
                        <div class="info">
                          <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($row['titre'] ?? 'Titre'); ?></h6>
                          <p class="text-muted mb-0"><?php echo htmlspecialchars($row['loyer'] ?? 'Loyer'); ?> € / mois</p>
                          <p class="small text-muted mb-0">Disponible : <?php echo (isset($row['disponible']) && $row['disponible'] == 1) ? 'Oui' : 'Non'; ?></p>
                          <p class="small text-muted mb-0"><?php echo htmlspecialchars($row['surface'] ?? 'Surface'); ?> m² - <?php echo htmlspecialchars($row['TYPE'] ?? 'Type'); ?></p>
                        </div>
                      </div>
                    </a>
                  </div>
                  <?php
                }
              } else {
                echo '<p class="text-muted">Aucune annonce disponible pour le moment.</p>';
              }
            ?>
          </div>
        </div>
      </section>
      <!-- Avis des utilisateurs -->
      <section class="user-reviews text-center">
        <h2 class="fw-bold mb-4">Ce que disent nos utilisateurs :</h2>
        <p class="text-muted-white mb-5 ">
          Quelques retours d'amateurs de Logemangue !
        </p>
        <div class="container">
          <div class="row g-4">
            <div class="col-md-4">
              <div class="p-4 review-card">
                <p class="mb-3">
                  "Logemangue m'a aidé à trouver un appartement parfait près de
                  mon université en un rien de temps !"
                </p>
                <h6 class="fw-bold">Marie D.</h6>
                <p class="text-muted">Étudiante en droit</p>
              </div>
            </div>
            <div class="col-md-4">
              <div class="p-4 review-card">
                <p class="mb-3">
                  "En tant que propriétaire, j'apprécie la simplicité de
                  publier mes annonces sur Logemangue et de trouver des
                  locataires fiables."
                </p>
                <h6 class="fw-bold">Jean P.</h6>
                <p class="text-muted">Propriétaire</p>
              </div>
            </div>
            <div class="col-md-4">
              <div class="p-4 review-card">
                <p class="mb-3">
                  "La fonctionnalité de colocation m'a permis de trouver des
                  colocataires géniaux et de partager les coûts du logement."
                </p>
                <h6 class="fw-bold">Sophie L.</h6>
                <p class="text-muted">Étudiante en médecine</p>
              </div>
            </div>
          </div>
        </div>
      </section>


      <footer class="text-center py-3">
        <?php include "footer.php"; ?>
      </footer>
    </div>

    <script>
      const toggle = document.getElementById("menu-toggle");
      const sidebar = document.getElementById("sidebar");

      toggle.addEventListener("click", () => {
        sidebar.classList.toggle("active");
      });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/responsive.js"></script>
  </body>
</html>
