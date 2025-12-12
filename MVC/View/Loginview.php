<?php
class LoginView {
    public function render($error = null) {
        // Use the standalone login template
        require_once __DIR__ . '/login.php';
    }
}
?>