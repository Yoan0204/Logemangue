<?php
class MessagerieModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getConversations(int $userId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT DISTINCT 
                u.id,
                u.nom,

                (
                    SELECT contenu
                    FROM message 
                    WHERE 
                        (id_expediteur = :userId AND id_destinataire = u.id)
                        OR 
                        (id_expediteur = u.id AND id_destinataire = :userId)
                    ORDER BY date_envoi DESC 
                    LIMIT 1
                ) AS dernier_message,

                (
                    SELECT COUNT(*)
                    FROM message
                    WHERE id_expediteur = u.id
                      AND id_destinataire = :userId
                      AND lu = 0
                ) AS non_lus

            FROM users u
            INNER JOIN message m 
                ON (m.id_expediteur = u.id OR m.id_destinataire = u.id)
            WHERE :userId IN (m.id_expediteur, m.id_destinataire)
              AND u.id != :userId
            ORDER BY u.nom
        ");

        $stmt->execute(['userId' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
