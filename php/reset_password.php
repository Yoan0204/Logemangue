<?php
require_once 'db2withoutlogin.php';

$token = $_GET['token'] ?? '';
$message = "";

$stmt = $pdo->prepare(
    "SELECT * FROM users WHERE reset_token = ? AND reset_expires > NOW()"
);
$stmt->execute([$token]);
$user = $stmt->fetch();

if (!$user) {
    $message = "Lien invalide ou expiré.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare(
        "UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?"
    );
    $stmt->execute([$password, $user['id']]);

    $message = "Mot de passe modifié avec succès. <a href='login.html'>Se connecter</a>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
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
</body>
</html>