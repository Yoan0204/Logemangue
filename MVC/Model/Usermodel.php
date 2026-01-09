<?php

class UserModel {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function deleteUser($id) {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function createUser($email, $password) {


        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare("
            INSERT INTO users (email, password)
            VALUES (:email, :password)
        ");

        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $passwordHash, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function updateUserPassword($id, $newPassword) {

        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare("
            UPDATE users SET password = :password WHERE id = :id
        ");

        $stmt->bindParam(':password', $passwordHash, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function login($email, $password) {

        $stmt = $this->pdo->prepare("
            SELECT * FROM users WHERE email = :email
        ");

        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);


        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }
}