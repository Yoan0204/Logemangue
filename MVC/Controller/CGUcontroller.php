<?php

class CGUController {
    private $cguModel;

    public function __construct($cguModel) {
        $this->cguModel = $cguModel;
    }

    public function showCGU() {
        $cguContent = $this->cguModel->cgu();
        include 'MVC/View/CGUview.php';
    }
}