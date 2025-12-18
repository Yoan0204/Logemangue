<?php

class FAQController {
    private $model;

    public function __construct($model) {
        $this->model = $model;
    }

    public function showFAQ() {
        $faqContent = $this->model->getFAQ();
        require_once __DIR__ . '/../View/FAQview.php';
        $view = new FAQView();
        $view->renderFAQ($faqContent);
    }
}