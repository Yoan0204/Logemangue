<?php
require_once 'db2.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $token = bin2hex(random_bytes(32));
        $expires = date("Y-m-d H:i:s", time() + 3600);

        $stmt = $pdo->prepare(
            "UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?"
        );
        $stmt->execute([$token, $expires, $email]);

        $link = "http://localhost/PROJET-LOGEMANGUE/Logemangue/php/reset_password.php?token=$token";


        $message = "Lien de réinitialisation : <br><a href='$link'>$link</a>";
    } else {
        $message = "Aucun compte trouvé avec cet email.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mot de passe oublié</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="container">
    <h2>Mot de passe oublié</h2>

    <form method="post">
        <input type="email" name="email" placeholder="Votre email" required>
        <button type="submit" class="btn">Envoyer le lien</button>
    </form>

    <p><?= $message ?></p>
</div>
</body>
</html>