<?php
// Endpoint AJAX pour charger plus de logements
header('Content-Type: application/json; charset=utf-8');

try {
    require_once __DIR__ . '/db2withoutlogin.php';
    require_once __DIR__ . '/../MVC/Controller/LogementController.php';
    require_once __DIR__ . '/../MVC/Model/LogementModel.php';

    if (!isset($conn) || !isset($pdo)) {
        throw new Exception('Database connection failed');
    }

    $model = new LogementModel($conn, $pdo);
    $controller = new LogementController($model, __DIR__ . '/../MVC/View/');
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erreur serveur: ' . $e->getMessage()
    ]);
    exit;
}

// Le contrôleur lit les paramètres GET (search, filters, limit, offset)
$searchResult = $controller->getFilteredSearchLogements();
$logements = $searchResult['logements'];
$limit = intval($searchResult['limit']);
$offset = intval($searchResult['offset']);
$total = intval($searchResult['total']);

if (!$logements) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erreur lors de la récupération des logements'
    ]);
    exit;
}

$html = '';
$added = 0;
if ($logements->num_rows > 0) {
    while ($row = $logements->fetch_assoc()) {
        $added++;
        $photo = !empty($row['photo_url']) ? htmlspecialchars($row['photo_url']) : 'placeholder.jpg';
        $titre = htmlspecialchars_decode($row['titre']);
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
        $html .= '<p class="small text-muted mb-0">' . $surface . ' m² - ' . $type . '</p>';
        $html .= '<p class="small text-muted">Note : ' . $note . ' ★</p>';

        // Bouton visible seulement pour l'admin
        if (isset($isAdmin) && $isAdmin == 1) {
            $html .= '<form method="post" style="margin-top: 10px;">';
            $html .= '<input type="hidden" name="logement_id" value="' . intval($row['ID']) . '">';
            $html .= '<button type="submit" class="btn-unapproved" name="delete">Enlever l\'approbation</button>';
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
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

exit;
