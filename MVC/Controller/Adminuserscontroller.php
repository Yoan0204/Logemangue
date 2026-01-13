<?php
require_once __DIR__ . '/../Model/Adminmodel.php';
require_once __DIR__ . '/../View/AdminUsersview.php';

class Adminuserscontroller {
    private $model;
    private $view;

    public function __construct($pdo) {
        $this->model = new AdminModel($pdo);
        $this->view = new AdminUsersview();
    }

    public function list() {
        // Handle toggle admin
        if (isset($_POST['toggle_admin'])) {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                $this->model->toggleAdmin($id);
                header('Location: admin_users.php');
                exit();
            }
        }

        // Handle delete user
        if (isset($_POST['delete_user'])) {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                $this->model->deleteUser($id);
                header('Location: admin_users.php');
                exit();
            }
        }

        $q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
        $users = $this->model->searchUsers($q);

        $data = [ 'q' => $q, 'users' => $users ];
        $this->view->render($data);
    }
}
