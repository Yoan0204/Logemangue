<?php
require 'db.php';

if (!isset($_GET['token'])) {
    die("Token manquant.");
}

$token = $_GET['token'];

$stmt = $pdo->prepare("
    SELECT id FROM users 
    WHERE verification_token = ? AND email_verifie = 0
");
$stmt->execute([$token]);
$user = $stmt->fetch();

if (!$user) {
    die("Lien invalide ou déjà utilisé.");
}

$update = $pdo->prepare("
    UPDATE users 
    SET email_verifie = 1, verification_token = NULL
    WHERE id = ?
");
$update->execute([$user['id']]);

header ("Location: login.html?verified=1");