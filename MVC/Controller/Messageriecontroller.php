<?php

class MessagerieController {
    private $model;

    public function __construct($model) {
        $this->model = $model;
    }

    public function showMessagerie() {
        $messages = $this->model->getMessages();
        include 'MVC/View/ListeMessagerie.php';
    }
}