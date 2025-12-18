
<!-- Vue: Mes Annonces -->
<div class="container py-4">
    <h1 class="text-center mb-4">Liste de vos Logements</h1>
    <hr class="my-4 border-2 opacity-100" style="color: var(--green);">
    <div class="row justify-content-center">
        <?php
        // Afficher les résultats
        if ($logements->num_rows > 0) {
            while($row = $logements->fetch_assoc()) {
        ?>
        <div class="col-md-4">
            <a href="/mes_annonces/<?php echo $row['ID']; ?>" class="logement-link">
                <div class="logement-card">
                    <img src="test.webp" alt="<?php echo $row['titre']; ?>">
                    <div class="info">
                        <h6 class="fw-bold mb-1"><?php echo $row['titre']; ?></h6>
                        <p class="text-muted mb-0"><?php echo $row['loyer']; ?> € / mois</p>
                        <p class="small text-muted mb-0">
                            Disponible : <?php echo ($row['disponible'] == 1) ? 'Oui' : 'Non'; ?>
                        </p>
                        <p class="small text-muted">
                            <?php
                                $status = $row['status'];
                                $color = '';

                                if ($status === 'Approved') {
                                    $color = 'text-success'; // vert
                                } elseif ($status === 'Waiting') {
                                    $color = 'text-warning'; // orange
                                } else {
                                    $color = 'text-muted'; // gris par défaut
                                }
                            ?>
                            Status : <span class="<?php echo $color; ?> fw-bold"><?php echo htmlspecialchars($status); ?></span>
                        </p>

                        <!-- Formulaire de suppression -->
                        <form method="post">
                            <input type="hidden" name="logement_id" value="<?php echo $row['ID']; ?>">
                            <button type="submit" class="btn-unapproved" name="delete">Supprimer</button>
                        </form>
                    </div>
                </div>
            </a>
        </div>
        <?php
            }
        } else {
            echo "<div class='col-12'><p class='text-center'>Aucun logement trouvé.</p></div>";
        }
        ?>
    </div>
</div>
