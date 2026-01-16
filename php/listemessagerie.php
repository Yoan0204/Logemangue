<?php
   
require_once 'db2.php';
require_once '../MVC/Controller/Messageriecontroller.php';

$controller = new MessagerieController($pdo);
$controller->liste();
