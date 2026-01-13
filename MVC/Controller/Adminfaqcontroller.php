<?php
require_once __DIR__ . '/../Model/Adminmodel.php';
require_once __DIR__ . '/../View/AdminFAQview.php';

class Adminfaqcontroller {
    private $model;
    private $view;

    public function __construct($pdo) {
        $this->model = new AdminModel($pdo);
        $this->view = new AdminFAQview();
    }

    public function manage() {
        if (isset($_POST['add_faq'])) {
            $question = trim((string)($_POST['question'] ?? ''));
            $reponse = trim((string)($_POST['reponse'] ?? ''));
            if ($question !== '' && $reponse !== '') {
                $this->model->addFAQ($question, $reponse);
                header('Location: admin_faq.php');
                exit();
            }
        }

        if (isset($_POST['edit_faq'])) {
            $id = (int)($_POST['id'] ?? 0);
            $question = trim((string)($_POST['question'] ?? ''));
            $reponse = trim((string)($_POST['reponse'] ?? ''));
            if ($id > 0) {
                $this->model->editFAQ($id, $question, $reponse);
                header('Location: admin_faq.php');
                exit();
            }
        }

        if (isset($_POST['delete_faq'])) {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                $this->model->deleteFAQ($id);
                header('Location: admin_faq.php');
                exit();
            }
        }

        $faqs = $this->model->getFAQs();
        $this->view->render(['faqs' => $faqs]);
    }
}
