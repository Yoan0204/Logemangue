<?php

class AdminUsersview {
    public function render(array $data) {
        $user = $GLOBALS['user'] ?? null;
        $userId = $GLOBALS['userId'] ?? null;

        $q = $data['q'] ?? '';
        $users = $data['users'] ?? [];
        ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="icon" type="image/x-icon" href="../png/icon.png" />
    <meta charset="UTF-8">
    <title>Admin – Gestion Utilisateurs</title>
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
                <a class="nav-link" href="admin_contact">Contact</a>

                <hr>

                <a class="nav-link" href="admin">Admin ⚙️</a>
                <a class="nav-link active-link" href="admin_users">Gestion utilisateurs</a>
                <a class="nav-link" href="admin_faq">Gestion FAQ</a>

                <a class="nav-link" href="profil">Mon profil</a>
            </nav>
        </div>
    </div>

    <!-- CONTENU -->
    <main class="flex-grow-1 p-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Gestion des utilisateurs</h1>

            <form method="get" class="d-flex">
                <input type="search"
                       name="q"
                       class="form-control me-2"
                       placeholder="Recherche (nom, email, type...)"
                       value="<?= htmlspecialchars($q) ?>">
                <button class="btn btn-outline-primary">Rechercher</button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th>Admin</th>
                    <th>Inscription</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>

                <?php if (count($users) === 0): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            Aucun utilisateur trouvé
                        </td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= htmlspecialchars($u['prenom'] . ' ' . $u['nom']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td>
                            <span class="badge bg-secondary">
                                <?= htmlspecialchars($u['type_utilisateur']) ?>
                            </span>
                        </td>
                        <td>
                            <?= $u['is_admin']
                                ? '<span class="badge bg-success">Oui</span>'
                                : '<span class="badge bg-danger">Non</span>' ?>
                        </td>
                        <td><?= date('d/m/Y', strtotime($u['created_at'])) ?></td>
                        <td class="d-flex gap-2">

                            <form method="post">
                                <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                <button class="btn btn-sm btn-midapproved"
                                        name="toggle_admin">
                                    <?= $u['is_admin'] ? 'Retirer admin' : 'Rendre admin' ?>
                                </button>
                            </form>

                            <?php if ($u['id'] != $userId): ?>
                                <form method="post"
                                      onsubmit="return confirm('Supprimer cet utilisateur ?');">
                                    <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                    <button class="btn btn-sm btn-okayed" name="delete_user">
                                        Supprimer
                                    </button>
                                </form>
                            <?php endif; ?>
                            <form method="post">
                                <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                <button class="btn btn-sm btn-unapproved"
                                        name="ban_user">
                                    <?= $u['banned'] ? 'Débannir' : 'Bannir' ?>
                                </button>
                            </form>                             
                                
                        </td>
                    </tr>
                <?php endforeach; ?>

                </tbody>
            </table>
        </div>

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
