<?php
$db_host = 'localhost';
$db_name = 'logemangue';
$db_user = 'websiteadmin';
$db_pass = 'websiteadmin';
$dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données: " . $e->getMessage());
}
?>
<?php
$db_host = 'localhost';
$db_name = 'logemangue';
$db_user = 'websiteadmin';
$db_pass = 'websiteadmin';
$dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données: " . $e->getMessage());
}
?>
<!--  ON OBTIENT LES INFORMATIONS DE LUTILISATEUR DE LA SESSION ACTIVE -->
<?php
session_start();
$userId = $_SESSION['user_id'];
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header('Location: login.html');
    exit();
}
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
$sql = "SELECT nom, telephone, genre, date_naissance,is_admin, type_utilisateur, biography FROM users WHERE id = $userId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "Aucun utilisateur trouvé";
}
?>