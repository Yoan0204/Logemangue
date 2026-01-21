<?php
require_once 'db2withoutlogin.php'; // Ajustez selon votre configuration

$message = '';
$messageType = '';
$isConnected = isset($_SESSION['user_id']);

// Si l'utilisateur est connecté, récupérer ses informations
$userData = [];
if ($isConnected) {
    try {
        $sql = "SELECT nom, prenom, email, telephone FROM users WHERE id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':user_id' => $_SESSION['user_id']]);
        $userData = $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Erreur récupération user: " . $e->getMessage());
    }
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['envoyer'])) {
    // Récupération et nettoyage des données
    $nom = htmlspecialchars(trim($_POST['nom']), ENT_QUOTES, 'UTF-8');
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $telephone = !empty($_POST['telephone']) ? htmlspecialchars(trim($_POST['telephone']), ENT_QUOTES, 'UTF-8') : null;
    $sujet = htmlspecialchars(trim($_POST['sujet']), ENT_QUOTES, 'UTF-8');
    $messageText = htmlspecialchars(trim($_POST['message']), ENT_QUOTES, 'UTF-8');
    
    // Validation côté serveur
    $erreurs = [];
    
    // Validation du nom
    if (!preg_match("/^[a-zA-ZÀ-ÿ\s'\-]+$/", $nom)) {
        $erreurs[] = "Le nom contient des caractères non autorisés.";
    }
    
    // Validation de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = "L'adresse email n'est pas valide.";
    }
    
    // Validation du téléphone (si fourni)
    if ($telephone && !preg_match("/^[0-9]{10}$/", $telephone)) {
        $erreurs[] = "Le numéro de téléphone doit contenir exactement 10 chiffres.";
    }
    
    // Validation du sujet
    $sujets_valides = ['question', 'logement', 'technique', 'compte', 'autre'];
    if (!in_array($sujet, $sujets_valides)) {
        $erreurs[] = "Le sujet sélectionné n'est pas valide.";
    }
    
    // Validation du message
    if (empty($messageText)) {
        $erreurs[] = "Le message ne peut pas être vide.";
    } elseif (strlen($messageText) > 1000) {
        $erreurs[] = "Le message ne peut pas dépasser 1000 caractères.";
    }
    
    // Si pas d'erreurs, insertion en base de données
    if (empty($erreurs)) {
        try {
            $sql = "INSERT INTO contacts (user_id, nom, email, telephone, sujet, message, date_creation, statut) 
                    VALUES (:user_id, :nom, :email, :telephone, :sujet, :message, NOW(), 'non_lu')";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':user_id' => $isConnected ? $_SESSION['user_id'] : null,
                ':nom' => $nom,
                ':email' => $email,
                ':telephone' => $telephone,
                ':sujet' => $sujet,
                ':message' => $messageText
            ]);
            
            $message = "Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.";
            $messageType = 'success';
            
            // Optionnel : Envoyer un email de notification
            // mail('contact@logemangue.fr', 'Nouveau message de contact', $messageText);
            
        } catch (PDOException $e) {
            $erreurs[] = "Une erreur est survenue lors de l'envoi de votre message. Veuillez réessayer.";
            error_log("Erreur contact form: " . $e->getMessage());
        }
    }
    
    if (!empty($erreurs)) {
        $message = implode('<br>', $erreurs);
        $messageType = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact - Logemangue</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/contact.css">

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
  <div class="container">
    <h1>Contactez-nous</h1>

    <?php if (!empty($message)): ?>
      <div class="alert-<?php echo $messageType; ?>-custom">
        <?php if ($messageType === 'success'): ?>
          ✓ <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
        <?php else: ?>
          ❌ <?php echo $message; ?>  
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <div class="contact-box">
      <div class="contact-header">
        <div class="contact-icon">✉️</div>
        <div class="contact-title">Une question ? Besoin d'aide ?</div>
        <div class="contact-subtitle">Remplissez le formulaire ci-dessous et nous vous répondrons rapidement</div>
      </div>

      <?php if ($isConnected && !empty($userData)): ?>
        <div class="user-info-badge">
          ✓ Connecté en tant que <?php echo htmlspecialchars($userData['nom'], ENT_QUOTES, 'UTF-8'); ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="" id="contactForm">
        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label for="nom">Nom complet<span class="required">*</span></label>
            <input type="text" id="nom" name="nom" class="form-control" placeholder="Jean Dupont" required pattern="[a-zA-ZÀ-ÿ\s'\-]+" title="Seules les lettres, espaces, tirets et apostrophes sont autorisés" value="<?php 
              if (isset($_POST['nom'])) {
                echo htmlspecialchars($_POST['nom'], ENT_QUOTES, 'UTF-8');
              } elseif ($isConnected && !empty($userData)) {
                echo htmlspecialchars(($userData['prenom'] ?? '') . ' ' . ($userData['nom'] ?? ''), ENT_QUOTES, 'UTF-8');
              }
            ?>" <?php echo $isConnected ? 'readonly' : ''; ?>>
          </div>
          <div class="col-md-6">
            <label for="email">Email<span class="required">*</span></label>
            <input type="email" id="email" name="email" class="form-control" placeholder="jean.dupont@example.com" required value="<?php 
              if (isset($_POST['email'])) {
                echo htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
              } elseif ($isConnected && !empty($userData)) {
                echo htmlspecialchars($userData['email'], ENT_QUOTES, 'UTF-8');
              }
            ?>" <?php echo $isConnected ? 'readonly' : ''; ?>>
          </div>
        </div>

        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label for="telephone">Téléphone</label>
            <input type="tel" id="telephone" name="telephone" class="form-control" placeholder="0612345678" pattern="[0-9]{10}" title="Le numéro de téléphone doit contenir exactement 10 chiffres" maxlength="10" value="<?php 
              if (isset($_POST['telephone'])) {
                echo htmlspecialchars($_POST['telephone'], ENT_QUOTES, 'UTF-8');
              } elseif ($isConnected && !empty($userData) && !empty($userData['telephone'])) {
                echo htmlspecialchars($userData['telephone'], ENT_QUOTES, 'UTF-8');
              }
            ?>">
          </div>
          <div class="col-md-6">
            <label for="sujet">Sujet<span class="required">*</span></label>
            <select id="sujet" name="sujet" class="form-select" required>
              <option value="">Sélectionnez un sujet</option>
              <option value="question" <?php echo (isset($_POST['sujet']) && $_POST['sujet'] === 'question') ? 'selected' : ''; ?>>Question générale</option>
              <option value="logement" <?php echo (isset($_POST['sujet']) && $_POST['sujet'] === 'logement') ? 'selected' : ''; ?>>Question sur un logement</option>
              <option value="technique" <?php echo (isset($_POST['sujet']) && $_POST['sujet'] === 'technique') ? 'selected' : ''; ?>>Problème technique</option>
              <option value="compte" <?php echo (isset($_POST['sujet']) && $_POST['sujet'] === 'compte') ? 'selected' : ''; ?>>Gestion de compte</option>
              <option value="autre" <?php echo (isset($_POST['sujet']) && $_POST['sujet'] === 'autre') ? 'selected' : ''; ?>>Autre</option>
            </select>
          </div>
        </div>

        <div class="mb-3">
          <label for="message">Message<span class="required">*</span></label>
          <textarea id="message" name="message" class="form-control" placeholder="Décrivez votre demande en détail..." required maxlength="1000"><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
          <div class="char-counter" id="charCounter">0 / 1000 caractères</div>
        </div>

        <button type="submit" name="envoyer" class="btn-submit">Envoyer le message</button>
      </form>
    </div>
  </div>

      <footer class="text-center">
        <?php include "footer.php"; ?>
      </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Validation du nom
    document.getElementById('nom').addEventListener('input', function(e) {
      this.value = this.value.replace(/[^a-zA-ZÀ-ÿ\s'\-]/g, '');
    });

    // Validation du téléphone
    document.getElementById('telephone').addEventListener('input', function(e) {
      this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
    });

    // Compteur de caractères
    const messageField = document.getElementById('message');
    const charCounter = document.getElementById('charCounter');
    
    function updateCharCounter() {
      const length = messageField.value.length;
      charCounter.textContent = `${length} / 1000 caractères`;
      
      if (length > 900) {
        charCounter.classList.add('warning');
      } else {
        charCounter.classList.remove('warning');
      }
    }
    
    messageField.addEventListener('input', updateCharCounter);
    
    // Initialiser le compteur au chargement
    updateCharCounter();

    // Empêcher les caractères dangereux dans le message
    messageField.addEventListener('input', function(e) {
      this.value = this.value.replace(/[<>{}[\]\\]/g, '');
    });

    // Validation côté client avant soumission
    document.getElementById('contactForm').addEventListener('submit', function(e) {
      const nom = document.getElementById('nom').value;
      const email = document.getElementById('email').value;
      const telephone = document.getElementById('telephone').value;
      const message = document.getElementById('message').value;

      // Validation du nom
      const nomPattern = /^[a-zA-ZÀ-ÿ\s'\-]+$/;
      if (!nomPattern.test(nom)) {
        e.preventDefault();
        alert('Le nom ne peut contenir que des lettres, espaces, tirets et apostrophes.');
        return false;
      }

      // Validation du téléphone (si fourni)
      if (telephone && !/^[0-9]{10}$/.test(telephone)) {
        e.preventDefault();
        alert('Le numéro de téléphone doit contenir exactement 10 chiffres.');
        return false;
      }

      // Validation du message
      if (!message.trim()) {
        e.preventDefault();
        alert('Le message ne peut pas être vide.');
        return false;
      }
    });
  </script>
</body>

</html>