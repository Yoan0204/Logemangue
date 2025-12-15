<?php
require_once 'db2withoutlogin.php';
require 'controllers/LogementController.php';
require 'models/LogementModel.php';


// Initialiser le modèle et le contrôleur
$model = new LogementModel($conn, $pdo);
$controller = new LogementController($model, __DIR__ . '/views/');

// Déterminer quelle vue afficher
$view = isset($_GET['view']) ? $_GET['view'] : 'recherche';
$message = $controller->handleDelete();

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
    $logements = $controller->getUserLogements($userId);
    $isAdmin = isset($user['is_admin']) ? $user['is_admin'] : 0;
} else {
    // Vue de recherche avec filtres
    $logements = $controller->getFilteredSearchLogements();
    $isAdmin = isset($user['is_admin']) ? $user['is_admin'] : 0;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $view === 'mesannonces' ? 'Mes Annonces' : 'Recherche de logements'; ?> - Logemangue</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>

<body>
    <header class="topbar">
        <a href="index.php" class="topbar-logo">
            <img src="../png/topbar.png" onresize="3000" alt="Logo" />
        </a>

        <nav class="topbar-nav">
            <a class="nav-link " href="index.php">Accueil</a>
            <a class="nav-link <?php echo $view === 'recherche' ? 'active-link' : ''; ?>" href="logements.php?view=recherche">Recherche</a>
            <?php if (!$isEtudiant): ?>
            <a class="nav-link" href="publish.php">Publier une annonce</a>
            <?php endif; ?>
            <a class="nav-link <?php echo $view === 'mesannonces' ? 'active-link' : ''; ?>" href="logements.php?view=mesannonces">Mes annonces</a>
            <a class="nav-link" href="listemessagerie.php">Ma messagerie</a>
            <?php if ($isAdmin): ?> 
                <a class="nav-link" href="admin.php">Admin ⚙️</a>
            <?php endif; ?>
            <a class="nav-link" href="profil.php">Mon profil</a>
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
                        <?php include 'views/' . $view . '.php'; ?>
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
<footer class="text-center py-3">
<?php include 'footer.php'; ?>
</footer>
</html>