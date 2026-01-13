<?php

class FAQModel {

private $pdo;
	public function __construct($pdo) {
		$this->pdo = $pdo;
	}
    public function getFAQ() {
        try {
            $query = "SELECT question, reponse FROM faq ORDER BY id_faq";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo "Erreur de récupération des FAQ : " . $e->getMessage();
            return [];
        }
    }
}