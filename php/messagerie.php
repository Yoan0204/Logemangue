<!DOCTYPE html>
<html lang="fr">

<?php include "db2.php"; ?>
<?php
// Traiter l'envoi de message en PREMIER
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
  ob_clean(); // Nettoyer tout buffer
  header('Content-Type: application/json');
  
  // Vérifier l'authentification
  if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Non authentifié']);
    exit;
  }
  
  $userId = $_SESSION['user_id'];
  $destinataireId = isset($_POST['destinataire_id']) ? intval($_POST['destinataire_id']) : 0;
  $message = isset($_POST['message']) ? trim($_POST['message']) : '';
  
  // Validation
  if (empty($message)) {
    echo json_encode(['success' => false, 'error' => 'Message vide']);
    exit;
  }
  
  if ($destinataireId <= 0) {
    echo json_encode(['success' => false, 'error' => 'Destinataire invalide']);
    exit;
  }
  
  // Insertion 
  try {
    $sql = "INSERT INTO message (id_expediteur, id_destinataire, contenu, date_envoi) 
            VALUES (:id_expediteur, :id_destinataire, :contenu, NOW())";
    
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
      ':id_expediteur' => $userId,
      ':id_destinataire' => $destinataireId,
      ':contenu' => $message
    ]);
    
    if ($result) {
      echo json_encode(['success' => true, 'message_id' => $pdo->lastInsertId()]);
    } else {
      echo json_encode(['success' => false, 'error' => 'Échec de l\'insertion']);
    }
    
  } catch(PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Erreur: ' . $e->getMessage()]);
  }
  
  exit;
}

if (!isset($_GET["dest"]) || !is_numeric($_GET["dest"])) {
    die("ID du destinataire invalide.");
}
$destinataire_id = (int) $_GET["dest"];
$offset = isset($_GET["offset"]) ? (int) $_GET["offset"] : 0;
$limit = 10;
try {
    $stmt = $pdo->prepare("
        SELECT *
        FROM message
        WHERE (id_expediteur = :userId AND id_destinataire = :destinataire_id)
           OR (id_expediteur = :destinataire_id AND id_destinataire = :userId)
        ORDER BY date_envoi DESC
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindValue(":userId", $userId, PDO::PARAM_INT);
    $stmt->bindValue(":destinataire_id", $destinataire_id, PDO::PARAM_INT);
    $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
    $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $messages = array_reverse($messages); // Vérifier s'il y a plus de messages
    $stmtCount = $pdo->prepare("
        SELECT COUNT(*) as total
        FROM message
        WHERE (id_expediteur = :userId AND id_destinataire = :destinataire_id)
           OR (id_expediteur = :destinataire_id AND id_destinataire = :userId)
    ");
    $stmtCount->execute([
        "userId" => $userId,
        "destinataire_id" => $destinataire_id,
    ]);
    $totalMessages = $stmtCount->fetch(PDO::FETCH_ASSOC)["total"];
    $hasMore = $offset + $limit < $totalMessages;
} catch (PDOException $e) {
    die("Erreur lors de la récupération des messages : " . $e->getMessage());
} // Récupère le nom du destinataire
$stmt_dest = $pdo->prepare("SELECT nom FROM users WHERE id = :destinataire_id");
$stmt_dest->execute(["destinataire_id" => $destinataire_id]);
$destinataire = $stmt_dest->fetch(PDO::FETCH_ASSOC);
$nom_destinataire = $destinataire
    ? htmlspecialchars($destinataire["nom"])
    : "Utilisateur inconnu";

?>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Messagerie - Logemangue</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/messagerie.css">
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
      <a class="nav-link" href="logements?view=mesannonces">Mes annonces</a>

      <a class="nav-link active-link" href="listemessagerie">Ma messagerie</a>

      <?php if ($isAdmin): ?> 
          <a class="nav-link" href="admin">Admin ⚙️</a>
      <?php endif; ?>

      <a class="nav-link" href="profil">Mon profil</a>
    </nav>
  </header>
<body>
  <!-- CONTENU PRINCIPAL -->
  <main class="flex-grow-1 p-4">

    <!-- Bandeau supérieur : nom du contact -->
    <div class="messagerie-header rounded-4 p-3 mb-4 d-flex align-items-center">
      <div class="messagerie-avatar">C</div>
      <h2 class="ms-3 fw-bold text-dark m-0"><?php echo $nom_destinataire; ?></h2>
    </div>
  <?php if ($offset > 0): ?>
    <a href="?dest=<?php echo $destinataire_id; ?>" class="nav-link btn-login paddin-top-10 mt-3 text-center">
        ← Retour aux messages récents
    </a>
    <a href="?dest=<?php echo $destinataire_id; ?>&offset=<?php echo $offset -
    10; ?>" class="nav-link btn-login paddin-top-10 mt-3 text-center">
        Charger les 10 messages précedents
    </a>
    <?php endif; ?><br>
        <div id="messages-list">
          <!-- Zone de conversation -->
          <div class="conversation-box p-4 rounded-4">
            <?php foreach ($messages as $message): ?>
                <?php // Détermine la classe CSS en fonction de l'expéditeur
                // Détermine la classe CSS en fonction de l'expéditeur
                $class =
                    $message["id_expediteur"] == $userId
                        ? "message message-right mb-4"
                        : "message message-left mb-4"; ?>
                <div class="<?php echo $class; ?>">
                    <p><?php echo htmlspecialchars($message["contenu"]); ?></p>
                    <small><?php echo $message["date_envoi"]; ?></small>
                </div>
            <?php endforeach; ?>
          </div>
        </div>
            
    <?php if ($hasMore): ?>
        <a href="?dest=<?php echo $destinataire_id; ?>&offset=<?php echo $offset +
    10; ?>" class="nav-link btn-login paddin-top-10 mt-3 text-center">
            Charger 10 messages supplémentaires
        </a>
    <?php endif; ?>


    <!-- Barre d’envoi -->
    <div class="send-bar d-flex align-items-center mt-4 p-3 rounded-4">
      <input type="text" id="messageInput" class="form-control send-input" placeholder="Message...">
      <button id="sendButton" class="send-button ms-3">▶</button>
    </div>

  </main>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const messageInput = document.getElementById('messageInput');
  const sendButton = document.getElementById('sendButton');
  
  function sendMessage() {
    const message = messageInput.value.trim();
    
    if (message === '') {
      alert('Veuillez saisir un message');
      return;
    }
    
    const destinataireId = <?php echo json_encode($destinataire_id); ?>;
    
    // Envoi vers la même page
    fetch(window.location.href, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: `message=${encodeURIComponent(message)}&destinataire_id=${destinataireId}`
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        messageInput.value = '';
        console.log('Message envoyé');
        // Optionnel : recharger les messages
        location.reload();
      } else {
        alert('Erreur : ' + data.error);
      }
    })
    .catch(error => {
      console.error('Erreur:', error);
      alert('Erreur lors de l\'envoi');
    });
  }
  
  sendButton.addEventListener('click', sendMessage);
  
  messageInput.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
      sendMessage();
    }
  });
});
</script>
<script src="../js/responsive.js"></script>
</body>
</html>
