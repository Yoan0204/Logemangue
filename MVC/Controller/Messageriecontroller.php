<?php

class MessagerieController {
    private $model;

    public function __construct($model) {
        $this->model = $model;
    }

    public function showMessagerie($userId = null) {
        $messages = $this->model->getMessages($userId);
        require_once __DIR__ . '/../View/Listemessagerie';
        $view = new ListeMessagerie();
        $view->render($messages);
    }
}