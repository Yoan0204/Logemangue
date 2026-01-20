
<!-- Vue: Mes Annonces -->
<div class="container py-4">
    <h1 class="text-center mb-4">Liste de vos Logements</h1>
    <hr class="my-4 border-2 opacity-100" style="color: var(--green);">
    <div id="mesAnnoncesContainer" class="row justify-content-center">
        <?php
        // Afficher les résultats
        if ($logements->num_rows > 0) {
            while($row = $logements->fetch_assoc()) {
        ?>
        <div class="col-md-4">
            <a href="logement?id=<?php echo $row['ID']; ?>" class="logement-link">
                <div class="logement-card">
                    <img src="<?php echo $row['photo_url'] ?: 'placeholder.jpg'; ?>" alt="<?php echo $row['titre']; ?>">      
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
                            <button type="submit" class="btn-unapproved" name="totaldelete" onclick="return confirm('Voulez-vous vraiment supprimer ce logement ? Cette action est irréversible.');">Supprimer</button>
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

    <div class="container mt-4 mb-5 text-center">
        <?php
        $limit = isset($limit) ? intval($limit) : 6;
        $offset = isset($offset) ? intval($offset) : 0;
        $total = isset($total) ? intval($total) : 0;
        $start = $total > 0 ? $offset + 1 : 0;
        $end = min($offset + $limit, $total);
        ?>

        <?php if ($total > 0): ?>
            <p id="mesAnnoncesRange" class="small text-muted">Affichage <?php echo $start; ?> - <?php echo $end; ?> sur <?php echo $total; ?> logements</p>
        <?php endif; ?>

        <div class="d-flex justify-content-center gap-2">
            <?php if ($offset + $limit < $total):
                $nextOffset = $offset + $limit;
            ?>
                <button id="loadMoreMesBtn" class="btn btn-login" data-offset="<?php echo $nextOffset; ?>" data-limit="<?php echo $limit; ?>" data-total="<?php echo $total; ?>">Voir plus</button>
            <?php endif; ?>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const btn = document.getElementById('loadMoreMesBtn');
        const container = document.getElementById('mesAnnoncesContainer');
        const range = document.getElementById('mesAnnoncesRange');
        if (!btn) return;

        btn.addEventListener('click', async () => {
            const limit = parseInt(btn.dataset.limit, 10) || 6;
            let offset = parseInt(btn.dataset.offset, 10) || 0;
            const total = parseInt(btn.dataset.total, 10) || 0;
            btn.disabled = true; btn.textContent = 'Chargement...';

            try {
                const params = new URLSearchParams(window.location.search);
                params.set('offset', offset);
                params.set('limit', limit);

                const res = await fetch('fetch_user_logements?' + params.toString());
                const data = await res.json();
                if (!data.success) throw new Error('Erreur');
                if (data.added && data.html) container.insertAdjacentHTML('beforeend', data.html);

                btn.dataset.offset = data.nextOffset;
                if (range) {
                    const start = total > 0 ? 1 : 0;
                    const currentEnd = Math.min(data.nextOffset, total);
                    range.textContent = `Affichage ${start} - ${currentEnd} sur ${total} logements`;
                }

                if (!data.hasMore) btn.style.display = 'none';
            } catch (e) {
                console.error(e); alert('Impossible de charger plus.');
            } finally { btn.disabled = false; btn.textContent = 'Voir plus'; }
        });
    });
    </script>
</div>
