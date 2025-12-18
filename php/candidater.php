<?php 
include 'db2.php';
// Récupération de l'ID du logement depuis le formulaire
$logementId = $_POST['logement_id'] ?? null;

//Check si il y a déja une candidature en cours pour ce logement et cet étudiant
$stmt = $conn->prepare("SELECT * FROM reservation WHERE id_etudiant = ? AND id_logement = ? AND statut = 'en attente'");
$stmt->bind_param("ii", $userId, $logementId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    header("Location: logement.php?id=" . $logementId . "&error=candidature_exists");
    exit();
}
//Check si l'utilisateur est un étudiant
$stmt = $conn->prepare("SELECT type_utilisateur FROM users WHERE ID = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
if ($user['type_utilisateur'] !== 'etudiant') {
    header("Location: logement.php?id=" . $logementId . "&error=not_student");
    exit();
}
//Insertion dans la table candidatures
if ($logementId) {
    $stmt = $conn->prepare("INSERT INTO reservation (date_debut, date_fin, statut, montant, id_etudiant, id_logement) VALUES (NOW(), DATE_ADD(NOW(), INTERVAL 1 MONTH), 'en attente', (SELECT loyer FROM logement WHERE ID = ?), ?, ?)");
    $stmt->bind_param("iii", $logementId, $userId, $logementId);
    
    if ($stmt->execute()) {
        header("Location: logement.php?id=" . $logementId . "&success=candidature_submitted");
        exit();
    } else {
        echo "Erreur lors de l'envoi de la candidature : " . $stmt->error;
    }
    
    $stmt->close();
} else {
    header("Location: logement.php?error=missing_logement_id");
}

