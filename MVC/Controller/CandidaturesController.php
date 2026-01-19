<?php

class CandidaturesController {
    private $model;

    public function __construct($model) {
        $this->model = $model;
    }

    public function showCandidatures($userId) {
        $candidatures = $this->model->getUserCandidatures($userId);
        return $candidatures;
    }
}
