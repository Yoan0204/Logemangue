<?php
include "db2withoutlogin.php";
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'] ?? '';

$stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
$stmt->execute([$email]);
$exists = $stmt->fetchColumn() > 0;

echo json_encode([
    'exists' => $exists
]);
