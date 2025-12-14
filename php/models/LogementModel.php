<?php

class LogementModel {
    private $conn;
    private $pdo;

    public function __construct($conn, $pdo) {
        $this->conn = $conn;
        $this->pdo = $pdo;
    }

    //Récupère tous les logements approuvés
    public function getApprovedLogements() {
        $sql = "SELECT l.*,
        (SELECT url_photo FROM photo 
         WHERE photo.id_logement = l.ID 
         ORDER BY id_photo ASC LIMIT 1) AS photo_url
        FROM logement l
        WHERE l.status='Approved'";

        $result = $this->conn->query($sql);
        return $result;
    }

    //Récupère les logements de l'utilisateur connecté
    public function getUserLogements($userId) {
        $sql = "SELECT l.*,
        (SELECT url_photo
         FROM photo
         WHERE photo.id_logement = l.ID
         ORDER BY id_photo ASC
         LIMIT 1) AS photo_url
        FROM logement l WHERE id_proprietaire = " . intval($userId);
        $result = $this->conn->query($sql);
        return $result;
    }

    //Met à jour le statut d'un logement
    public function updateLogementStatus($logementId, $status) {
        $sql = "UPDATE logement SET status = :status WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':id', $logementId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    //Supprime un logement (marque comme supprimé)
    public function deleteLogement($logementId) {
        return $this->updateLogementStatus($logementId, 'Waiting');
    }

    //Récupère un logement par ID
    public function getLogementById($logementId) {
        $sql = "SELECT * FROM logement WHERE id = " . intval($logementId);
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }

    //Récupère les logements approuvés avec filtres avancés
    public function getFilteredLogements($filters = []) {
        $sql = "SELECT l.*,
        (SELECT url_photo
         FROM photo
         WHERE photo.id_logement = l.ID
         ORDER BY id_photo ASC
         LIMIT 1) AS photo_url
        FROM logement l
        WHERE l.status='Approved'";

        
        // Filtre de recherche par titre ou description
        if (!empty($filters['search'])) {
            $search = $this->conn->real_escape_string($filters['search']);
            $sql .= " AND (titre LIKE '%$search%' OR description LIKE '%$search%' OR ville LIKE '%$search%')";
        }
        
        // Filtre par ville
        if (!empty($filters['ville'])) {
            $ville = $this->conn->real_escape_string($filters['ville']);
            $sql .= " AND ville LIKE '%$ville%'";
        }
        
        // Filtre par type
        if (!empty($filters['type'])) {
            $type = $this->conn->real_escape_string($filters['type']);
            $sql .= " AND type = '$type'";
        }
        
        // Filtre par budget max
        if (!empty($filters['budget_max'])) {
            $budget = intval($filters['budget_max']);
            $sql .= " AND loyer <= $budget";
        }
        
        // Filtre par date de disponibilité
        if (!empty($filters['date_dispo'])) {
            $date = $this->conn->real_escape_string($filters['date_dispo']);
            $sql .= " AND date_disponibilite >= '$date'";
        }
        
        // Filtre par surface min
        if (!empty($filters['surface_min'])) {
            $surface = intval($filters['surface_min']);
            $sql .= " AND surface >= $surface";
        }
        
        // Filtre meublé
        if (isset($filters['meuble']) && $filters['meuble'] == '1') {
            $sql .= " AND meuble = 1";
        }
        
        // Filtre colocation
        if (isset($filters['coloc']) && $filters['coloc'] == '1') {
            $sql .= " AND colocation = 1";
        }
        
        // Filtre par type de propriétaire
        if (!empty($filters['type_proprio'])) {
            $type = $this->conn->real_escape_string($filters['type_proprio']);
            $sql .= " AND type_proprietaire = '$type'";
        }
        
        // Filtre par mots-clés
        if (!empty($filters['keywords'])) {
            $keywords = $this->conn->real_escape_string($filters['keywords']);
            $sql .= " AND (titre LIKE '%$keywords%' OR description LIKE '%$keywords%' OR tags LIKE '%$keywords%')";
        }
        
        // Filtre par note minimale
        if (!empty($filters['min_rating'])) {
            $rating = intval($filters['min_rating']);
            $sql .= " AND note >= $rating";
        }
        
        // Filtre par disponibilité
        if (isset($filters['disponible']) && $filters['disponible'] != '') {
            $dispo = intval($filters['disponible']);
            $sql .= " AND disponible = $dispo";
        }
        
        $result = $this->conn->query($sql);
        return $result;
    }
}
