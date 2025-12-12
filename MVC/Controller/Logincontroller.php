<?php

class LoginController {

    private $model;

    public function __construct($model) {
        $this->model = $model;
    }

    public function login() {


        if (empty($_POST['email']) || empty($_POST['password'])) {
            return "missing_fields";
        }

        $email = trim($_POST['email']);
        $password = $_POST['password'];


        $user = $this->model->login($email);

        if (!$user) {
            return "wrong_email";
        }


        if (!password_verify($password, $user['password'])) {
            return "wrong_password";
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];

        return "success";
    }
}
