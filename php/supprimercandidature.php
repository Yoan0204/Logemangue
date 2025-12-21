<?php
require "db2withoutlogin.php";
$etudiantId = $_POST['etudiant_id'] ?? null;
$logementId = $_POST['logement_id'] ?? null;
if ($etudiantId && $logementId) {
    $stmt = $conn->prepare("DELETE FROM reservation WHERE id_etudiant = ? AND id_logement = ?");
    $stmt->bind_param("ii", $etudiantId, $logementId);
    
    if ($stmt->execute()) {
        header("Location: logement?id=" . $logementId . "&success=candidature_deleted");
        exit();
    } else {
        echo "Erreur lors de la suppression de la candidature : " . $stmt->error;
    }
    
    $stmt->close();
} else {
    header("Location: logement?error=missing_parameters");
}
?>