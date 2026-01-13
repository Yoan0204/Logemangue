<?php
require "db2.php";

if (!isset($user["is_admin"]) || $user["is_admin"] != 1) {
    header("Location: index");
    exit();
}

/* ➕ AJOUT FAQ */
if (isset($_POST['add_faq'])) {
    $stmt = $pdo->prepare("INSERT INTO faq (question, reponse) VALUES (:q, :r)");
    $stmt->execute([
        ':q' => $_POST['question'],
        ':r' => $_POST['reponse']
    ]);
}

/* ✏️ MODIFICATION FAQ */
if (isset($_POST['edit_faq'])) {
    $stmt = $pdo->prepare("
        UPDATE faq 
        SET question = :q, reponse = :r 
        WHERE id_faq = :id
    ");
    $stmt->execute([
        ':q' => $_POST['question'],
        ':r' => $_POST['reponse'],
        ':id' => $_POST['id']
    ]);
}

/* 🗑️ SUPPRESSION FAQ */
if (isset($_POST['delete_faq'])) {
    $stmt = $pdo->prepare("DELETE FROM faq WHERE id_faq = :id");
    $stmt->execute([':id' => $_POST['id']]);
}

/* 📋 LISTE FAQ */
$faqs = $pdo->query("SELECT * FROM faq ORDER BY id_faq DESC")->fetchAll(PDO::FETCH_ASSOC);
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

                <a class="nav-link" href="faq.php">FAQ</a>
                <a class="nav-link" href="#">Contact</a>

                <hr>

                <?php if (isset($user["is_admin"]) && $user["is_admin"] == 1): ?>
                    <a class="nav-link" href="admin.php">Dashboard Admin ⚙️</a>
                    <a class="nav-link active-link" href="admin_faq.php">Gestion FAQ ❓</a>
                <?php endif; ?>

                <a class="nav-link" href="profil">Mon profil</a>
                <a class="nav-link" href="login.html">Connexion</a>
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
                    <button class="btn btn-success" name="add_faq">Ajouter</button>
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
                            <button class="btn btn-primary btn-sm" name="edit_faq">Modifier</button>
                            <button class="btn btn-danger btn-sm"
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
