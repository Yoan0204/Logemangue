<?php
// Inclure la connexion à la base de données
require_once 'db2.php';

// Récupérer toutes les demandes de contact
$query = "SELECT * FROM contacts ORDER BY date_creation DESC";
$result = mysqli_query($conn, $query);
$contacts = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="icon" type="image/x-icon" href="../png/icon.png" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Demandes de Contact</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/admin.css">
<style>
    :root {
        --gradient-main: linear-gradient(135deg, #ffcc00, #ff8c00);
        --orange: #ff8c00;
        --yellow: #ffcc00;
        --dark: #2b2b2b;
        --light-bg: #fffaf2;
        --border-soft: #f0e2c6;
    }

    h1 {
        font-weight: 700;
        margin-bottom: 30px;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* TABLE CARD */
    .table-wrapper {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background: var(--gradient-main);
        color: white;
    }

    th {
        padding: 16px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
    }

    td {
        padding: 16px;
        border-bottom: 1px solid var(--border-soft);
        vertical-align: middle;
    }

    tbody tr:hover {
        background-color: #fff3df;
    }

    /* BUTTONS */
    .btn {
        border-radius: 999px;
        padding: 6px 14px;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-view {
        background: linear-gradient(135deg, #34d399, #10b981);
        color: white;
    }

    .btn-view:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 15px rgba(16,185,129,0.4);
    }

    .btn-delete {
        background: linear-gradient(135deg, #f87171, #dc2626);
        color: white;
    }

    .btn-delete:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 15px rgba(220,38,38,0.4);
    }

    /* EMPTY STATE */
    .empty {
        background: white;
        padding: 60px;
        border-radius: 16px;
        text-align: center;
        color: #999;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        font-size: 1.1rem;
    }

    /* POPUP */
    .popup-message {
        border-radius: 16px;
        max-width: 500px;
        animation: fadeIn 0.2s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translate(-50%, -55%); }
        to { opacity: 1; transform: translate(-50%, -50%); }
    }
</style>

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
                <a class="nav-link active-link" href="admin_contact">Contact</a>

                <hr>

                <a class="nav-link " href="admin.php">Admin ⚙️</a>
                <a class="nav-link " href="admin_users.php">Gestion utilisateurs</a>
                <a class="nav-link" href="admin_faq.php">Gestion FAQ</a>

                <a class="nav-link" href="profil">Mon profil</a>
            </nav>
        </div>
    </div>
    <!-- CONTENU PRINCIPAL -->
    <main class="flex-grow-1 p-2">
    <div class="dashboard-container">
        <h1 style="margin-top: 30px;">📧 Demandes de Contact</h1>
        
        <?php if (count($contacts) > 0): ?>
            <div class="table-wrapper">
<table>

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Sujet</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contacts as $contact): ?>
                        <tr>
                            <td><?php echo $contact['id']; ?></td>
                            <td><?php echo htmlspecialchars_decode($contact['nom']); ?></td>
                            <td><?php echo htmlspecialchars_decode($contact['email']); ?></td>
                            <td><?php echo htmlspecialchars_decode($contact['sujet']); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($contact['date_creation'])); ?></td>
                            <td>
                                <button class="btn btn-approved" onclick="showPopup('<?php echo htmlspecialchars_decode($contact['sujet']); ?>', '<?php echo htmlspecialchars_decode($contact['message']); ?>')">Voir</button>
                                <button class="btn btn-unapproved" onclick="deleteContact(<?php echo $contact['id']; ?>)">Supprimer</button>
                                <a class="btn btn-midapproved" href="mailto:<?php echo htmlspecialchars_decode($contact['email']); ?>" class="btn btn-view">Répondre</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        <?php else: ?>
            <div class="empty">Aucune demande de contact</div>
        <?php endif; ?>
    </div>

    <script>
        function viewContact(id) {
        fetch('get_contact.php?id=' + id)
            .then(response => response.json())
            .then(data => alert(data.message))
            .catch(error => console.error('Error:', error));
        }
function deleteContact(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce contact ?')) {
        fetch('deletecontact.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'id=' + encodeURIComponent(id)
        })
        .then(response => response.text())
        .then(data => {
            if (data === 'success') {
                alert('Contact supprimé');
                location.reload(); // ou supprimer la ligne du tableau
            } else {
                alert('Erreur lors de la suppression');
            }
        })
        .catch(error => {
            console.error(error);
            alert('Erreur serveur');
        });
    }
}

function showPopup(sujet,message) {
    const popup = document.createElement('div');
    popup.className = 'popup-message';
    popup.style.position = 'fixed';
    popup.style.top = '50%';
    popup.style.left = '50%';
    popup.style.transform = 'translate(-50%, -50%)';
    popup.style.backgroundColor = 'white';
    popup.style.padding = '30px';
    popup.style.boxShadow = '0 20px 40px rgba(0,0,0,0.25)';
    popup.style.zIndex = '1000';

    popup.innerHTML = `
        <h5 style="margin-bottom:15px;color:#ff8c00;">Message</h5>
        <h4>Sujet : ${sujet}</h4>
        <p style="white-space:pre-line;">${message}</p>
        <div style="text-align:right;margin-top:20px;">
            <button class="btn btn-delete" onclick="this.closest('.popup-message').remove()">Fermer</button>
        </div>
    `;

    document.body.appendChild(popup);
}


        function setupContactLinks() {
            const contactLinks = document.querySelectorAll('.contact-link');
            contactLinks.forEach(link => {
                link.addEventListener('click', function(event) {
                    event.preventDefault();
                    const message = this.getAttribute('data-message');
                    showPopup(message);
                });
            });
        }

        window.onload = setupContactLinks;
    </script>
</body>
</html>