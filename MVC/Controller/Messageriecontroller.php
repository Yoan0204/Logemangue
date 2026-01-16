<?php
require_once __DIR__ . '/../Model/MessagerieModel.php';
require_once __DIR__ . '/../../php/db2.php';
class MessagerieController
{
    private MessagerieModel $model;

    public function __construct(PDO $pdo)
    {
        $this->model = new MessagerieModel($pdo);
    }

    public function liste(): void
    {
        // Variables issues de la session
        $userId     = $_SESSION['user_id'];

        $destinataires = $this->model->getConversations($userId);
        
        $isAdmin = isset($user['is_admin']) ? $user['is_admin'] : 1;   
        $isEtudiant = (isset($user['type_utilisateur']) && $user['type_utilisateur'] === 'Etudiant') ? true : false; 

        require __DIR__ . '/../View/ListeMessagerie.php';
    }
}
