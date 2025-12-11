<?php
class ForgotPasswordView {
    public function render($message = null) {
        ?>
        <h2>Mot de passe oublié ?</h2>

        <?php if($message): ?>
            <p style="color:green;"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <form method="POST" action="index.php?action=forgot">
            <label>Email :</label>
            <input type="email" name="email" required><br><br>
            <button type="submit">Envoyer un lien de réinitialisation</button>
        </form>

        <p><a href="index.php?action=login">Retour à la connexion</a></p>
        <?php
    }
}