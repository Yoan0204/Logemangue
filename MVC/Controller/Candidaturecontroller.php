<?php

class Candidaturecontroller {
    private $model;

    public function __construct($model) {
        $this->model = $model;
    }

    public function showCandidatures($userId) {
        $candidatures = $this->model->getCandidatures($userId);
        require_once __DIR__ . '/../View/Candidatureview.php';
        $view = new CandidatureView();
        $view->render($candidatures);
    }
}