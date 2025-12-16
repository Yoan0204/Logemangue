<?php require 'db2.php';?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ma Messagerie</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/listmessagerie.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    
</head>
  <header class="topbar">
    <a href="index.php" class="topbar-logo">
      <img src="../png/topbar.png" onresize="3000" alt="Logo" />
    </a>

    <nav class="topbar-nav">
      <a class="nav-link " href="index.php">Accueil</a>
      <a class="nav-link" href="logements.php">Recherche</a>

      <?php if (!$isEtudiant): ?>
      <a class="nav-link" href="publish.php">Publier une annonce</a>
      <?php endif; ?>
      <a class="nav-link" href="logements.php?view=mesannonces">Mes annonces</a>

      <a class="nav-link active-link" href="listemessagerie.php">Ma messagerie</a>
        <?php if ($isAdmin): ?>
      <a class="nav-link" href="admin.php">Admin ⚙️</a>
        <?php endif; ?>
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
            <input type="text" id="searchInput" placeholder=" | Rechercher (Contact, Bien, ...)" autocomplete="off">
        </div>

        <div class="separator-line"></div>

        <!-- Liste des conversations -->
        <div class="container" id="conversationsContainer">

<?php
// Requête corrigée pour récupérer tous les utilisateurs en conversation + dernier message
$stmt = $pdo->prepare("
    SELECT DISTINCT 
        u.id,
        u.nom,
        (
            SELECT contenu
            FROM message 
            WHERE 
                (id_expediteur = :userId AND id_destinataire = u.id)
                OR 
                (id_expediteur = u.id AND id_destinataire = :userId)
            ORDER BY date_envoi DESC 
            LIMIT 1
        ) AS dernier_message
    FROM users u
    INNER JOIN message m 
        ON (m.id_expediteur = u.id OR m.id_destinataire = u.id)
    WHERE :userId IN (m.id_expediteur, m.id_destinataire)
    AND u.id != :userId
    ORDER BY u.nom
");

// Exécution
$stmt->execute(['userId' => $userId]);
$destinataires = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Boucle d'affichage
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

    <script>
        // Récupérer l'input de recherche et le conteneur
        const searchInput = document.getElementById('searchInput');
        const conversations = document.querySelectorAll('.conversation');

        // Fonction de filtrage
        function filterConversations() {
            const searchTerm = searchInput.value.toLowerCase().trim();

            conversations.forEach(conversation => {
                // Récupérer le nom du contact et le dernier message
                const contactName = conversation.querySelector('h4').textContent.toLowerCase();
                const lastMessage = conversation.querySelector('p').textContent.toLowerCase();

                // Vérifier si le texte de recherche correspond
                const matches = contactName.includes(searchTerm) || lastMessage.includes(searchTerm) || searchTerm === '';

                // Afficher ou masquer la conversation
                conversation.parentElement.style.display = matches ? 'block' : 'none';
            });
        }

        // Ajouter un événement d'écoute sur l'input
        searchInput.addEventListener('input', filterConversations);
        searchInput.addEventListener('keyup', filterConversations);

        // Optionnel: afficher un message si aucun résultat
        function updateEmptyMessage() {
            const visibleConversations = Array.from(conversations).filter(
                conv => conv.parentElement.style.display !== 'none'
            );
            
            const container = document.getElementById('conversationsContainer');
            let emptyMessage = container.querySelector('.empty-message');
            
            if (visibleConversations.length === 0 && searchInput.value.trim()) {
                if (!emptyMessage) {
                    emptyMessage = document.createElement('div');
                    emptyMessage.className = 'empty-message text-center text-muted py-5';
                    emptyMessage.textContent = 'Aucune conversation trouvée.';
                    container.appendChild(emptyMessage);
                }
                emptyMessage.style.display = 'block';
            } else if (emptyMessage) {
                emptyMessage.style.display = 'none';
            }
        }

        searchInput.addEventListener('input', () => {
            filterConversations();
            updateEmptyMessage();
        });
    </script>

</body>
      <footer class="text-center py-3">
        <?php include 'footer.php'; ?>
      </footer>
</html>
