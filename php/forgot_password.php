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
        $link = "logemangue.gt.tc/php/reset_password.php?token=$token";

        // Envoi email
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'mc.carruette@gmail.com';
            $mail->Password = 'cwta uhgq kuyb fiug';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->CharSet = 'UTF-8';

            // IMPORTANT : même email que le SMTP
            $mail->setFrom('tonemail@gmail.com', 'Logemangue Support');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Réinitialisation de votre mot de passe';
            $mail->Body = "
<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Réinitialisation mot de passe</title>
</head>
<body style='margin:0;padding:0;font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Roboto,\"Helvetica Neue\",Arial,sans-serif;background-color:#f5f5f5;'>
    <table width='100%' cellpadding='0' cellspacing='0' style='background-color:#f5f5f5;padding:40px 20px;'>
        <tr>
            <td align='center'>
                <table width='600' cellpadding='0' cellspacing='0' style='background:linear-gradient(135deg,#ff9a56 0%,#ff6a00 50%,#ff4500 100%);border-radius:16px;overflow:hidden;box-shadow:0 10px 40px rgba(255,106,0,0.3);'>
                    <!-- Header avec dégradé -->
                    <tr>
                        <td style='padding:50px 40px;text-align:center;'>
                            <h1 style='margin:0;color:#ffffff;font-size:32px;font-weight:700;text-shadow:0 2px 10px rgba(0,0,0,0.2);'>
                                🔐 Réinitialisation
                            </h1>
                        </td>
                    </tr>
                    
                    <!-- Corps du message avec fond blanc -->
                    <tr>
                        <td style='background-color:#ffffff;padding:50px 40px;'>
                            <p style='margin:0 0 20px;color:#333;font-size:18px;line-height:1.6;'>
                                Bonjour,
                            </p>
                            
                            <p style='margin:0 0 30px;color:#666;font-size:16px;line-height:1.6;'>
                                Vous avez demandé la réinitialisation de votre mot de passe. Pour continuer, cliquez sur le bouton ci-dessous :
                            </p>
                            
                            <!-- Bouton CTA -->
                            <table width='100%' cellpadding='0' cellspacing='0'>
                                <tr>
                                    <td align='center' style='padding:10px 0 30px;'>
                                        <a href='$link' style='display:inline-block;background:linear-gradient(135deg,#ff9a56,#ff6a00);color:#ffffff;text-decoration:none;padding:16px 40px;border-radius:50px;font-size:16px;font-weight:600;box-shadow:0 4px 15px rgba(255,106,0,0.4);transition:all 0.3s ease;'>
                                            Réinitialiser mon mot de passe
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Info box -->
                            <table width='100%' cellpadding='0' cellspacing='0' style='background-color:#fff5ed;border-left:4px solid #ff6a00;border-radius:8px;margin:20px 0;'>
                                <tr>
                                    <td style='padding:20px;'>
                                        <p style='margin:0;color:#d65a00;font-size:14px;line-height:1.5;'>
                                            ⏱️ <strong>Ce lien est valable pendant 1 heure</strong>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style='margin:0 0 10px;color:#999;font-size:14px;line-height:1.6;'>
                                Si le bouton ne fonctionne pas, copiez et collez ce lien dans votre navigateur :
                            </p>
                            <p style='margin:0 0 30px;color:#ff6a00;font-size:13px;word-break:break-all;'>
                                $link
                            </p>
                            
                            <p style='margin:0;color:#999;font-size:14px;line-height:1.6;'>
                                Si vous n'êtes pas à l'origine de cette demande, vous pouvez ignorer cet email en toute sécurité.
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style='background-color:#2d2d2d;padding:30px 40px;text-align:center;'>
                            <p style='margin:0 0 10px;color:#ffffff;font-size:16px;font-weight:600;'>
                                Logemangue Support
                            </p>
                            <p style='margin:0;color:#999;font-size:13px;'>
                                © 2025 Logemangue. Tous droits réservés.
                            </p>
                        </td>
                    </tr>
                </table>
                
                <!-- Note sous l'email -->
                <table width='600' cellpadding='0' cellspacing='0' style='margin-top:20px;'>
                    <tr>
                        <td style='text-align:center;color:#999;font-size:12px;line-height:1.5;'>
                            Cet email a été envoyé automatiquement, merci de ne pas y répondre.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
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
