<?php
class MessagerieModel {
	private $pdo;

	public function __construct($pdo) {
		$this->pdo = $pdo;
	}

	// Retourne les conversations/messages pour un utilisateur
	public function getMessages($userId = null) {
		if (!$userId) {
			return [];
		}

		$stmt = $this->pdo->prepare("SELECT m.*, u.nom as contact_nom FROM message m LEFT JOIN users u ON (u.id = IF(m.id_expediteur = :uid, m.id_destinataire, m.id_expediteur)) WHERE m.id_expediteur = :uid OR m.id_destinataire = :uid ORDER BY m.date_envoi DESC");
		$stmt->execute([':uid' => $userId]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
}
