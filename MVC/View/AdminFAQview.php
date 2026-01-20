<?php

class AdminFAQview {
    public function render(array $data) {
        $user = $GLOBALS['user'] ?? null;
        $userId = $GLOBALS['userId'] ?? null;

        $faqs = $data['faqs'] ?? [];
        ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin – Gestion FAQ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>

<body>

<button id="menu-toggle" class="hamburger">☰</button>

<div class="d-flex">

    <!-- SIDEBAR -->
    <div class="sidebar" id="sidebar">
        <div>
            <a href="index">
                <img class="sidebar-logo" src="../png/Aberent.png" alt="Logo">
            </a>

            <nav class="nav flex-column">
                <a class="nav-link" href="index">Accueil</a>
                <a class="nav-link" href="logements">Recherche</a>

                <hr>

                <?php if (
                    isset($user["type_utilisateur"]) &&
                    ($user["type_utilisateur"] == "Proprietaire" || $user["type_utilisateur"] == "Organisme")
                ): ?>
                    <a class="nav-link" href="publish">Publier une annonce</a>
                    <a class="nav-link" href="logements?view=mesannonces">Mes annonces</a>
                <?php endif; ?>

                <a class="nav-link" href="listemessagerie">Ma messagerie</a>

                <hr>

                <a class="nav-link" href="index?page=faq">FAQ</a>
                <a class="nav-link" href="#">Contact</a>

                <hr>

                <?php if (isset($user["is_admin"]) && $user["is_admin"] == 1): ?>
                    <a class="nav-link" href="admin.php">Admin ⚙️</a>
                <a class="nav-link" href="admin_users.php">Gestion utilisateurs</a>                    
                    <a class="nav-link active-link" href="admin_faq.php">Gestion FAQ</a>
                <?php endif; ?>

                <a class="nav-link" href="profil">Mon profil</a>
            </nav>
        </div>
    </div>

    <!-- CONTENU PRINCIPAL -->
    <main class="flex-grow-1 p-4">

        <h1 class="mb-4">Gestion des FAQ</h1>

        <!-- ➕ AJOUT FAQ -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="mb-3">Ajouter une FAQ</h5>
                <form method="post">
                    <input type="text" name="question" class="form-control mb-2" placeholder="Question" required>
                    <textarea name="reponse" class="form-control mb-2" placeholder="Réponse" rows="4" required></textarea>
                    <button class="btn btn-login" name="add_faq">Ajouter</button>
                </form>
            </div>
        </div>

        <!-- 📋 LISTE FAQ -->
        <?php if (count($faqs) === 0): ?>
            <p class="text-muted">Aucune FAQ enregistrée.</p>
        <?php endif; ?>

        <?php foreach ($faqs as $faq): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <form method="post">
                        <input type="hidden" name="id" value="<?= $faq['id_faq'] ?>">

                        <input type="text"
                               name="question"
                               class="form-control mb-2"
                               value="<?= htmlspecialchars($faq['question']) ?>">

                        <textarea name="reponse"
                                  class="form-control mb-2"
                                  rows="4"><?= htmlspecialchars($faq['reponse']) ?></textarea>

                        <div class="d-flex gap-2">
                            <button class="btn btn-approved btn-sm" name="edit_faq">Modifier</button>
                            <button class="btn btn-unapproved btn-sm"
                                    name="delete_faq"
                                    onclick="return confirm('Supprimer cette FAQ ?')">
                                Supprimer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>

    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const toggle = document.getElementById("menu-toggle");
    const sidebar = document.getElementById("sidebar");

    toggle.addEventListener("click", () => {
        sidebar.classList.toggle("active");
    });
</script>

</body>
</html>

<?php
    }
}
