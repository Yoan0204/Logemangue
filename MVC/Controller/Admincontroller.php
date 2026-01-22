<?php

require_once __DIR__ . '/../Model/Adminmodel.php';
require_once __DIR__ . '/../View/Adminview.php';

class Admincontroller {
    private $model;
    private $view;

    public function __construct($pdo) {
        $this->model = new AdminModel($pdo);
        $this->view = new AdminView();
    }

    public function index() {
        // Auth check is performed in the bootstrap file (php/admin.php)

        // Handle approve POST
        if (isset($_POST['approve'])) {
            $id = (int)($_POST['logement_id'] ?? 0);
            if ($id > 0) {
                $this->model->approveLogement($id);
                // Redirect to avoid form resubmission preserving params
                $redirect = 'admin.php';
                $params = [];
                if (!empty($_GET['q'])) $params['q'] = $_GET['q'];
                if (!empty($_GET['page'])) $params['page'] = (int)$_GET['page'];
                $params['message'] = 'approved';
                if (!empty($params)) $redirect .= '?' . http_build_query($params);
                header('Location: ' . $redirect);
                exit();
            }
        }

        //Handle delete POST
        if (isset($_POST['totaldelete'])) {
            $id = (int)($_POST['logement_id'] ?? 0);
            if ($id > 0) {
                $this->model->totalDeleteLogement($id);
                // Redirect to avoid form resubmission preserving params
                $redirect = 'admin.php';
                $params = [];
                if (!empty($_GET['q'])) $params['q'] = $_GET['q'];
                if (!empty($_GET['page'])) $params['page'] = (int)$_GET['page'];
                $params['message'] = 'deleted';
                if (!empty($params)) $redirect .= '?' . http_build_query($params);
                header('Location: ' . $redirect);
                exit();
            }
        }

        $q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $perPage = 6;
        $offset = ($page - 1) * $perPage;

        $totalRows = $this->model->countWaitingLogements($q);
        $totalPages = (int)ceil($totalRows / $perPage);
        $logements = $this->model->getWaitingLogements($q, $perPage, $offset);

        $newUsers = $this->model->countNewUsersLast7Days();
        $totalLogements = $this->model->countTotalLogements();
        $userTypes = $this->model->getUserTypeCounts();

        // Prepare user type counts
        $proprietaires = 0; $etudiants = 0; $organismes = 0; $totalUsers = 0;
        foreach ($userTypes as $type) {
            $count = (int)$type['count'];
            $totalUsers += $count;
            $t = strtolower($type['type_utilisateur']);
            if ($t === 'proprietaire') $proprietaires = $count;
            elseif ($t === 'etudiant') $etudiants = $count;
            elseif ($t === 'organisme') $organismes = $count;
        }

        $data = [
            'q' => $q,
            'page' => $page,
            'perPage' => $perPage,
            'totalPages' => $totalPages,
            'logements' => $logements,
            'newUsers' => $newUsers,
            'totalLogements' => $totalLogements,
            'totalUsers' => $totalUsers,
            'proprietaires' => $proprietaires,
            'etudiants' => $etudiants,
            'organismes' => $organismes
        ];

        $this->view->renderDashboard($data);
    }
}
