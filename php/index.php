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

  <?php     
  require 'db2withoutlogin.php';
  ?>
  
   <header class="topbar">
    <a href="index.php" class="topbar-logo">
      <img src="../png/topbar.png" onresize="3000" alt="Logo" />
    </a>

    <nav class="topbar-nav">
      <a class="nav-link active-link" href="index.php">Accueil</a>
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

        <form action="logements.php" method="GET" class="d-flex justify-content-center align-items-center mb-5">
          <input
            type="text"
            name="search"
            class="form-control search-input"
            placeholder="Ville, quartier, type de logement..."
          />
          <button type="submit" class="btn-search">Rechercher →</button>
        </form>


        <!--
<div class="d-flex justify-content-center gap-5 stats">
<div><strong>15+</strong><br>Logements disponibles</div>
<div><strong>100%</strong><br>Annonces vérifiées</div>
<div><strong>24/7</strong><br>Support étudiant</div>
</div>-->
        <div class="container carousel-container" style="margin-bottom: 0px">
          <div
            id="carouselLogements"
            class="carousel slide"
            data-bs-ride="carousel"
          >
            <!-- Indicateurs -->
            <div class="carousel-indicators">
              <button
                type="button"
                data-bs-target="#carouselLogements"
                data-bs-slide-to="0"
                class="active"
              ></button>
              <button
                type="button"
                data-bs-target="#carouselLogements"
                data-bs-slide-to="1"
              ></button>
              <button
                type="button"
                data-bs-target="#carouselLogements"
                data-bs-slide-to="2"
              ></button>
              <button
                type="button"
                data-bs-target="#carouselLogements"
                data-bs-slide-to="3"
              ></button>
            </div>

            <!-- Images du carousel -->
            <div class="carousel-inner">
              <div class="carousel-item active">
                <img
                  src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=1000&h=500&fit=crop"
                  class="d-block w-100"
                  alt="Appartement moderne"
                />
                <div class="carousel-caption">
                  <h5 class="property-title">
                    Appartement Moderne - Centre Ville
                  </h5>
                  <p class="property-info">
                    3 chambres • 2 salles de bain • 95m² • 1 200€/mois
                  </p>
                </div>
              </div>

              <div class="carousel-item">
                <img
                  src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=1000&h=500&fit=crop"
                  class="d-block w-100"
                  alt="Maison avec jardin"
                />
                <div class="carousel-caption">
                  <h5 class="property-title">Maison avec Jardin - Banlieue</h5>
                  <p class="property-info">
                    4 chambres • 3 salles de bain • 150m² • 1 800€/mois
                  </p>
                </div>
              </div>

              <div class="carousel-item">
                <img
                  src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=1000&h=500&fit=crop"
                  class="d-block w-100"
                  alt="Studio lumineux"
                />
                <div class="carousel-caption">
                  <h5 class="property-title">
                    Studio Lumineux - Quartier Résidentiel
                  </h5>
                  <p class="property-info">
                    1 chambre • 1 salle de bain • 35m² • 650€/mois
                  </p>
                </div>
              </div>

              <div class="carousel-item">
                <img
                  src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=900&h=500&fit=crop"
                  class="d-block w-100"
                  alt="Loft contemporain"
                />
                <div class="carousel-caption">
                  <h5 class="property-title">
                    Loft Contemporain - Zone Artistique
                  </h5>
                  <p class="property-info">
                    2 chambres • 2 salles de bain • 110m² • 1 500€/mois
                  </p>
                </div>
              </div>
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

      <footer class="text-center py-3">
        <?php include 'footer.php'; ?>
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
  </body>
</html>
