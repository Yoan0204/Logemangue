<?php
require "db2.php";

if (isset($_POST["resend"])) {
    $email = trim($_POST["email"]);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: resend_verification?erreur=email_invalide");
        exit();
    }

    // Vérifier si l'email existe et n'est pas vérifié
    $checkSql = "SELECT id, nom FROM users WHERE email = ? AND email_verified = 0";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Générer un nouveau token
        $verification_token = bin2hex(random_bytes(32));
        $token_expiry = date('Y-m-d H:i:s', strtotime('+24 hours'));

        // Mettre à jour le token
        $updateSql = "UPDATE users SET verification_token = ?, token_expiry = ? WHERE id = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("ssi", $verification_token, $token_expiry, $user['id']);
        $stmt->execute();

        // Envoyer l'email
        $verification_link = "https://votresite.com/verify_email.php?token=" . $verification_token;
        
        $subject = "Nouvelle demande de vérification - Logemangue";
        $message = "
        <html>
        <head>
    <link rel="icon" type="image/x-icon" href="../png/icon.png" />
            <style>
                body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
                .container { background: white; max-width: 600px; margin: 0 auto; padding: 30px; border-radius: 10px; }
                .btn { display: inline-block; padding: 15px 40px; background: #ffa500; color: white; text-decoration: none; border-radius: 8px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h2>Nouvelle demande de vérification</h2>
                <p>Bonjour " . htmlspecialchars($user['nom']) . ",</p>
                <p>Vous avez demandé un nouveau lien de vérification.</p>
                <a href='" . $verification_link . "' class='btn'>Vérifier mon email</a>
                <p style='margin-top: 20px;'>Ce lien est valable pendant 24 heures.</p>
            </div>
        </body>
        </html>
        ";

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: noreply@logemangue.com" . "\r\n";

        mail($email, $subject, $message, $headers);
        header("Location: resend_verification?success=envoye");
    } else {
        header("Location: resend_verification?erreur=compte_non_trouve");
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="icon" type="image/x-icon" href="../png/icon.png" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Renvoyer la vérification - Logemangue</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .resend-container {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 90%;
        }
        .logo {
            text-align: center;
            font-size: 32px;
            font-weight: bold;
            background: linear-gradient(135deg, #ffd700, #ffa500);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 30px;
        }
        .btn-resend {
            background: linear-gradient(135deg, #ffd700, #ffa500);
            border: none;
            color: white;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="resend-container">
        <div class="logo">🏠 Logemangue</div>
        <h2 class="text-center mb-4">Renvoyer l'email de vérification</h2>

        <?php if (isset($_GET["success"])): ?>
            <div class="alert alert-success">Un nouvel email de vérification a été envoyé.</div>
        <?php endif; ?>

        <?php if (isset($_GET["erreur"])): ?>
            <div class="alert alert-danger">
                <?php if ($_GET["erreur"] == "email_invalide"): ?>
                    Email invalide.
                <?php elseif ($_GET["erreur"] == "compte_non_trouve"): ?>
                    Aucun compte non vérifié trouvé avec cet email.
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="">
            <div class="mb-3">
                <label for="email" class="form-label">Adresse email</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>

            <button type="submit" name="resend" class="btn-resend">Renvoyer</button>

            <p class="text-center mt-3">
                <a href="login" style="color: #ffa500;">Retour à la connexion</a>
            </p>
        </form>
    </div>
</body>
</html>