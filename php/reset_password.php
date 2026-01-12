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
    die("Lien invalide ou expiré.");
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
    <title>Nouveau mot de passe</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <main
      style="font-family: Arial;"
      >
<div class="container">
    <h2>Nouveau mot de passe</h2>

    <form method="post">
        <input type="password" name="password" placeholder="Nouveau mot de passe" required>
        <button type="submit" class="btn">Changer</button>
    </form>

    <p><?= $message ?></p>
</div>
</body>
</html>