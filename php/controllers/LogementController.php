<?php

class LogementController {
    private $model;
    private $view;

    public function __construct($model, $view) {
        $this->model = $model;
        $this->view = $view;
    }

    //Récupère les logements pour la page de recherche
    public function getSearchLogements() {
        $logements = $this->model->getApprovedLogements();
        return $logements;
    }

    //Récupère les logements de l'utilisateur connecté
    public function getUserLogements($userId) {
        $logements = $this->model->getUserLogements($userId);
        return $logements;
    }

    //Traite la suppression d'un logement
    public function handleDelete() {
        if (isset($_POST['delete']) && isset($_POST['logement_id'])) {
            $logementId = $_POST['logement_id'];
            $this->model->deleteLogement($logementId);
            return "Le logement a été marqué comme supprimé !";
        }
        return null;
    }

    //Affiche la vue appropriée (mes annonces ou recherche)
    public function render($view, $data = []) {
        return require $this->view . $view . '.php';
    }
}
