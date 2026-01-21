<?php

class AdminModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /* LOGEMENTS */
    public function approveLogement(int $id): bool {
        $stmt = $this->pdo->prepare("UPDATE logement SET status = 'Approved' WHERE id = :id AND status = 'Waiting'");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function countWaitingLogements(string $q = ''): int {
        $params = [];
        $searchClause = '';
        if ($q !== '') {
            $searchClause = " AND (l.titre LIKE :q OR l.description LIKE :q OR l.adresse LIKE :q)";
            $params[':q'] = "%$q%";
        }
        $sql = "SELECT COUNT(*) as total FROM logement l WHERE l.status = 'Waiting'" . $searchClause;
        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $k => $v) $stmt->bindValue($k, $v, PDO::PARAM_STR);
        $stmt->execute();
        return (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getWaitingLogements(string $q = '', int $limit = 6, int $offset = 0): array {
        $params = [];
        $searchClause = '';
        if ($q !== '') {
            $searchClause = " AND (l.titre LIKE :q OR l.description LIKE :q OR l.adresse LIKE :q)";
            $params[':q'] = "%$q%";
        }

        $sql = "SELECT l.*, (SELECT url_photo FROM photo WHERE photo.id_logement = l.ID ORDER BY id_photo ASC LIMIT 1) AS photo_url FROM logement l WHERE l.status = 'Waiting'" . $searchClause . " ORDER BY l.ID DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $k => $v) $stmt->bindValue($k, $v, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* STATS */
    public function countNewUsersLast7Days(): int {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
        $stmt->execute();
        return (int)$stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }

    public function countTotalLogements(): int {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM logement");
        $stmt->execute();
        return (int)$stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }

    public function getUserTypeCounts(): array {
        $stmt = $this->pdo->prepare("SELECT type_utilisateur, COUNT(*) as count FROM users GROUP BY type_utilisateur");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* USERS */
    public function searchUsers(string $q = '', int $limit = 50): array {
        $params = [];
        $where = '';
        if ($q !== '') {
            $where = "WHERE nom LIKE :q OR prenom LIKE :q OR email LIKE :q OR type_utilisateur LIKE :q";
            $params[':q'] = "%$q%";
        }

        $sql = "SELECT id, nom, prenom, email, type_utilisateur, is_admin, created_at, banned FROM users $where ORDER BY created_at DESC LIMIT :limit";
        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $k => $v) $stmt->bindValue($k, $v, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function toggleAdmin(int $id): bool {
        $stmt = $this->pdo->prepare("UPDATE users SET is_admin = NOT is_admin WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteUser(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function toggleBan(int $id): bool {
        // Update ban status
        $stmt = $this->pdo->prepare("UPDATE logement SET status = 'Waiting' WHERE id_proprietaire = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
        $stmt->execute();       
        $stmt = $this->pdo->prepare("UPDATE users SET banned = NOT banned WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /* FAQ */
    public function getFAQs(): array {
        $stmt = $this->pdo->prepare("SELECT * FROM faq ORDER BY id_faq DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addFAQ(string $q, string $r): bool {
        $stmt = $this->pdo->prepare("INSERT INTO faq (question, reponse) VALUES (:q, :r)");
        return $stmt->execute([':q' => $q, ':r' => $r]);
    }

    public function editFAQ(int $id, string $q, string $r): bool {
        $stmt = $this->pdo->prepare("UPDATE faq SET question = :q, reponse = :r WHERE id_faq = :id");
        return $stmt->execute([':q' => $q, ':r' => $r, ':id' => $id]);
    }

    public function deleteFAQ(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM faq WHERE id_faq = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
