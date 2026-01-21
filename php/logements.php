<?php
require_once 'db2withoutlogin.php';
require '../MVC/Controller/LogementController.php';
require '../MVC/Model/LogementModel.php';


// Initialiser le modèle et le contrôleur
$model = new LogementModel($conn, $pdo);
$controller = new LogementController($model, __DIR__ . '/views/');

// Déterminer quelle vue afficher
$view = isset($_GET['view']) ? $_GET['view'] : 'recherche';
$message = $controller->handleDelete();
$message = $controller->handleTotalDelete();

// Valider la vue demandée
$allowed_views = ['recherche', 'mesannonces'];
if (!in_array($view, $allowed_views)) {
    $view = 'recherche';
}

// Récupérer les logements selon la vue
if ($view === 'mesannonces') {
    // Vérifier que l'utilisateur est connecté
    if (!isset($userId)) {
        header('Location: login.html');
        exit;
    }
    // Pagination for "mes annonces"
    $searchResult = $controller->getUserLogementsPaginated($userId);
    $logements = $searchResult['logements'];
    $limit = $searchResult['limit'];
    $offset = $searchResult['offset'];
    $total = $searchResult['total'];
    $isAdmin = isset($user['is_admin']) ? $user['is_admin'] : 0;
} else {
    // Vue de recherche avec filtres + pagination
    $searchResult = $controller->getFilteredSearchLogements();
    $logements = $searchResult['logements'];
    $limit = $searchResult['limit'];
    $offset = $searchResult['offset'];
    $total = $searchResult['total'];
    $isAdmin = isset($user['is_admin']) ? $user['is_admin'] : 0;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="icon" type="image/x-icon" href="../png/icon.png" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $view === 'mesannonces' ? 'Mes Annonces' : 'Recherche de logements'; ?> - Logemangue</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>

<body>
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
            <a class="nav-link <?php echo $view === 'recherche' ? 'active-link' : ''; ?>" href="logements?view=recherche">Recherche</a>
            <?php if (!$isEtudiant): ?>
                <a class="nav-link" href="publish">Publier une annonce</a>
            <?php endif; ?>
            <?php if (!$isEtudiant): ?>
                <a class="nav-link <?php echo $view === 'mesannonces' ? 'active-link' : ''; ?>" href="logements?view=mesannonces">Mes annonces</a>               
            <?php endif; ?>
            <?php if ($isEtudiant): ?>
                <a class="nav-link" href="candidatures">Mes candidatures</a>        
            <?php endif; ?>            
                  <a class="nav-link" href="listemessagerie">Ma messagerie</a>
            <?php if ($isAdmin): ?> 
                <a class="nav-link" href="admin">Admin ⚙️</a>
            <?php endif; ?>
            <a class="nav-link" href="profil">Mon profil</a>
        </nav>
    </header>

    <body>
        <!-- CONTENU PRINCIPAL -->
        <main class="flex-grow-1 p-4">
            <!-- Afficher le message de succès si présent -->
            <?php if ($message): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- LOGEMENTS -->
            <div class="d-flex">
                <div class="container-fluid" style="max-width: 2200px;">
                    <div class="row g-4">
                        <!-- Inclure la vue appropriée -->
                        <?php include  $view . '.php'; ?>
                    </div>
                </div>
            </div>
        </main>
    </body>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const toggle = document.getElementById("menu-toggle");
        const sidebar = document.getElementById("sidebar");

        if (toggle && sidebar) {
            toggle.addEventListener("click", () => {
                sidebar.classList.toggle("active");
            });
        }
    </script>
</body>
<footer class="text-center">
<?php include 'footer.php'; ?>
</footer>
</html>