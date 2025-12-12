<?php
session_start();

// Connexion à la base
require_once 'db2.php'; 

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $newPassword = trim($_POST['new_password']);
    $confirmPassword = trim($_POST['confirm_password']);

    if ($email === '' || $newPassword === '' || $confirmPassword === '') {
        $error = "Veuillez remplir tous les champs.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Adresse email invalide.";
    } elseif ($newPassword !== $confirmPassword) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {

        // Vérification utilisateur
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $error = "Aucun compte trouvé avec cette adresse email.";
        } else {

            // MAJ du mot de passe
            $update = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update->execute([$newPassword, $user['id']]);

            $success = "Votre mot de passe a été mis à jour avec succès.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mot de passe oublié</title>

    <!-- Style de la page -->
    <link rel="stylesheet" href="../css/style.css">

    <style>
     .forgot-container {
    width: 420px;
    margin: 80px auto;
    background: #FFFFFF;
    padding: 30px;
    border-radius: 18px;
    box-shadow: 5px 5px 0px #F5EEDC;
    border: 3px solid #FFB74D; /* bordeau orange clair */
    font-family: "Arial", sans-serif;
}

.forgot-container h2 {
    text-align: center;
    margin-bottom: 25px;
    font-size: 1.6rem;
    font-weight: bold;
    background: linear-gradient(90deg, #FFEB3B, #FF9800, #FF5722);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.forgot-container .form-group {
    margin-bottom: 18px;
}

.forgot-container label {
    font-weight: bold;
    color: #2E7D32;  /* vert mango */
    margin-bottom: 6px;
    display: block;
}

.forgot-container input {
    width: 100%;
    padding: 10px;
    margin-top: 6px;
    border-radius: 12px;
    border: 2px solid #FFC76A;
    background: #FFFDF7;
    transition: 0.2s ease-in-out;
}

.forgot-container input:focus {
    border-color: #FF9800;
    outline: none;
    box-shadow: 0 0 5px #FFB74D;
}

.forgot-container button {
    width: 100%;
    padding: 12px;
    margin-top: 10px;
    border: none;
    border-radius: 14px;
    background: linear-gradient(90deg, #FF9800, #FF5722);
    color: white;
    font-size: 1rem;
    font-weight: bold;
    cursor: pointer;
    box-shadow: 4px 4px 0px #F5EEDC;
    transition: 0.25s;
}

.forgot-container button:hover {
    transform: translateY(-2px);
    box-shadow: 6px 6px 0px #F5EEDC;
}

.message {
    margin-top: 15px;
    text-align: center;
    font-size: 14px;
    padding: 8px 12px;
    border-radius: 12px;
}

.error {
    color: #C62828;
    background: #FFEBEE;
    border: 2px solid #FFCDD2;
}

.success {
    color: #2E7D32;
    background: #E8F5E9;
    border: 2px solid #C8E6C9;
}

.back-link {
    margin-top: 20px;
    text-align: center;
}

.back-link a {
    color: #FF5722;
    font-weight: bold;
    text-decoration: none;
    transition: 0.2s;
}

.back-link a:hover {
    text-decoration: underline;
}