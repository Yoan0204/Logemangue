<?php

class CandidatureView {
    public function render(array $candidatures) {
        
        ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ma Messagerie</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/listmessagerie.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    /* Conteneur arrondi */
    .rounded-table {
        background: linear-gradient(135deg, #fff4d6, #ffe1b3);
        border-radius: 18px;
        padding: 10px;
        box-shadow: 0 8px 25px rgba(255, 165, 0, 0.2);
    }

    /* Table */
    .candidature-table {
        background: transparent;
        border-radius: 16px;
        overflow: hidden;
    }

    /* En-tête */
    .candidature-table thead {
        background: linear-gradient(135deg, #ffb703, #fb8500);
        color: #fff;
    }

    .candidature-table th {
        padding: 15px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
    }

    /* Lignes */
    .candidature-table tbody tr {
        background-color: #ffffff;
        border-radius: 12px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .candidature-table tbody tr:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(255, 183, 3, 0.25);
    }

    /* Cellules */
    .candidature-table td {
        padding: 14px;
        vertical-align: middle;
    }

    /* Lien logement */
    .logement-link {
        color: #fb8500;
        font-weight: 500;
        text-decoration: none;
    }

    .logement-link:hover {
        text-decoration: underline;
    }

    /* Badge statut */
    .status-badge {
        background: linear-gradient(135deg, #ffb703, #fb8500);
        color: #fff;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
    }

    .status-badge.red {
        background: #dc3545;
    }
    .status-badge.green {
        background: #28a745;
    }

</style>
    
</head>
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
      <a class="nav-link" href="logements">Recherche</a>

      <?php if (!$isEtudiant): ?>
      <a class="nav-link" href="publish">Publier une annonce</a>
      <?php endif; ?>
      <?php if (!$isEtudiant): ?>
      <a class="nav-link" href="logements?view=mesannonces">Mes annonces</a>        
      <?php endif; ?>
      <?php if ($isEtudiant): ?>
      <a class="nav-link active-link" href="candidatures">Mes candidatures</a>        
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
        <div class="header-box mb-4 items-center">
            <h1 class="fw-bold">Vos candidatures</h1>
        </div>
<div class="container mt-4">
    <?php if (count($candidatures) === 0): ?>
        <p class="text-muted">Vous n'avez aucune candidature</p>
    <?php else: ?>
        <div class="table-responsive rounded-table">
            <table class="table table-borderless candidature-table">
                <thead>
                    <tr>
                        <th>Logement</th>
                        <th>Date de début</th>
                        <th>Date de fin</th>
                        <th>Statut</th>
                        <th>Montant</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($candidatures as $candidature) : ?>
                        <tr>
                            <td>
                                <strong>
                                    <?php echo htmlspecialchars(
                                        $candidature['adresse'] . ', ' .
                                        $candidature['ville'] . ' ' .
                                        $candidature['code_postal']
                                    ); ?>
                                </strong>
                                <br>
                                <a class="logement-link" href="../php/logement?id=<?php echo $candidature['logement_id']; ?>">
                                    Voir le logement
                                </a>
                           
</div>

</body>
      <footer class="text-center py-3">
        <?php include 'footer.php'; ?>
      </footer>
</html>

        <?php
    }
}