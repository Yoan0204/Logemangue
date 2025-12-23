<?php 

class Profilcontroller {
    private $model;

    public function __construct($model) {
        $this->model = $model;
    }

    public function viewProfile($userId) {
        return $this->model->getUserProfile($userId);
    }

    public function updateProfile($userId, $data) {
        if (empty($data['nom']) || empty($data['email']) || empty($data['telephone']) || empty($data['date_de_naissance']) || empty($data['genre']) || empty($data['type_utilisateur'])) {
            return "missing_fields";
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return "invalid_email";
        }

        if ($this->model->updateUserProfile($userId, $data)) {
            return "success";
        } else {
            return "update_failed";
        }
    }
}