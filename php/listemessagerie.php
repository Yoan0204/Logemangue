<?php require 'db2.php';?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ma Messagerie</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* En-tête */
        .header-box {
            background: linear-gradient(to right, #ffcd00, #ff7f32);
            padding: 25px 40px;
            margin: 20px;
            border-radius: 18px;
            box-shadow: 6px 6px 0px #e3dfd1;
            align-items: center;
            self-align: center;
        }

        .search-bar {
            background: linear-gradient(to right, #ffcd00, #ff8a3d);
            padding: 10px 20px;
            margin: 25px auto;
            width: 60%;
            border-radius: 15px;
            box-shadow: 4px 4px 0px #e3dfd1;
        }

        .search-bar input {
            border: none;
            width: 100%;
            padding: 8px;
            border-radius: 8px;
        }

        .separator-line {
            width: 60%;
            height: 4px;
            background: #0e6b37;
            margin: 20px auto;
            border-radius: 2px;
        }

        /* Conversations */
        .conversation {
            display: flex;
            align-items: center;
            padding: 15px;
            margin: 18px 0;
            border-radius: 15px;
            background: linear-gradient(to right, #ffcd00, #ff8a3d);
            box-shadow: 4px 4px 0px #e3dfd1;
            cursor: pointer;
        }

        .conversation:hover {
            transform: scale(1.01);
            transition: 0.2s;
        }

        .profile-circle {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 24px;
            margin-right: 20px;
        }

        .arrow-box {
            margin-left: auto;
            background: #bd6438;
            color: white;
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

    </style>
</head>
  <header class="topbar">
    <a href="index.php" class="topbar-logo">
      <img src="../png/topbar.png" onresize="3000" alt="Logo" />
    </a>

    <nav class="topbar-nav">
      <a class="nav-link " href="index.php">Accueil</a>
      <a class="nav-link" href="recherche.php">Recherche</a>

      <a class="nav-link" href="publish.php">Publier une annonce</a>
      <a class="nav-link" href="mesannonces.php">Mes annonces</a>

      <a class="nav-link active-link" href="listemessagerie.php">Ma messagerie</a>

      <a class="nav-link" href="admin.php">Admin ⚙️</a>

      <a class="nav-link " href="profil.php">Mon profil</a>
    </nav>
  </header>
<body>

    <!-- Contenu principal -->
    <div>
        <div class="header-box mb-4 items-center">
            <h1 class="fw-bold">Ma messagerie</h1>
        </div>

        <!-- Barre de recherche -->
        <div class="search-bar">
            <input type="text" placeholder=" | Rechercher (Contact, Bien, ...)">
        </div>

        <div class="separator-line"></div>

        <!-- Liste des conversations -->
        <div class="container">

            <?php
            // Requête améliorée pour récupérer aussi le dernier message
            $stmt = $pdo->prepare("
                SELECT DISTINCT u.id, u.nom, 
                    (SELECT contenu 
                        FROM message 
                        WHERE (id_expediteur = ? AND id_destinataire = u.id) 
                        OR (id_expediteur = u.id AND id_destinataire = ?)
                        ORDER BY date_envoi DESC 
                        LIMIT 1) as dernier_message
                FROM message m
                INNER JOIN users u ON m.id_destinataire = u.id
                WHERE m.id_expediteur = ?
                ORDER BY u.nom
            ");

            $stmt->execute([$userId, $userId, $userId]);
            $destinataires = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($destinataires as $destinataire): 
                $initiale = strtoupper(substr($destinataire['nom'], 0, 1));
                $dernierMessage = $destinataire['dernier_message'] ?? 'Aucun message';
            ?>
            <a href="messagerie.php?dest=<?php echo $destinataire['id']; ?>" style="text-decoration: none; color: inherit;">
                <div class="conversation">
                    <div class="profile-circle"><?php echo htmlspecialchars($initiale); ?></div>
                    <div>
                        <h4 class="m-0 fw-bold"><?php echo htmlspecialchars($destinataire['nom']); ?></h4>
                        <p class="m-0"><?php echo htmlspecialchars($dernierMessage); ?></p>
                    </div>
                    <div class="arrow-box">›</div>
                </div>
            </a>
            <?php endforeach; ?>

        </div>

    </div>

</body>
</html>
