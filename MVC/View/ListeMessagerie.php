<?php require_once __DIR__ . '/../../php/db2.php';
$isAdmin = isset($user['is_admin']) ? $user['is_admin'] : 0;   
$isEtudiant = (isset($user['type_utilisateur']) && $user['type_utilisateur'] === 'Etudiant') ? true : false;  ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ma messagerie</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/listmessagerie.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
      <a class="nav-link active-link" href="listemessagerie">Ma messagerie</a>
        <?php if ($isAdmin): ?>
      <a class="nav-link" href="admin">Admin ⚙️</a>
        <?php endif; ?>
      <a class="nav-link " href="profil">Mon profil</a>
    </nav>
  </header>

    <div>
        <div class="header-box mb-4 items-center">
            <h1 class="fw-bold">Ma messagerie</h1>
        </div>

        <!-- Barre de recherche -->
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder=" | Rechercher (Contact, Bien, ...)" autocomplete="off">
        </div>

        <div class="separator-line"></div>

        <!-- Liste des conversations -->
        <div class="container" id="conversationsContainer">
        <?php foreach ($destinataires as $dest): 
            $initiale = strtoupper(substr($dest['nom'], 0, 1));
            $nonLus = (int)$dest['non_lus'];
        ?>
                            <a href="messagerie?dest=<?= $dest['id'] ?>" style="text-decoration: none; color: inherit;">
                <div class="conversation d-flex align-items-center justify-content-between <?php echo $nonLus > 0 ? 'conversation-unread' : ''; ?>">
                    <div class="d-flex align-items-center">
                        <div class="profile-circle"><?php echo htmlspecialchars($initiale); ?></div>

                        <div class="ms-3">
                            <h4 class="m-0 fw-bold">
                                <?= htmlspecialchars($dest['nom']) ?>
                            </h4>
                            <p class="m-0 text-muted">
                                <?= htmlspecialchars($dest['dernier_message'] ?? 'Aucun message') ?>
                            </p>
                        </div>
                    </div>

                    <div class="d-flex align-items-center">
                        <?php if ($nonLus > 0): ?>
                            <span class="badge badge-unread">
                                <?php echo $nonLus; ?>
                            </span>
                        <?php endif; ?>
                        <div class="arrow-box ms-3">›</div>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>

        </div>

    </div>
    </div>
</div>

<script src="/js/messagerie-search.js"></script>


</body>
</html>
