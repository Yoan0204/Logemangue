<?php

class CGUController {
    private $model;

    public function __construct($model) {
        $this->model = $model;
    }

    public function showCGU() {
        $cguContent = $this->model->cgu();
        include 'MVC/View/CGUview.php';
    }
}