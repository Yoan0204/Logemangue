<?php

class RegisterController {

    private $model;

    public function __construct($model) {
        $this->model = $model;
    }

    public function register() {
        
        if (empty($_POST['email']) || empty($_POST['password']) || empty($_POST['confirm_password'])) {
            return "missing_fields";
        }

        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if ($password != $confirm_password) {
            return "password_mismatch";
        }

        if ($this->model->emailExists($email)) {
            return "email_exists";
        }

        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        if ($this->model->createUser($email, $hashed_password)) {
            return "success";
        } else {
            return "registration_failed";
        }
    }
}