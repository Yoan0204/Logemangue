<?php
include "db2withoutlogin.php";
$etudiantId = $_POST['etudiant_id'] ?? null;
echo $etudiantId;
$logementId = $_POST['logement_id'] ?? null;
$note = $_POST['note'] ?? null;
// vérifier que l'étudiant a une reservation approuvée pour ce logement
if ($etudiantId && $logementId && $note !== null) {
    $stmt = $conn->prepare("SELECT * FROM reservation WHERE id_etudiant = ? AND id_logement = ? AND statut = 'Approuvée'");
    $stmt->bind_param("ii", $etudiantId, $logementId);
    $stmt->execute();
    $result = $stmt->get_result();

// vérifir s'il a déja noté ce logement 
    $checkStmt = $conn->prepare("SELECT * FROM avis WHERE id_etudiant = ? AND id_logement = ?");
    $checkStmt->bind_param("ii", $etudiantId, $logementId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    if ($checkResult->num_rows > 0) {
        header("Location: logement?id=" . $logementId . "&error=already_rated");
        exit();
    }
    

    if ($result->num_rows > 0) {
        // insérer la note dans la table des notes
        $insertStmt = $conn->prepare("INSERT INTO avis (id_etudiant, id_logement, note) VALUES (?, ?, ?)");
        $insertStmt->bind_param("iii", $etudiantId, $logementId, $note);
        
        if ($insertStmt->execute()) {

        // Calcul de la moyenne
        $avgStmt = $conn->prepare("
            SELECT AVG(note) AS moyenne 
            FROM avis 
            WHERE id_logement = ?
        ");
        $avgStmt->bind_param("i", $logementId);
        $avgStmt->execute();
        $avgResult = $avgStmt->get_result();
        $row = $avgResult->fetch_assoc();
        $moyenne = $row['moyenne'];
        $avgStmt->close();

        // Mise à jour du logement
        $updateStmt = $conn->prepare("
            UPDATE logement 
            SET note = ? 
            WHERE id = ?
        ");
        $updateStmt->bind_param("di", $moyenne, $logementId);
        $updateStmt->execute();
        $updateStmt->close();

        header("Location: logement?id=" . $logementId . "&success=note_added");
        exit();
    }

 else {
            echo "Erreur lors de l'ajout de la note : " . $insertStmt->error;
        }
        
        $insertStmt->close();
    } else {
        header("Location: logement?id=" . $logementId . "&error=no_approved_reservation");
        exit();
    }
    
    $stmt->close();
} else {
    header("Location: logement?error=missing_parameters");
}
?>
