<?php
$db_host = 'localhost';
$db_name = 'logemangue';
$db_user = 'websiteadmin';
$db_pass = 'websiteadmin';
$dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
try {
    $pdo = new PDO($dsn, $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données: " . $e->getMessage());
}

if (!isset($_SESSION['user_id'])) {
    session_start();
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
            $sql = "SELECT nom, telephone, genre, date_naissance,is_admin, type_utilisateur, biography FROM users WHERE id = $userId";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
            } else {
                echo "Aucun utilisateur trouvé";
            }
    }
}
?>