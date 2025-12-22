<?php
$db_host = 'sql310.infinityfree.com';
$db_name = 'if0_40739245_logemangue';
$db_user = 'if0_40739245';
$db_pass = 'jBSNfcgnL8';
$dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données: " . $e->getMessage());
}
?>
