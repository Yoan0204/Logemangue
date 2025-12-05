<?php

class LogementModel {
    private $conn;
    private $pdo;

    public function __construct($conn, $pdo) {
        $this->conn = $conn;
        $this->pdo = $pdo;
    }

    /**
     * Récupère tous les logements approuvés
     */
    public function getApprovedLogements() {
        $sql = "SELECT * FROM logement WHERE status='Approved'";
        $result = $this->conn->query($sql);
        return $result;
    }

    /**
     * Récupère les logements de l'utilisateur connecté
     */
    public function getUserLogements($userId) {
        $sql = "SELECT * FROM logement WHERE id_proprietaire = " . intval($userId);
        $result = $this->conn->query($sql);
        return $result;
    }

    /**
     * Met à jour le statut d'un logement
     */
    public function updateLogementStatus($logementId, $status) {
        $sql = "UPDATE logement SET status = :status WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':id', $logementId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Supprime un logement (marque comme supprimé)
     */
    public function deleteLogement($logementId) {
        return $this->updateLogementStatus($logementId, 'Waiting');
    }

    /**
     * Récupère un logement par ID
     */
    public function getLogementById($logementId) {
        $sql = "SELECT * FROM logement WHERE id = " . intval($logementId);
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }
}
