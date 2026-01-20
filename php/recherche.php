<?php
$search = $_GET['search'] ?? '';
$ville = $_GET['ville'] ?? '';
$type = $_GET['type'] ?? '';
$budget_max = $_GET['budget_max'] ?? '2000';
$date_dispo = $_GET['date_dispo'] ?? '';
$surface_min = $_GET['surface_min'] ?? '';
$meuble = $_GET['meuble'] ?? '';
$coloc = $_GET['coloc'] ?? '';
$type_proprio = $_GET['type_proprio'] ?? '';
$keywords = $_GET['keywords'] ?? '';
$min_rating = $_GET['min_rating'] ?? '0';
?>
<!-- Vue: Recherche de Logements -->
<div class="container-fluid p-4">
    <!-- BARRE DE RECHERCHE PLEINE LARGEUR -->
    <form method="GET" action="logements" id="searchForm" class="search-form">
        <input type="hidden" name="view" value="recherche">
        <div class="search-bar p-3 rounded-14 shadow-sm mb-4">
            <div class="search-grid">
                <input type="text" name="search" class="form-control search-input-filters"
                    value="<?php echo htmlspecialchars($search); ?>" placeholder="Recherche...">
                <input type="text" name="ville" class="form-control" placeholder="Ville" 
                    value="<?php echo htmlspecialchars($ville); ?>">
                <select name="type" class="form-select">
                    <option value="">Type</option>
                    <option value="Studio" <?php echo $type === 'Studio' ? 'selected' : ''; ?>>Studio</option>
                    <option value="T1" <?php echo $type === 'T1' ? 'selected' : ''; ?>>T1</option>
                    <option value="T2" <?php echo $type === 'T2' ? 'selected' : ''; ?>>T2</option>
                    <option value="T3" <?php echo $type === 'T3' ? 'selected' : ''; ?>>T3</option>
                    <option value="T4+" <?php echo $type === 'T4+' ? 'selected' : ''; ?>>T4+</option>                    
                    <option value="Colocation" <?php echo $type === 'Colocation' ? 'selected' : ''; ?>>Colocation</option>
                </select>
                <button type="button" id="toggleFilters" class="btn btn-filters">Plus de filtres non? ⚙️</button>
            </div>
        </div>

        <!-- FILTRES AVANCÉS -->
        <div id="filtersSection" class="filters p-4 rounded-4 shadow-sm mb-4" style="display:none;">
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Budget max (€)</label>
                    <input type="range" name="budget_max" class="form-range custom-range" min="0" max="2000" step="25" 
                        id="rangeBudget" value="<?php echo htmlspecialchars($budget_max); ?>">
                    <div class="d-flex justify-content-between small fw-semibold">
                        <span>0€</span>
                        <span id="budgetValue"><?php echo htmlspecialchars($budget_max); ?>€</span>
                        <span>2000€</span>  
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Date de disponibilité</label>
                    <input type="date" name="date_dispo" class="form-control" 
                        value="<?php echo htmlspecialchars($date_dispo); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Surface min (m²)</label>
                    <input type="number" name="surface_min" class="form-control" placeholder="Surface (m²)"
                        value="<?php echo htmlspecialchars($surface_min); ?>">
                </div>
            </div>

            <div class="row g-3 mt-3">
                <div class="col-md-2 d-flex align-items-center gap-2">
                    <input type="checkbox" id="meuble" name="meuble" class="custom-checkbox" value="1"
                        <?php echo $meuble === '1' ? 'checked' : ''; ?>>
                    <label for="meuble" class="form-label mb-0">Meublé</label>
                </div>
                <div class="col-md-2 d-flex align-items-center gap-2">
                    <input type="checkbox" id="coloc" name="coloc" class="custom-checkbox" value="1"
                        <?php echo $coloc === '1' ? 'checked' : ''; ?>>
                    <label for="coloc" class="form-label mb-0">Colocation</label>
                </div>
                <div class="col-md-4">
                    <select name="type_proprio" class="form-select">
                        <option value="">Proposé par (Agence, Particulier...)</option>
                        <option value="Organisme" <?php echo $type_proprio === 'Organisme' ? 'selected' : ''; ?>>Organisme</option>
                        <option value="Proprietaire" <?php echo $type_proprio === 'Proprietaire' ? 'selected' : ''; ?>>Proprietaire</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" name="keywords" class="form-control" placeholder="Mots-clés"
                        value="<?php echo htmlspecialchars($keywords); ?>">
                </div>
            </div>

            <div class="row mt-3 align-items-center">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Note minimale :</label>
                    <div class="stars" id="starRating">
                        <span class="star" data-value="1" <?php echo $min_rating >= 1 ? 'data-selected="true"' : ''; ?>>★</span>
                        <span class="star" data-value="2" <?php echo $min_rating >= 2 ? 'data-selected="true"' : ''; ?>>★</span>
                        <span class="star" data-value="3" <?php echo $min_rating >= 3 ? 'data-selected="true"' : ''; ?>>★</span>
                        <span class="star" data-value="4" <?php echo $min_rating >= 4 ? 'data-selected="true"' : ''; ?>>★</span>
                        <span class="star" data-value="5" <?php echo $min_rating >= 5 ? 'data-selected="true"' : ''; ?>>★</span>
                    </div>
                    <input type="hidden" name="min_rating" id="minRatingValue" value="<?php echo htmlspecialchars($min_rating); ?>">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-filters w-100">Appliquer les filtres</button>
                </div>
                <div class="col-md-4">
                    <a style="    display: inline-flex; align-items: center;justify-content: center;" href="logements" class="btn btn-filters-secondary w-100">Réinitialiser</a>
                </div>
            </div>
        </div>
    </form>

    <hr class="my-4 border-2 opacity-100" style="color: var(--green);">

    <!-- LOGEMENTS -->
    <div class="container">
        <h1 class="text-center mb-4">Liste des Logements</h1>
        <hr class="my-4 border-2 opacity-100" style="color: var(--green);"><br>
        <div id="logementsContainer" class="row justify-content-center">
            <?php
            // Afficher les résultats (initial load)
            if ($logements->num_rows > 0) {
                while($row = $logements->fetch_assoc()) {
            ?>
            <div class="col-md-4">
                <a href="logement?id=<?php echo $row['ID']; ?>" class="logement-link">
                    <div class="logement-card">
                        <img src="<?php echo $row['photo_url'] ?: 'placeholder.jpg'; ?>" 
     alt="<?php echo $row['titre']; ?>">

                        <div class="info">
                            <h6 class="fw-bold mb-1"><?php echo $row['titre']; ?></h6>
                            <p class="text-muted mb-0"><?php echo $row['loyer']; ?> € / mois</p>
                            <p class="small text-muted mb-0">
                                <?php echo $row['ville']; ?>
                            <p class="small text-muted mb-0">
                            </p>
                            <p class="small text-muted mb-0">
                                <?php echo $row['surface']; ?> m² - <?php echo $row['TYPE']; ?>
                            </p>
                            <p class="small text-muted">
                                Note : <?php echo $row['note'] ?? 'N/A'; ?> ★
                            <?php
                            // Bouton visible seulement pour l'admin
                            if (isset($isAdmin) && $isAdmin == 1) {
                            ?>
                            <form method="post">
                                <input type="hidden" name="logement_id" value="<?php echo $row['ID']; ?>">
                                <button type="submit" class="btn-unapproved" name="delete">Supprimer</button>
                            </form>
                            <?php
                            }
                            ?>
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

    <!-- Pagination / Voir plus -->
    <?php
    // Défauts si les variables ne sont pas fournies
    $limit = isset($limit) ? intval($limit) : 10;
    $offset = isset($offset) ? intval($offset) : 0;
    $total = isset($total) ? intval($total) : 0;

    // Calcul indices d'affichage
    $start = $total > 0 ? $offset + 1 : 0;
    $end = min($offset + $limit, $total);
    ?>

    <div class="container mt-4 mb-5 text-center">
        <?php if ($total > 0): ?>
            <p id="resultRange" class="small text-muted">Affichage <?php echo $start; ?> - <?php echo $end; ?> sur <?php echo $total; ?> logements</p>
        <?php endif; ?>

        <div class="d-flex justify-content-center gap-2">
            <?php if ($offset > 0):
                $prevOffset = max(0, $offset - $limit);
                $q = $_GET; $q['offset'] = $prevOffset; $q['limit'] = $limit;
            ?>
                <a href="logements?<?php echo http_build_query($q); ?>" class="btn btn-outline-secondary">Précédent</a>
            <?php endif; ?>

            <?php if ($offset + $limit < $total):
                // Pour fallback non-JS, on fournit un lien; le bouton JS prendra le relai
                $nextOffset = $offset + $limit;
                $q = $_GET; $q['offset'] = $nextOffset; $q['limit'] = $limit;
            ?>
                <a id="loadMoreLink" href="logements?<?php echo http_build_query($q); ?>" class="btn btn-primary d-none">Voir plus</a>
                <button id="loadMoreBtn" class="btn btn-login" data-offset="<?php echo $offset + $limit; ?>" data-limit="<?php echo $limit; ?>" data-total="<?php echo $total; ?>">Voir plus</button>
                <noscript>
                    <a href="logements?<?php echo http_build_query($q); ?>" class="btn btn-login">Voir plus</a>
                </noscript>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Toggle affichage filtres
    const toggleBtn = document.getElementById('toggleFilters');
    const filtersSection = document.getElementById('filtersSection');
    toggleBtn.addEventListener('click', () => {
        filtersSection.style.display = filtersSection.style.display === 'none' ? 'block' : 'none';
    });

    // Mise à jour dynamique du budget affiché
    const budgetRange = document.getElementById('rangeBudget');
    const budgetValue = document.getElementById('budgetValue');
    if (budgetRange) {
        budgetRange.addEventListener('input', () => {
            budgetValue.textContent = budgetRange.value + '€';
        });
    }

    // Sélecteur d'étoiles
    const stars = document.querySelectorAll('#starRating .star');
    const minRatingInput = document.getElementById('minRatingValue');
    
    stars.forEach(star => {
        // Appliquer la classe selected si data-selected="true"
        if (star.hasAttribute('data-selected') && star.getAttribute('data-selected') === 'true') {
            star.classList.add('selected');
        }
        
        star.addEventListener('click', () => {
            const value = parseInt(star.dataset.value);
            minRatingInput.value = value;
            stars.forEach(s => {
                s.classList.toggle('selected', parseInt(s.dataset.value) <= value);
            });
        });
    });
</script>

<script>
// AJAX "Voir plus" : charge et ajoute des cartes sans rechargement
document.addEventListener('DOMContentLoaded', () => {
    const loadBtn = document.getElementById('loadMoreBtn');
    const container = document.getElementById('logementsContainer');
    const resultRange = document.getElementById('resultRange');

    if (!loadBtn) return;

    loadBtn.addEventListener('click', async () => {
        let offset = parseInt(loadBtn.dataset.offset, 10) || 0;
        const limit = parseInt(loadBtn.dataset.limit, 10) || 6;
        const total = parseInt(loadBtn.dataset.total, 10) || 0;

        loadBtn.disabled = true;
        const originalText = loadBtn.textContent;
        loadBtn.textContent = 'Chargement...';

        try {
            const params = new URLSearchParams(window.location.search);
            params.set('offset', offset);
            params.set('limit', limit);

            const res = await fetch('fetch_logements?' + params.toString());
            if (!res.ok) throw new Error('Network error');
            const data = await res.json();
            if (!data.success) throw new Error('Server error');

            if (data.added && data.html) {
                container.insertAdjacentHTML('beforeend', data.html);
            }

            // Update offset and range
            loadBtn.dataset.offset = data.nextOffset;
            if (resultRange) {
                const start = total > 0 ? 1 : 0;
                const currentEnd = Math.min(data.nextOffset, total);
                resultRange.textContent = `Affichage ${start} - ${currentEnd} sur ${total} logements`;
            }

            if (!data.hasMore) {
                loadBtn.style.display = 'none';
            }

        } catch (err) {
            console.error(err);
            alert('Impossible de charger plus de logements.');
        } finally {
            loadBtn.disabled = false;
            loadBtn.textContent = originalText;
        }
    });
});
</script>
