<?php

class CandidaturesModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getUserCandidatures($userId) {
        $stmt = $this->pdo->prepare("
            SELECT r.id, r.date_debut, r.date_fin, r.statut, r.montant, l.adresse, l.ville, l.code_postal, l.id AS logement_id
            FROM reservation r
            JOIN logement l on r.id_logement = l.id
            WHERE r.id_etudiant = :userId
            ORDER BY r.date_debut DESC
        ");
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
