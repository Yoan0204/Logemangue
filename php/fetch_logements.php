<?php
// Endpoint AJAX pour charger plus de logements
header('Content-Type: application/json; charset=utf-8');
require_once 'db2withoutlogin.php';
require 'controllers/LogementController.php';
require 'models/LogementModel.php';

$model = new LogementModel($conn, $pdo);
$controller = new LogementController($model, __DIR__ . '/views/');

// Le contrôleur lit les paramètres GET (search, filters, limit, offset)
$searchResult = $controller->getFilteredSearchLogements();
$logements = $searchResult['logements'];
$limit = intval($searchResult['limit']);
$offset = intval($searchResult['offset']);
$total = intval($searchResult['total']);

$html = '';
$added = 0;
if ($logements && $logements->num_rows > 0) {
    while ($row = $logements->fetch_assoc()) {
        $added++;
        $photo = !empty($row['photo_url']) ? htmlspecialchars($row['photo_url']) : 'placeholder.jpg';
        $titre = htmlspecialchars($row['titre']);
        $loyer = htmlspecialchars($row['loyer']);
        $ville = htmlspecialchars($row['ville']);
        $disponible = ($row['disponible'] == 1) ? 'Oui' : 'Non';
        $surface = htmlspecialchars($row['surface']);
        $type = htmlspecialchars($row['TYPE']);
        $note = htmlspecialchars($row['note'] ?? 'N/A');

        // Construire le HTML pour chaque carte, identique à la vue
        $html .= '<div class="col-md-4">';
        $html .= '<a href="logement?id=' . intval($row['ID']) . '" class="logement-link">';
        $html .= '<div class="logement-card">';
        $html .= '<img src="' . $photo . '" alt="' . $titre . '">';
        $html .= '<div class="info">';
        $html .= '<h6 class="fw-bold mb-1">' . $titre . '</h6>';
        $html .= '<p class="text-muted mb-0">' . $loyer . ' € / mois</p>';
        $html .= '<p class="small text-muted mb-0">' . $ville . '</p>';
        $html .= '<p class="small text-muted mb-0">Disponible : ' . $disponible . '</p>';
        $html .= '<p class="small text-muted mb-0">' . $surface . ' m² - ' . $type . '</p>';
        $html .= '<p class="small text-muted">Note : ' . $note . ' ★</p>';

        // Bouton visible seulement pour l'admin
        if (isset($isAdmin) && $isAdmin == 1) {
            $html .= '<form method="post">';
            $html .= '<input type="hidden" name="logement_id" value="' . intval($row['ID']) . '">';
            $html .= '<button type="submit" class="btn-unapproved" name="delete">Supprimer</button>';
            $html .= '</form>';
        }

        $html .= '</div></div></a></div>';
    }
}

$nextOffset = $offset + $added;
$hasMore = $nextOffset < $total;

echo json_encode([
    'success' => true,
    'html' => $html,
    'added' => $added,
    'nextOffset' => $nextOffset,
    'hasMore' => $hasMore,
    'total' => $total
]);

exit;
