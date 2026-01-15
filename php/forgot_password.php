<?php
require_once 'db2withoutlogin.php';

/* ===== PHPMailer ===== */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer-7.0.2/src/Exception.php';
require '../PHPMailer-7.0.2/src/PHPMailer.php';
require '../PHPMailer-7.0.2/src/SMTP.php';

$message = "";
$user = null; 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Génération du token
        $token = bin2hex(random_bytes(32));
        $expires = date("Y-m-d H:i:s", time() + 3600);

        $stmt = $pdo->prepare(
            "UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?"
        );
        $stmt->execute([$token, $expires, $email]);

        // Lien absolu (IMPORTANT)
        $link = "localhost/Logemangue/php/reset_password.php?token=$token";

        // Envoi email
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'etoiledemortlaguerredesclans@gmail.com';
            $mail->Password = 'xhft atkl wfjz elsq
';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->CharSet = 'UTF-8';

            // IMPORTANT : même email que le SMTP
            $mail->setFrom('tonemail@gmail.com', 'Logemangue Support');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Réinitialisation de votre mot de passe';
            $mail->Body = "
                <p>Bonjour,</p>
                <p>Vous avez demandé la réinitialisation de votre mot de passe.</p>
                <p>
                    <a href='$link' style='color:#ff7a00;font-weight:bold;'>
                        Cliquez ici pour réinitialiser votre mot de passe
                    </a>
                </p>
                <p>Ce lien est valable 1 heure.</p>
                <p>Si vous n'êtes pas à l'origine de cette demande, ignorez cet email.</p>
            ";

            $mail->send();

            $message = "Un lien de réinitialisation a été envoyé à votre adresse email.";
            $message_type = "success";

        } catch (Exception $e) {
            $message = "Erreur lors de l'envoi de l'email.";
            $message_type = "error";
        }

    } else {
        // Message volontairement vague (sécurité)
        $message = "Si un compte existe avec cet email, un lien a été envoyé.";
        $message_type = "success";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mot de passe oublié</title>
  <link rel="stylesheet" href="../css/style.css">
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
    <main
      style="font-family: Arial;"
      >

  <div class="auth-container">
    <div class="auth-card">
      <h2>Mot de passe oublié</h2>
      <p class="subtitle">Entrez votre email pour recevoir un lien de réinitialisation.</p>
      
      <form method="post">
        <input type="email" name="email" placeholder="Votre email" required>
        <button type="submit">Envoyer le lien</button>
      </form>
      
      <?php if (!empty($message)) : ?>
        <p class="message"><?= $message ?></p>
        <?php endif; ?>
        <?php if (!empty($link)) : ?>
          <div class="reset-link-box">
            <p>Lien de réinitialisation :</p>
            <a class="reset-link-btn" href="<?= $link ?>" target="_blank">
              Ouvrir le lien de réinitialisation
            </a>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </body>
