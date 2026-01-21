<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && $user['email_verifie'] == 0) {
            header("Location: login.html?erreur=2");
            exit;
        }
        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['logged_in'] = true;
            header("Location: index");
        } else {
            header("Location: login.html?erreur=1");
        }
        if ($_SESSION["banned"] == 1 || $user['banned'] == 1) {
        //Déconnecter l'utilisateur
        session_unset();
        session_destroy();
        //Rediriger vers la page de login avec message d'erreur
        header('Location: login.html?banned=1');
        exit();
    }
    } catch (PDOException $e) {
        die("Erreur lors de la connexion: " . $e->getMessage());
    }
}
?>
