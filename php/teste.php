<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Accueil - Logemangue</title>
  <link rel="icon" type="image/x-icon" href='icon.png' onresize="2300">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="../css/css.css">
</head>

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
          <a class="nav-link active-link" href="index.php">Accueil</a>
          <a class="nav-link" href="recherche.php">Recherche</a>

          <hr>
            <li><a class="nav-link" href="publish.php">Publier une annonce</a></li>
            <li><a class="nav-link" href="mesannonces.php">Mes annonces</a></li>
          <a class="nav-link" href="#">Ma messagerie</a>

          <hr>
          <a class="nav-link" href="#">FAQ</a>
          <a class="nav-link" href="#">Contact</a>

          <hr>
          <li><a class="nav-link " href="admin.php">Admin ⚙️</a></li>
          <a class="nav-link" href="profil.php">Mon profil</a>
          <a class="nav-link" href="login.html">Connexion</a>
        </nav>
      </div>
    </div>  

    <!-- Contenu principal -->
    <div class="flex-grow-1">
      <section class="hero text-center text-white">
        <h1 class="display-5 fw-bold mb-3">Ceci est une page test </h1>
        <p class="lead mb-5">Cette page sert uniquement à tester le menu sidebar</p>
      </section>

      <section class="news-section p-5">
        <h2 class="mb-4">Actualités du monde</h2>
        <p class="lead">Découvrez les dernières actualités et événements importants qui façonnent notre monde. De la politique aux sciences, en passant par l'économie et la culture, restez informé des développements majeurs qui impactent nos sociétés. Nous vous apportons les informations essentielles pour comprendre les enjeux contemporains et anticiper les tendances futures.</p>
      </section>
    </div>
    

    </div>
  </div>
  <script>
    const toggle = document.getElementById("menu-toggle");
const sidebar = document.getElementById("sidebar");

toggle.addEventListener("click", () => {
  sidebar.classList.toggle("active");
});
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>