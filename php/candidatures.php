<?php
require_once __DIR__ . "/db2.php";

if (!isset($user["is_admin"]) || $user["is_admin"] != 1) {
    header("Location: index");
    exit();
}

require_once __DIR__ . "/../MVC/View/Candidatureview.php";

$controller = new CandidatureView($pdo);
$controller->manage();
exit;

