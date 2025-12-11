<<<<<<< HEAD
<?php
class LoginView {
    public function render($error = null) {
        include 'MVC/View/login.php';
    }
}

=======
<!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Logemangue</title>
            <p>Votre espace étudiant logement</p>
            <link rel="stylesheet" href="../css/style.css">
        </head>
        <body>
            <h2>Connexion</h2>

            <?php if ($error): ?>
                <p style="color:red;"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <form method="POST" action="index.php?action=login">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required><br><br>

                <label for="password">Mot de passe:</label>
                <input type="password" id="password" name="password" required><br><br>

                <button type="submit">Se connecter</button>
            </form>

            <p>Pas encore inscrit ? <a href="register.php">Créer un compte</a></p>
        </body>
        </html>
>>>>>>> 61e45a06a142d935d6e353e4811358af3b752e45
