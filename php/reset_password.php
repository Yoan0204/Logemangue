<?php
require_once 'db2withoutlogin.php';

$token = $_GET['token'] ?? '';
$message = "";
$message_type = "";

$stmt = $pdo->prepare(
    "SELECT * FROM users WHERE reset_token = ? AND reset_expires > NOW()"
);
$stmt->execute([$token]);
$user = $stmt->fetch();

if (!$user) {
<<<<<<< HEAD
    $error = true;
    $message = "Lien invalide ou expiré.";
    $message_type = "error";
=======
    $message = "Lien invalide ou expiré.";
>>>>>>> 4038c14523fc0f4b8f6a28b3e6828a4cc8d237c9
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && $user) {
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare(
        "UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?"
    );
    $stmt->execute([$password, $user['id']]);

    $message = "Mot de passe modifié avec succès. <a href='login.html'>Se connecter</a>";
    $message_type = "success";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<<<<<<< HEAD
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/password.css" rel="stylesheet">

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

      <a class="nav-link" href="publish">Publier une annonce</a>
      <?php if (!$isEtudiant): ?>
      <a class="nav-link" href="logements?view=mesannonces">Mes annonces</a>        
      <?php endif; ?>

      <a class="nav-link" href="listemessagerie">Ma messagerie</a>
      <a class="nav-link " href="profil.php">Mon profil</a>
    </nav>
  </header>
<body>
    <div class="container" style="margin-top: 80px;">
        <?php if (isset($error) && $error): ?>
            <div class="error-container">
                <div class="error-icon">🔒</div>
                <h2>Lien invalide</h2>
                <div class="message error">
                    <?= $message ?>
                </div>
                <div class="back-link">
                    <a href="forgot_password.php">← Demander un nouveau lien</a>
                </div>
            </div>
        <?php else: ?>
            <h2>Nouveau mot de passe</h2>
            <p class="subtitle">Choisissez un nouveau mot de passe sécurisé pour votre compte.</p>

            <form method="post" id="resetForm">
                <div class="form-group">
                    <label for="password">Nouveau mot de passe</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" placeholder="Entrez votre nouveau mot de passe" required minlength="8">
                        <button type="button" class="toggle-password" onclick="togglePassword()">👁️</button>
                    </div>
                </div>

                <div class="password-requirements">
                    <strong>Votre mot de passe doit contenir :</strong>
                    <ul>
                        <li id="length">Au moins 8 caractères</li>
                        <li id="uppercase">Une lettre majuscule</li>
                        <li id="lowercase">Une lettre minuscule</li>
                        <li id="number">Un chiffre</li>
                    </ul>
                </div>

                <button type="submit" class="btn">Changer le mot de passe</button>
            </form>

            <?php if ($message): ?>
                <div class="message <?= $message_type ?>">
                    <?= $message ?>
                </div>
            <?php endif; ?>

            <div class="back-link">
                <a href="login.html">← Retour à la connexion</a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleBtn = document.querySelector('.toggle-password');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleBtn.textContent = '🙈';
            } else {
                passwordInput.type = 'password';
                toggleBtn.textContent = '👁️';
            }
        }

        // Validation en temps réel du mot de passe
        const passwordInput = document.getElementById('password');
        
        if (passwordInput) {
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                
                // Vérifier la longueur
                const lengthReq = document.getElementById('length');
                if (password.length >= 8) {
                    lengthReq.classList.add('requirement-met');
                } else {
                    lengthReq.classList.remove('requirement-met');
                }
                
                // Vérifier majuscule
                const uppercaseReq = document.getElementById('uppercase');
                if (/[A-Z]/.test(password)) {
                    uppercaseReq.classList.add('requirement-met');
                } else {
                    uppercaseReq.classList.remove('requirement-met');
                }
                
                // Vérifier minuscule
                const lowercaseReq = document.getElementById('lowercase');
                if (/[a-z]/.test(password)) {
                    lowercaseReq.classList.add('requirement-met');
                } else {
                    lowercaseReq.classList.remove('requirement-met');
                }
                
                // Vérifier chiffre
                const numberReq = document.getElementById('number');
                if (/[0-9]/.test(password)) {
                    numberReq.classList.add('requirement-met');
                } else {
                    numberReq.classList.remove('requirement-met');
                }
            });
        }
    </script>
=======
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nouveau mot de passe</title>
  <link rel="stylesheet" href="../css/style.css?v=1">
</head>

<body>
    <main
      style="font-family: Arial;"
      >
  <div class="auth-container">
    <div class="auth-card">
      <h2>Nouveau mot de passe</h2>
      <p class="subtitle">Choisissez un nouveau mot de passe pour votre compte.</p>

      <form method="post">
        <input type="password" name="password" placeholder="Nouveau mot de passe" required>
        <button type="submit">Changer</button>
      </form>

      <?php if (!empty($message)) : ?>
        <p class="message"><?= $message ?></p>
      <?php endif; ?>

      <div class="auth-links">
        <a href="login.html" class="small-link">← Retour à la connexion</a>
      </div>
    </div>
  </div>
>>>>>>> 4038c14523fc0f4b8f6a28b3e6828a4cc8d237c9
</body>
</html>