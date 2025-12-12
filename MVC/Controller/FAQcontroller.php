<?php

class FAQController {
    private $model;

    public function __construct($model) {
        $this->model = $model;
    }

    public function showFAQ() {
        $faqContent = $this->model->getFAQ();
        include 'MVC/View/FAQview.php';
    }
}