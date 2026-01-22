<?php
require 'db.php';
/* ===== PHPMailer ===== */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer-7.0.2/src/Exception.php';
require '../PHPMailer-7.0.2/src/PHPMailer.php';
require '../PHPMailer-7.0.2/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $name = trim($_POST['name']);
    $phone = $_POST['phone'];
    $genre = $_POST['genre'];
    $birthdate = $_POST['birthdate_value'];
    $type_utilisateur = $_POST['type_utilisateur'];

    // Vérification caractères nom
    if (!preg_match('/^[a-zA-Z0-9 _-]+$/', $name)) {
        header("Location: login.html?error=carun");
        exit;
    }

    if (!$email) {
        die("Email invalide");
    }

    if ($password !== $confirm_password) {
        die("Les mots de passe ne correspondent pas.");
    }

    // Vérifier si email existe déjà
    $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);
    if ($check->fetch()) {
        die("Email déjà utilisé.");
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Génération token
    $token = bin2hex(random_bytes(32));

    try {
        // Insertion utilisateur NON vérifié
        $stmt = $pdo->prepare("
            INSERT INTO users 
            (email, password, nom, telephone, genre, date_naissance, type_utilisateur, email_verifie, verification_token)
            VALUES (?, ?, ?, ?, ?, ?, ?, 0, ?)
        ");

        $stmt->execute([
            $email,
            $hashed_password,
            $name,
            $phone,
            $genre,
            $birthdate,
            $type_utilisateur,
            $token
        ]);

        // ==========================
        // ENVOI EMAIL DE VÉRIFICATION
        // ==========================
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // ou autre SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'mc.carruette@gmail.com';
        $mail->Password = 'onjc vnyy epfo bomd
';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->CharSet = 'UTF-8';
        $mail->setFrom('mc.carruette@gmail.com', 'Logemangue');
        $mail->addAddress($email, $name);

        $verificationLink = "http://localhost/Logemangue/php/verify_email.php?token=$token";

$mail->isHTML(true);
$mail->Subject = 'Vérifiez votre adresse email - Logemangue';

$mail->Body = "
<!DOCTYPE html>
<html lang='fr'>
<head>
    <link rel="icon" type="image/x-icon" href="../png/icon.png" />
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Vérification email</title>
</head>
<body style='margin:0;padding:0;font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Roboto,\"Helvetica Neue\",Arial,sans-serif;background-color:#f5f5f5;'>
    <table width='100%' cellpadding='0' cellspacing='0' style='background-color:#f5f5f5;padding:40px 20px;'>
        <tr>
            <td align='center'>
                <table width='600' cellpadding='0' cellspacing='0' style='background:linear-gradient(135deg,#ff9a56 0%,#ff6a00 50%,#ff4500 100%);border-radius:16px;overflow:hidden;box-shadow:0 10px 40px rgba(255,106,0,0.3);'>
                    
                    <!-- Header -->
                    <tr>
                        <td style='padding:50px 40px;text-align:center;'>
                            <h1 style='margin:0;color:#ffffff;font-size:32px;font-weight:700;text-shadow:0 2px 10px rgba(0,0,0,0.2);'>
                                📧 Vérification email
                            </h1>
                        </td>
                    </tr>

                    <!-- Contenu -->
                    <tr>
                        <td style='background-color:#ffffff;padding:50px 40px;'>
                            <p style='margin:0 0 20px;color:#333;font-size:18px;line-height:1.6;'>
                                Bonjour <strong>$name</strong>,
                            </p>

                            <p style='margin:0 0 30px;color:#666;font-size:16px;line-height:1.6;'>
                                Merci pour votre inscription sur <strong>Logemangue</strong> 🎉  
                                Afin d'activer votre compte, veuillez confirmer votre adresse email en cliquant sur le bouton ci-dessous :
                            </p>

                            <!-- Bouton -->
                            <table width='100%' cellpadding='0' cellspacing='0'>
                                <tr>
                                    <td align='center' style='padding:10px 0 30px;'>
                                        <a href='$verificationLink' style='display:inline-block;background:linear-gradient(135deg,#ff9a56,#ff6a00);color:#ffffff;text-decoration:none;padding:16px 40px;border-radius:50px;font-size:16px;font-weight:600;box-shadow:0 4px 15px rgba(255,106,0,0.4);'>
                                            Vérifier mon email
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Info -->
                            <table width='100%' cellpadding='0' cellspacing='0' style='background-color:#fff5ed;border-left:4px solid #ff6a00;border-radius:8px;margin:20px 0;'>
                                <tr>
                                    <td style='padding:20px;'>
                                        <p style='margin:0;color:#d65a00;font-size:14px;line-height:1.5;'>
                                            🔐 <strong>Ce lien est personnel et sécurisé</strong><br>
                                            Il vous permet d'activer votre compte Logemangue.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p style='margin:0 0 10px;color:#999;font-size:14px;line-height:1.6;'>
                                Si le bouton ne fonctionne pas, copiez et collez ce lien dans votre navigateur :
                            </p>

                            <p style='margin:0 0 30px;color:#ff6a00;font-size:13px;word-break:break-all;'>
                                $verificationLink
                            </p>

                            <p style='margin:0;color:#999;font-size:14px;line-height:1.6;'>
                                Si vous n'êtes pas à l'origine de cette inscription, vous pouvez ignorer cet email en toute sécurité.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style='background-color:#2d2d2d;padding:30px 40px;text-align:center;'>
                            <p style='margin:0 0 10px;color:#ffffff;font-size:16px;font-weight:600;'>
                                Logemangue
                            </p>
                            <p style='margin:0;color:#999;font-size:13px;'>
                                © 2025 Logemangue. Tous droits réservés.
                            </p>
                        </td>
                    </tr>
                </table>

                <!-- Note -->
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


        header("Location: index?registered=1&verify=1");
        exit;

    } catch (Exception $e) {
        die("Erreur lors de l'inscription : " . $e->getMessage());
    }
}
?>