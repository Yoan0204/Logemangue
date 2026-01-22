<?php

class CGUView {
    public function renderCGU($cguContent) {
        ?>
        <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conditions Générales d'Utilisation</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/cgu.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">

</head>
<?php include "../php/db2withoutlogin.php"  ; ?>
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
      <a class="nav-link" href="index">Accueil</a>
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
    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <div class="hero-content text-center">
                <h1 style="font-size :2.6rem;" class="hero-title">Conditions Générales d'Utilisation</h1>
                <p class="hero-subtitle">Dernière mise à jour : Janvier 2026</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    
                    <!-- Section 1 -->
                    <div class="section-card">
                        <h2 class="section-title">
                            <span class="section-number">1</span>
                            Objet
                        </h2>
                        <p class="section-text">
                            Les présentes Conditions Générales d'Utilisation (CGU) régissent l'accès et l'utilisation de notre plateforme. En utilisant nos services, vous acceptez sans réserve les présentes conditions. Si vous n'acceptez pas ces termes, nous vous prions de ne pas utiliser notre plateforme.
                        </p>
                    </div>

                    <!-- Section 2 -->
                    <div class="section-card">
                        <h2 class="section-title">
                            <span class="section-number">2</span>
                            Inscription et Compte Utilisateur
                        </h2>
                        <p class="section-text">
                            Pour accéder à certaines fonctionnalités, vous devez créer un compte. Vous vous engagez à fournir des informations exactes et à maintenir la confidentialité de vos identifiants. Vous êtes responsable de toutes les activités effectuées sous votre compte.
                        </p>
                        <div class="highlight-box">
                            <strong>Important :</strong> Vous devez avoir au moins 18 ans pour créer un compte ou disposer de l'autorisation parentale.
                        </div>
                    </div>

                    <!-- Section 3 -->
                    <div class="section-card">
                        <h2 class="section-title">
                            <span class="section-number">3</span>
                            Utilisation des Services
                        </h2>
                        <p class="section-text">
                            Vous vous engagez à utiliser nos services de manière légale et conforme aux présentes CGU. Il est notamment interdit de :
                        </p>
                        <ul class="section-text mt-3">
                            <li>Violer les droits de propriété intellectuelle</li>
                            <li>Diffuser des contenus illicites ou offensants</li>
                            <li>Tenter d'accéder de manière non autorisée à nos systèmes</li>
                            <li>Utiliser les services à des fins commerciales sans autorisation</li>
                        </ul>
                    </div>

                    <!-- Section 4 -->
                    <div class="section-card">
                        <h2 class="section-title">
                            <span class="section-number">4</span>
                            Propriété Intellectuelle
                        </h2>
                        <p class="section-text">
                            Tous les contenus présents sur la plateforme (textes, images, logos, vidéos) sont protégés par les droits de propriété intellectuelle. Toute reproduction, représentation ou utilisation non autorisée est strictement interdite.
                        </p>
                    </div>

                    <!-- Section 5 -->
                    <div class="section-card">
                        <h2 class="section-title">
                            <span class="section-number">5</span>
                            Protection des Données
                        </h2>
                        <p class="section-text">
                            Nous collectons et traitons vos données personnelles conformément à notre Politique de Confidentialité et au Règlement Général sur la Protection des Données (RGPD). Vous disposez d'un droit d'accès, de rectification et de suppression de vos données.
                        </p>
                        <div class="highlight-box">
                            Vos données sont sécurisées et ne seront jamais vendues à des tiers.
                        </div>
                    </div>

                    <!-- Section 6 -->
                    <div class="section-card">
                        <h2 class="section-title">
                            <span class="section-number">6</span>
                            Responsabilité et Garanties
                        </h2>
                        <p class="section-text">
                            Nous nous efforçons de maintenir nos services accessibles et fonctionnels, mais ne pouvons garantir une disponibilité ininterrompue. Nous déclinons toute responsabilité en cas de dommages indirects résultant de l'utilisation de nos services.
                        </p>
                    </div>

                    <!-- Section 7 -->
                    <div class="section-card">
                        <h2 class="section-title">
                            <span class="section-number">7</span>
                            Modification des CGU
                        </h2>
                        <p class="section-text">
                            Nous nous réservons le droit de modifier les présentes CGU à tout moment. Les modifications entreront en vigueur dès leur publication sur la plateforme. Il est de votre responsabilité de consulter régulièrement ces conditions.
                        </p>
                    </div>

                    <!-- Section 8 -->
                    <div class="section-card">
                        <h2 class="section-title">
                            <span class="section-number">8</span>
                            Droit Applicable et Litiges
                        </h2>
                        <p class="section-text">
                            Les présentes CGU sont régies par le droit français. En cas de litige, et après tentative de résolution amiable, les tribunaux français seront seuls compétents.
                        </p>
                    </div>

                    <!-- Contact Button -->
                    <div class="text-center mt-5">
                        <button onclick="window.location.href='contact'" class="btn btn-primary-custom">Nous Contacter</button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->


    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>
    <footer>

          <?php include "footer.php"; ?>

    </footer>
</html>

        <?php
    }
}