<?php

class FAQController {
    private $faqModel;

    public function __construct($faqModel) {
        $this->faqModel = $faqModel;
    }

    public function showFAQ() {
        $faqContent = $this->faqModel->getFAQ();
        include 'MVC/View/FAQview.php';
    }
}