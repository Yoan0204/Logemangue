<?php

class CandidatureModel {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getCandidatures($userId) {

        $stmt = $this->pdo->prepare("SELECT r.id, r.date_debut, r.date_fin, r.statut, r.montant, l.adresse, l.ville, l.code_postal, l.id AS logement_id
            FROM reservation r
            JOIN logement l on r.id_logement = l.id
            WHERE r.id_etudiant = ?
            ORDER BY r.date_debut DESC
        ");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $candidatures = [];
        while ($row = $result->fetch_assoc()) {
            $candidatures[] = $row;
        }

        $stmt->close();
        return $candidatures;
    }
}