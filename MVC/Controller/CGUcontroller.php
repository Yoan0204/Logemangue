<?php

class CGUController {
    private $model;

    public function __construct($model) {
        $this->model = $model;
    }

    public function showCGU() {
        $cguContent = $this->model->cgu();
        require_once __DIR__ . '/../View/CGUview.php';
        $view = new CGUView();
        $view->renderCGU($cguContent);
    }
}