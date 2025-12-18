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
        // Backward compatible: returns all user logements if limit/offset not provided
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

    // Récupère les logements d'un utilisateur avec pagination
    public function getUserLogementsPaginated($userId, $limit = 6, $offset = 0) {
        $sql = "SELECT l.*,
        (SELECT url_photo
         FROM photo
         WHERE photo.id_logement = l.ID
         ORDER BY id_photo ASC
         LIMIT 1) AS photo_url
        FROM logement l WHERE id_proprietaire = " . intval($userId);

        $sql .= " LIMIT " . intval($limit) . " OFFSET " . intval($offset);

        $result = $this->conn->query($sql);
        return $result;
    }

    // Compte les logements d'un utilisateur
    public function countUserLogements($userId) {
        $sql = "SELECT COUNT(*) as total FROM logement WHERE id_proprietaire = " . intval($userId);
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return intval($row['total']);
    }

    // Récupère les logements en attente (admin) avec pagination
    public function getWaitingLogements($limit = 6, $offset = 0) {
        $sql = "SELECT l.*,
        (SELECT url_photo
         FROM photo
         WHERE photo.id_logement = l.ID
         ORDER BY id_photo ASC
         LIMIT 1) AS photo_url
        FROM logement l
        WHERE l.status='Waiting'
        LIMIT " . intval($limit) . " OFFSET " . intval($offset);

        $result = $this->conn->query($sql);
        return $result;
    }

    // Compte les logements en attente
    public function countWaitingLogements() {
        $sql = "SELECT COUNT(*) as total FROM logement WHERE status = 'Waiting'";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return intval($row['total']);
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
    public function getFilteredLogements($filters = [], $limit = 5, $offset = 0) {
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
    
    // Ajout de la pagination
    $sql .= " LIMIT " . intval($limit) . " OFFSET " . intval($offset);
    
    $result = $this->conn->query($sql);
    return $result;
}

// Méthode pour compter le nombre total de logements (utile pour savoir s'il y en a encore)
public function countFilteredLogements($filters = []) {
    $sql = "SELECT COUNT(*) as total
    FROM logement l
    WHERE l.status='Approved'";
    
    // Appliquer les mêmes filtres que dans getFilteredLogements
    if (!empty($filters['search'])) {
        $search = $this->conn->real_escape_string($filters['search']);
        $sql .= " AND (titre LIKE '%$search%' OR description LIKE '%$search%' OR ville LIKE '%$search%')";
    }
    
    if (!empty($filters['ville'])) {
        $ville = $this->conn->real_escape_string($filters['ville']);
        $sql .= " AND ville LIKE '%$ville%'";
    }
    
    if (!empty($filters['type'])) {
        $type = $this->conn->real_escape_string($filters['type']);
        $sql .= " AND type = '$type'";
    }
    
    if (!empty($filters['budget_max'])) {
        $budget = intval($filters['budget_max']);
        $sql .= " AND loyer <= $budget";
    }
    
    if (!empty($filters['date_dispo'])) {
        $date = $this->conn->real_escape_string($filters['date_dispo']);
        $sql .= " AND date_disponibilite >= '$date'";
    }
    
    if (!empty($filters['surface_min'])) {
        $surface = intval($filters['surface_min']);
        $sql .= " AND surface >= $surface";
    }
    
    if (isset($filters['meuble']) && $filters['meuble'] == '1') {
        $sql .= " AND meuble = 1";
    }
    
    if (isset($filters['coloc']) && $filters['coloc'] == '1') {
        $sql .= " AND colocation = 1";
    }
    
    if (!empty($filters['type_proprio'])) {
        $type = $this->conn->real_escape_string($filters['type_proprio']);
        $sql .= " AND type_proprietaire = '$type'";
    }
    
    if (!empty($filters['keywords'])) {
        $keywords = $this->conn->real_escape_string($filters['keywords']);
        $sql .= " AND (titre LIKE '%$keywords%' OR description LIKE '%$keywords%' OR tags LIKE '%$keywords%')";
    }
    
    if (!empty($filters['min_rating'])) {
        $rating = intval($filters['min_rating']);
        $sql .= " AND note >= $rating";
    }
    
    if (isset($filters['disponible']) && $filters['disponible'] != '') {
        $dispo = intval($filters['disponible']);
        $sql .= " AND disponible = $dispo";
    }
    
    $result = $this->conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'];
}
}
