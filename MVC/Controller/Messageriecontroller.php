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

        require __DIR__ . '/../View/ListeMessagerie.php';
    }
}
