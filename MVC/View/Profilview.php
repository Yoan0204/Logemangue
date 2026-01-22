<?php

class Profilview {
    public function renderProfile($profileData, $message = '') {
        ?>
        <!DOCTYPE html>
        <html lang="fr">
        <head>
    <link rel="icon" type="image/x-icon" href="../png/icon.png" />
           <meta charset="UTF-8">
            <title>Mon profil</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="../css/style.css">
        </head>
        <body>
            <h1>Profil</h1>
            <?php if ($message): ?>
                <p><?php echo htmlspecialchars_decode($message); ?></p>
            <?php endif; ?>
            <form method="POST" action="">
                <label for="nom">Nom:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars_decode($profileData['name']); ?>" required><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars_decode($profileData['email']); ?>" required><br>

                <label for="telephone">Telephone:</label>
                <input type="text" id="telephone" name="telephone" value="<?php echo htmlspecialchars_decode($profileData['telephone']); ?>" required><br>

                <label for="date_de_naissance">Date de naissance:</label>
                <input type="date" id="date_de_naissance" name="date_de_naissance" value="<?php echo htmlspecialchars_decode($profileData['date_de_naissance']); ?>" required><br>

                <label for="genre">Genre:</label>
                <select id="genre" name="genre" required>
                    <option value="masculin" <?php if ($profileData['genre'] == 'masculin') echo 'selected'; ?>>Masculin</option>
                    <option value="feminin" <?php if ($profileData['genre'] == 'feminin') echo 'selected'; ?>>Féminin</option>
                    <option value="autre" <?php if ($profileData['genre'] == 'autre') echo 'selected'; ?>>Autre</option>
                </select><br>

                <label for="type_utilisateur">Type d'utilisateur:</label>
                <select id="type_utilisateur" name="type_utilisateur" required>
                    <option value="etudiant" <?php if ($profileData['type_utilisateur'] == 'etudiant') echo 'selected'; ?>>Étudiant</option>
                    <option value="proprietaire" <?php if ($profileData['type_utilisateur'] == 'proprietaire') echo 'selected'; ?>>Propriétaire</option>
                    <option value="organisme" <?php if ($profileData['type_utilisateur'] == 'organisme') echo 'selected'; ?>>Organisme</option>
                </select><br>

                <button class="btn-login mt-3" type="submit">Mettre à jour</button>
            </form>
        </body>
        </html>
        <?php
    }
}