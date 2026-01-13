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

    //Récupère les logements avec filtres avancés
    public function getFilteredSearchLogements() {
        $filters = [];
        
        // Récupérer les paramètres GET et les valider
        $filters['search'] = isset($_GET['search']) ? trim($_GET['search']) : '';
        $filters['ville'] = isset($_GET['ville']) ? trim($_GET['ville']) : '';
        $filters['type'] = isset($_GET['type']) ? trim($_GET['type']) : '';
        $filters['budget_max'] = isset($_GET['budget_max']) ? intval($_GET['budget_max']) : '';
        $filters['date_dispo'] = isset($_GET['date_dispo']) ? $_GET['date_dispo'] : '';
        $filters['surface_min'] = isset($_GET['surface_min']) ? intval($_GET['surface_min']) : '';
        $filters['meuble'] = isset($_GET['meuble']) ? $_GET['meuble'] : '';
        $filters['coloc'] = isset($_GET['coloc']) ? $_GET['coloc'] : '';
        $filters['type_proprio'] = isset($_GET['type_proprio']) ? trim($_GET['type_proprio']) : '';
        $filters['keywords'] = isset($_GET['keywords']) ? trim($_GET['keywords']) : '';
        $filters['min_rating'] = isset($_GET['min_rating']) ? intval($_GET['min_rating']) : '';
        $filters['disponible'] = isset($_GET['disponible']) ? $_GET['disponible'] : '';
        
        // Pagination: récupère limit et offset depuis les paramètres GET
        $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
        $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;

        $logements = $this->model->getFilteredLogements($filters, $limit, $offset);
        $total = $this->model->countFilteredLogements($filters);

        // Retourner un tableau contenant le jeu de résultats et les infos de pagination
        return [
            'logements' => $logements,
            'limit' => $limit,
            'offset' => $offset,
            'total' => $total
        ];
    }

    //Récupère les logements de l'utilisateur connecté
    public function getUserLogements($userId) {
        $logements = $this->model->getUserLogements($userId);
        return $logements;
    }

    // Récupère les logements de l'utilisateur avec pagination
    public function getUserLogementsPaginated($userId) {
        $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
        $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;

        $logements = $this->model->getUserLogementsPaginated($userId, $limit, $offset);
        $total = $this->model->countUserLogements($userId);

        return [
            'logements' => $logements,
            'limit' => $limit,
            'offset' => $offset,
            'total' => $total
        ];
    }

    // Récupère les logements en attente (admin) avec pagination
    public function getWaitingLogementsPaginated() {
        $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 6;
        $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;

        $logements = $this->model->getWaitingLogements($limit, $offset);
        $total = $this->model->countWaitingLogements();

        return [
            'logements' => $logements,
            'limit' => $limit,
            'offset' => $offset,
            'total' => $total
        ];
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
        return require $this->view . $view . '  ';
    }
}
