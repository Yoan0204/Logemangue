<?php
require 'db2withoutlogin.php'; // fichier qui crée $pdo

if (isset($_POST['id'])) {
    $id = (int) $_POST['id'];

    $stmt = $pdo->prepare("DELETE FROM contacts WHERE id = ?");
    if ($stmt->execute([$id])) {
        echo 'success';
    } else {
        echo 'error';
    }
}
