<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mon profil - Logemangue</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/logement.css">
</head>

<?php
require "db2withoutlogin.php";

$logementId = $_GET["id"] ?? null;
if (!$logementId) {
    echo "Logement non spécifié.";
    exit();
}
$stmt = $pdo->prepare("SELECT * FROM logement WHERE id = :id");
$stmt->execute([":id" => $logementId]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$row) {
    echo "Logement non trouvé.";
    exit();
}
$stmt = $pdo->prepare(
    "SELECT user.nom, user.email, user.telephone FROM users user JOIN logement loge ON user.id = loge.id_proprietaire WHERE loge.id = :id_logement"
);
$stmt->execute([":id_logement" => $logementId]);
$owner = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare(
    "SELECT url_photo FROM photo WHERE id_logement = :id_logement ORDER BY id_photo ASC"
);
$stmt->execute([":id_logement" => $logementId]);
$photo = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
   <header class="topbar">
    <a href="index" class="topbar-logo">
      <img src="../png/topbar.png" onresize="3000" alt="Logo" />
    </a>

    <nav class="topbar-nav">
      <a class="nav-link" href="index">Accueil</a>
      <a class="nav-link" href="logements">Recherche</a>
        <?php if (!$isEtudiant): ?>
      <a class="nav-link" href="publish">Publier une annonce</a>
        <?php endif; ?>
      <a class="nav-link" href="logements?view=mesannonces">Mes annonces</a>

      <a class="nav-link" href="listemessagerie">Ma messagerie</a>
      <?php if ($isAdmin): ?> 
          <a class="nav-link" href="admin">Admin ⚙️</a>
      <?php endif; ?>

      <a class="nav-link " href="profil">Mon profil</a>
    </nav>
  </header>

<body>
<!-- ====== BLOC PRINCIPAL ====== -->
<?php 
if (isset($_GET['error'])) {
    $error = $_GET['error'];
    if ($error === 'candidature_exists') {
        echo '<div class="alert alert-danger" style="margin: 20px;" role="alert">
                Vous avez déjà une candidature en cours pour ce logement.
              </div>';
    } elseif ($error === 'missing_logement_id') {
        echo '<div style="margin: 20px;" class="alert alert-danger" role="alert">
                ID de logement manquant.
              </div>';
    }
} elseif (isset($_GET['success'])) {
    $success = $_GET['success'];
    if ($success === 'candidature_submitted') {
        echo '<div style="margin: 20px;" class="alert alert-success" role="alert">
                Candidature envoyée avec succès !
              </div>';
    }
    elseif ($success === 'candidature_deleted') {
        echo '<div style="margin: 20px;" class="alert alert-success" role="alert">
                Candidature supprimée avec succès !
              </div>';
    }
}
?>
<div class="container py-4">

    <div class="row">
        <!-- Grande image -->
        <div class="col-lg-9">
            <img id="mainImage" style="box-shadow: 4px 4px 0 #e1e1e1;" 
                src="<?php echo $photo[0]['url_photo'] ?: 'placeholder.jpg'; ?>" 
                class="banner-img" alt="logement">
        </div>
        <!-- Miniatures -->
        <div class="col-lg-3 d-flex flex-column justify-content-between">
            <?php if (isset($photo[1])) { ?>
                <img src="<?php echo $photo[1]['url_photo']; ?>" 
                    class="thumb-img" alt="miniature" 
                    onclick="swapImages(this)" style="cursor: pointer;">
            <?php } ?>
            <?php if (isset($photo[2])) { ?>
                <img src="<?php echo $photo[2]['url_photo']; ?>" 
                    class="thumb-img" alt="miniature" 
                    onclick="swapImages(this)" style="cursor: pointer;">
            <?php } ?>
            <?php if (isset($photo[3])) { ?>
                <img src="<?php echo $photo[3]['url_photo']; ?>" 
                    class="thumb-img" alt="miniature" 
                    onclick="swapImages(this)" style="cursor: pointer;">
            <?php } ?>            
        </div>
    </div>



    <!-- ===== TEXTE + BOUTONS ACTION CÔTE À CÔTE ===== -->
    <div class="row mt-4">
        <!-- Bloc texte (onglets) -->
        <div class="col-lg-9">
          <!-- BOUTONS D'ONGLETS -->
          <div class="mb-3 tab-buttons">
              <button class="tab-btn active" data-tab="description">Description</button>
              <button class="tab-btn" data-tab="localisation">Localisation</button>
              <button class="tab-btn" data-tab="message">Propriétaire</button>
          </div>

          <!-- CONTENU DES ONGLETS -->
          <div class="tab-content">
              <div class="tab-pane fade show active info-box" id="description">
                    <h2>
                        <?php echo htmlspecialchars($row["titre"]); ?> 
                        <img src="../png/verified.png" 
                            alt="Certifié" 
                            title="Ce contenu est certifié et vérifié par les administrateurs de Logemangue."
                            style="width: 20px; height: 20px; vertical-align: middle; margin-left: 5px; margin-bottom:5px; cursor: pointer;">
                    </h2> 
                    <h3 class=""><strong><?php echo htmlspecialchars(
                        $row["loyer"]
                    ); ?> € / mois</strong></h2> <br>
                    <p><?php echo nl2br(
                        htmlspecialchars($row["description"])   
                    ); ?></p>
                    <p><strong>Adresse :</strong> <?php echo htmlspecialchars(
                        $row["adresse"]
                    ); ?> - 
                    <?php echo htmlspecialchars(
                        $row["ville"]
                    ); ?> </p>
                    <p><strong>Type de logement :</strong> <?php echo htmlspecialchars(
                        $row["TYPE"]
                    ); ?></p>
                    <p><strong>Surface :</strong> <?php echo htmlspecialchars(
                        $row["surface"]
                    ); ?> m²</p>
                    <p><strong>Meublé :</strong> <?php echo $row[
                        "meuble"
                    ] ? "Oui" : "Non"; ?></p>
                    <p><strong>Statut de l'annonce :</strong> <?php echo htmlspecialchars(
                        $row["status"]
                    ); ?></p>   
                    <p><strong>Charges incluses :</strong> <?php echo $row[
                        "charges_incluses"
                    ] ? "Oui" : "Non"; ?></p>
                    <p><strong>Note :</strong> <?php echo htmlspecialchars(
                        $row["note"]
                    ); ?>/5</p>
                    
              </div>

              <div class="tab-pane fade info-box" id="localisation">
                    <p><strong>Adresse :</strong> <?php echo htmlspecialchars(
                        $row["adresse"]
                    ); ?></p>
                
                      <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d2086.8370832268038!2d2.2432445618470607!3d48.777118574786144!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sfr!2sfr!4v1765278973850!5m2!1sfr!2sfr" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
              </div>

              <div class="tab-pane fade info-box" id="message">
                    <h4>Propriétaire du logement</h4> <br>
                    <p><strong>Nom :</strong> <?php echo htmlspecialchars(
                        $owner["nom"]
                    ); ?></p>
                    <p><strong>Email :</strong> <?php echo htmlspecialchars(
                        $owner["email"]
                    ); ?></p>
                    <p><strong>Téléphone :</strong> <?php echo htmlspecialchars(
                        $owner["telephone"]
                    ); ?></p>
                <?php if (isset($userId) && $row["id_proprietaire"] == $userId): ?>
                    <div class="alert alert-info mt-3" role="alert">
                        <h3 class="alert-heading">Liste des candidatures reçues :</h3> <br>
                        <?php
                        $stmt = $pdo->prepare(  
                            "SELECT u.ID, u.nom, u.email, u.telephone
                             FROM reservation r 
                             JOIN users u ON r.id_etudiant = u.ID
                             WHERE r.id_logement = :id_logement"
                        );
                        $stmt->execute([":id_logement" => $logementId]);
                        $candidatures = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        if (count($candidatures) > 0):
                            foreach ($candidatures as $candidature):
                        ?>
                                <div class="candidature-item mb-3 p-3 border rounded">
                                    <p><strong>Nom :</strong> <?php echo htmlspecialchars($candidature["nom"]); ?></p>
                                    <p><strong>Email :</strong> <?php echo htmlspecialchars($candidature["email"]); ?></p>
                                    <p><strong>Téléphone :</strong> <?php echo htmlspecialchars($candidature["telephone"]); ?></p>
                                    <a style="display: inline-flex; align-items: center; justify-content: center;" href="messagerie.php?dest=<?php echo $candidature["ID"]; ?>" class="btn btn-approved">Contacter</a>
                                    <form method="POST" action="supprimercandidature.php" style="display: inline;">
                                        <input type="hidden" name="etudiant_id" value="<?php echo $candidature["ID"]; ?>">
                                        <input type="hidden" name="logement_id" value="<?php echo $logementId; ?>">
                                        <button type="submit" style="height: 40px; align-itself: right;" class="btn btn-unapproved">Supprimer la candidature</button>
                                    </form>

                                </div>
                        <?php
                            endforeach;
                        else:
                            echo "<p>Aucune candidature reçue pour le moment.</p>";
                        endif;
                        ?>
                    </div>
                <?php endif; ?>
              </div>
          </div>



        </div>

        <!-- Bloc boutons d’action -->
        <div class="col-lg-3">
            <div class="action-card">
                <form method="POST" action="candidater.php">
                    <input type="hidden" name="logement_id" value="<?php echo $logementId; ?>">
                <button type="submit" class="action-btn">📄 Candidater</button></form>

                <button class="action-btn">⭐ Favoris</button>
                <a href="messagerie.php?dest=<?php echo $row[
                    "id_proprietaire"
                ]; ?>" class="action-btn link-offset-2 link-underline link-underline-opacity-0">💬 Envoyer un message</a>
                <button class="action-btn" onclick="copyUrl()">📤 Partager</button>
            </div>
        </div>
        
    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const toggle = document.getElementById("menu-toggle");
const sidebar = document.getElementById("sidebar");

toggle.addEventListener("click", () => {
  sidebar.classList.toggle("active");
});
  </script>
<script>
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const targetId = button.getAttribute('data-tab');

            // Activer le bouton cliqué
            tabButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');

            // Afficher le contenu correspondant et cacher les autres
            tabPanes.forEach(pane => {
                if(pane.id === targetId){
                    pane.classList.add('show', 'active');
                } else {
                    pane.classList.remove('show', 'active');
                }
            });
        });
    });
</script>
<script>
    function copyUrl() {
        // Créer un élément textarea temporaire
        var tempInput = document.createElement("textarea");
        
        // Récupérer l'URL de la page actuelle
        tempInput.value = window.location.href;
        
        // Ajouter cet élément au DOM
        document.body.appendChild(tempInput);
        
        // Sélectionner le contenu de l'élément textarea
        tempInput.select();
        tempInput.setSelectionRange(0, 99999); // Pour les appareils mobiles
        
        // Copier le texte sélectionné dans le presse-papier
        document.execCommand("copy");
        
        // Retirer l'élément textarea du DOM
        document.body.removeChild(tempInput);
        
        // Afficher un message pour l'utilisateur (optionnel)
        alert("URL copiée dans le presse-papier !");
    }
</script>
<script>
    function swapImages(clickedThumb) {
        const mainImage = document.getElementById('mainImage');
        const tempSrc = mainImage.src;
        
        // Échange les sources
        mainImage.src = clickedThumb.src;
        clickedThumb.src = tempSrc;
    }
</script>
</body>

</html>