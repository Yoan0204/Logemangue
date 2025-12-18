<?php
class RegisterView {
    public function render($error = null) {
        ?>
        <h2>Inscription</h2>

        <?php if($error): ?>
            <p style="color:red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" action="index.php?action=register">
            <label>Email :</label>
            <input type="email" name="email" required><br><br>

            <label>Mot de passe :</label>
            <input type="password" name="password" required><br><br>

            <label>Confirmer le mot de passe :</label>
            <input type="password" name="confirm_password" required><br><br>

            <button type="submit">S'inscrire</button>
        </form>

        <p>Déjà un compte ? <a href="/login">Se connecter</a></p>
        <?php
    }
}