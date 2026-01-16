<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $name = $_POST['name'];
    $name = trim($name);
    $phone = $_POST['phone'];
    $genre = $_POST['genre'];
    $birthdate = $_POST['birthdate_value'];
    $type_utilisateur = $_POST['type_utilisateur'];

if (!preg_match('/^[a-zA-Z0-9 _-]+$/', $name)) {
    header("Location: login.html?error=carun");
    die();
}



     // Validation du numéro de téléphone


    if ($password !== $confirm_password) {
        die("Les mots de passe ne correspondent pas.");
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (email, password, nom, telephone, genre, date_naissance, type_utilisateur) VALUES (?, ? , ?,?,?,?,?)");
        $stmt->execute([$email, $hashed_password, $name, $phone, $genre, $birthdate, $type_utilisateur]);
        header("Location: index?registered=1");
    } catch (PDOException $e) {
        die("Erreur lors de l'inscription: " . $e->getMessage());
    }
}
        
?>
