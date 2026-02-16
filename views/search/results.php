<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4">

    <h2 class="mb-4">Résultats pour "<?= htmlspecialchars($search) ?>"</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <!-- ÉQUIPES -->
    <?php if (!empty($equipes)): ?>
    <div class="mb-4">
        <h3 class="border-bottom pb-2">Équipes</h3>
        <div class="row">
            <?php foreach($equipes as $equipe): ?>
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <img src="<?= $equipe['team']['logo'] ?? '' ?>" 
                             class="img-fluid mb-3" 
                             style="max-height:80px;" 
                             alt="<?= htmlspecialchars($equipe['team']['name'] ?? '') ?>">
                        
                        <h5 class="card-title fw-bold"><?= htmlspecialchars($equipe['team']['name'] ?? '') ?></h5>
                        
                        <p class="text-muted mb-1">
                            <small><?= htmlspecialchars($equipe['venue']['name'] ?? 'N/A') ?></small>
                        </p>
                        
                        <p class="text-muted mb-1">
                            <small><?= htmlspecialchars($equipe['team']['country'] ?? '') ?></small>
                        </p>
                        
                        <p class="text-muted">
                            <small>Fondée en <?= htmlspecialchars($equipe['team']['founded'] ?? 'N/A') ?></small>
                        </p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- JOUEURS -->
    <?php if (!empty($joueurs)): ?>
    <div class="mb-4">
        <h3 class="border-bottom pb-2">Joueurs</h3>
        <div class="row">
            <?php foreach($joueurs as $joueur): ?>
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <img src="<?= $joueur['player']['photo'] ?? '' ?>" 
                                 class="rounded-circle me-3" 
                                 style="width:60px; height:60px; object-fit:cover;" 
                                 alt="<?= htmlspecialchars($joueur['player']['name'] ?? '') ?>">
                            
                            <div>
                                <h5 class="card-title mb-0 fw-bold">
                                    <?= htmlspecialchars($joueur['player']['name'] ?? '') ?>
                                </h5>
                                <small class="text-muted">
                                    <?= htmlspecialchars($joueur['player']['nationality'] ?? '') ?>
                                </small>
                            </div>
                        </div>
                        
                        <?php if (!empty($joueur['statistics'][0])): 
                            $stat = $joueur['statistics'][0]; 
                        ?>
                        <div class="mb-2">
                            <img src="<?= $stat['team']['logo'] ?? '' ?>" 
                                 style="height:20px;" 
                                 class="me-2" 
                                 alt="">
                            <span class="fw-bold"><?= htmlspecialchars($stat['team']['name'] ?? '') ?></span>
                        </div>
                        
                        <p class="mb-1">
                            <small>Position: <strong><?= htmlspecialchars($stat['games']['position'] ?? 'N/A') ?></strong></small>
                        </p>
                        
                        <p class="mb-1">
                            <small>Âge: <?= htmlspecialchars($joueur['player']['age'] ?? 'N/A') ?> ans</small>
                        </p>
                        
                        <p class="mb-0">
                            <small>Buts: <?= htmlspecialchars($stat['goals']['total'] ?? 0) ?></small>
                        </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- LIGUES -->
    <?php if (!empty($ligues)): ?>
    <div class="mb-4">
        <h3 class="border-bottom pb-2">🏆 Ligues</h3>
        <div class="row">
            <?php foreach($ligues as $ligue): ?>
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <img src="<?= $ligue['league']['logo'] ?? '' ?>" 
                             class="img-fluid mb-3" 
                             style="max-height:60px;" 
                             alt="<?= htmlspecialchars($ligue['league']['name'] ?? '') ?>">
                        
                        <h5 class="card-title fw-bold"><?= htmlspecialchars($ligue['league']['name'] ?? '') ?></h5>
                        
                        <p class="text-muted mb-1">
                            <small>🌍 <?= htmlspecialchars($ligue['country']['name'] ?? '') ?></small>
                        </p>
                        
                        <p class="text-muted">
                            <small>Type: <?= htmlspecialchars($ligue['league']['type'] ?? '') ?></small>
                        </p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- PAYS -->
    <?php if (!empty($pays)): ?>
    <div class="mb-4">
        <h3 class="border-bottom pb-2">🌍 Pays</h3>
        <div class="row">
            <?php foreach($pays as $country): ?>
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <img src="<?= $country['flag'] ?? '' ?>" 
                             class="img-fluid mb-3" 
                             style="max-height:60px;" 
                             alt="<?= htmlspecialchars($country['name'] ?? '') ?>">
                        
                        <h5 class="card-title fw-bold"><?= htmlspecialchars($country['name'] ?? '') ?></h5>
                        
                        <?php if (isset($country['code'])): ?>
                        <p class="text-muted">
                            <small>Code: <?= htmlspecialchars($country['code']) ?></small>
                        </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Aucun résultat -->
    <?php if (empty($equipes) && empty($joueurs) && empty($ligues) && empty($pays) && !isset($error)): ?>
    <div class="alert alert-info text-center">
        <h4>Aucun résultat trouvé pour "<?= htmlspecialchars($search) ?>"</h4>
        <p>Essayez avec un autre terme de recherche.</p>
    </div>
    <?php endif; ?>

</div>