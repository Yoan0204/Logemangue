<?php

class Candidaturecontroller {
    private $Candidaturemodel;

    public function __construct($Candidaturemodel) {
        $this->Candidaturemodel = $Candidaturemodel;
    }

    public function showCandidatures() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: login');
            exit();
        }

        $userId = $_SESSION['user_id'];
        $candidatures = $this->candidatureModel->getCandidaturesByUserId($userId);
        $user = $this->userModel->getUserById($userId);

        $isEtudiant = $user['role'] === 'etudiant';
        $isAdmin = $user['role'] === 'admin';

        require_once 'MVC/View/Candidatureview.php';
    }
}