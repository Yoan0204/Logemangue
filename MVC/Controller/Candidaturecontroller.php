<?php

class Candidaturecontroller {
    private $Candidaturemodel;

    public function __construct($Candidaturemodel) {
        $this->Candidaturemodel = $Candidaturemodel;
    }

    public function showCandidatures($userId) {
        $candidatures = $this->Candidaturemodel->getCandidatures($userId);
        require_once __DIR__ . '/../View/Candidatureview.php';
        $view = new CandidatureView();
        $view->render($candidatures);
    }
}