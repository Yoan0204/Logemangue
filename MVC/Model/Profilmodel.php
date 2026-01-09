<?php

class Profilmodel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getUserProfile($userId) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUserProfile($userId, $data) {
        $stmt = $this->pdo->prepare("UPDATE users SET nom = :nom, email = :email, telephone = :telephone, date_de_naissance = :date_de_naissance, genre = :genre, type_utilisateur = :type_utilisateur WHERE id = :id");
        $stmt->bindParam(':nom', $data['nom']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':id', $userId);
        $stmt->bindParam(':telephone', $data['telephone']);
        $stmt->bindParam(':date_de_naissance', $data['date_de_naissance']);
        $stmt->bindParam(':genre', $data['genre']);
        $stmt->bindParam(':type_utilisateur', $data['type_utilisateur']);
        return $stmt->execute();
    }
}
