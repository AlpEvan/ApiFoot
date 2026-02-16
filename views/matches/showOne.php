<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="container mt-4">

    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <?php if (empty($match) && !isset($error)): ?>
        <div class="alert alert-warning">
            <i class="bi bi-info-circle"></i> Aucune donnée de match disponible.
        </div>
    <?php else: ?>

    <!-- MATCH HEADER -->
    <div class="card mb-4 shadow">
        <div class="card-body">
            <div class="row align-items-center text-center">

                <!-- Logo équipe domicile -->
                <div class="col-12 col-md-2 mb-3 mb-md-0">
                    <?php if (!empty($match['homeLogo'] ?? '')): ?>
                        <img src="<?= htmlspecialchars($match['homeLogo']) ?>" 
                             class="img-fluid" 
                             style="max-height:80px;"
                             alt="<?= htmlspecialchars($match['homeName'] ?? '') ?>">
                    <?php else: ?>
                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                             style="width:80px; height:80px; margin:0 auto;">
                            <i class="bi bi-shield-fill fs-1 text-muted"></i>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Nom équipe domicile -->
                <div class="col-12 col-md-3 fw-bold mb-2 mb-md-0">
                    <?= htmlspecialchars($match['homeName'] ?? 'N/A') ?>
                </div>

                <!-- Score et statut -->
                <div class="col-12 col-md-2 mb-3 mb-md-0">
                    <?php 
                        $eventStage = $match['eventStage'] ?? 'SCHEDULED';
                        $isLive = in_array($eventStage, ['LIVE', 'FIRST_HALF', 'SECOND_HALF', 'EXTRA_TIME']);
                        $isFinished = in_array($eventStage, ['FINISHED', 'AFTER_EXTRA_TIME', 'AFTER_PENALTIES']);
                    ?>
                    
                    <!-- Badge statut -->
                    <div class="mb-2">
                        <?php if ($isLive): ?>
                            <span class="badge bg-danger">
                                <i class="bi bi-broadcast"></i> EN DIRECT
                            </span>
                        <?php elseif ($isFinished): ?>
                            <span class="badge bg-secondary">TERMINÉ</span>
                        <?php else: ?>
                            <span class="badge bg-info">À VENIR</span>
                        <?php endif; ?>
                    </div>

                    <!-- Score -->
                    <?php if ($isLive || $isFinished): ?>
                        <div class="fs-2 fw-bold text-primary">
                            <?= htmlspecialchars($match['homeScore'] ?? '0') ?> 
                            - 
                            <?= htmlspecialchars($match['awayScore'] ?? '0') ?>
                        </div>
                        
                        <?php if ($isLive && isset($match['gameTime']) && $match['gameTime'] !== '-1'): ?>
                            <div class="text-danger fw-bold">
                                <?= htmlspecialchars($match['gameTime']) ?>'
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-muted fs-4">vs</div>
                    <?php endif; ?>

                    <!-- Heure du match -->
                    <div class="text-muted small mt-2">
                        <?php if (!empty($match['startDateTimeUtc'])): ?>
                            <?= date('d/m/Y H:i', strtotime($match['startDateTimeUtc'])) ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Nom équipe extérieure -->
                <div class="col-12 col-md-3 fw-bold text-md-end mb-2 mb-md-0">
                    <?= htmlspecialchars($match['awayName'] ?? 'N/A') ?>
                </div>

                <!-- Logo équipe extérieure -->
                <div class="col-12 col-md-2">
                    <?php if (!empty($match['awayLogo'] ?? '')): ?>
                        <img src="<?= htmlspecialchars($match['awayLogo']) ?>" 
                             class="img-fluid" 
                             style="max-height:80px;"
                             alt="<?= htmlspecialchars($match['awayName'] ?? '') ?>">
                    <?php else: ?>
                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                             style="width:80px; height:80px; margin:0 auto;">
                            <i class="bi bi-shield-fill fs-1 text-muted"></i>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Infos match -->
            <div class="text-center mt-3">
                <div class="small text-secondary">
                    <i class="bi bi-trophy"></i> 
                    <?= htmlspecialchars($match['tournamentName'] ?? 'Tournoi inconnu') ?>
                </div>
            </div>
        </div>
    </div>

    <!-- ONGLETS -->
    <ul class="nav nav-tabs mb-3" id="matchTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="lineups-tab" data-bs-toggle="tab" data-bs-target="#lineups" type="button">
                <i class="bi bi-people"></i> Compositions
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="stats-tab" data-bs-toggle="tab" data-bs-target="#stats" type="button">
                <i class="bi bi-bar-chart"></i> Statistiques
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="events-tab" data-bs-toggle="tab" data-bs-target="#events" type="button">
                <i class="bi bi-clock-history"></i> Événements
            </button>
        </li>
    </ul>

    <div class="tab-content" id="matchTabContent">
        
        <!-- ONGLET COMPOSITIONS -->
        <div class="tab-pane fade show active" id="lineups" role="tabpanel">
            <?php if (empty($lineups)): ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Compositions non disponibles pour ce match.
                </div>
            <?php else: ?>
                <div class="row">
                    <!-- Équipe domicile -->
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white fw-bold text-center">
                                <i class="bi bi-shield-fill"></i> <?= htmlspecialchars($match['homeName'] ?? 'Équipe 1') ?>
                            </div>
                            <div class="card-body">
                                <?php 
                                $homePlayers = $lineups['home'] ?? $lineups['homeTeam'] ?? [];
                                if (is_array($homePlayers) && !empty($homePlayers)): 
                                ?>
                                    <h6 class="text-muted mb-3">
                                        <i class="bi bi-people-fill"></i> Titulaires
                                    </h6>
                                    <ul class="list-group list-group-flush">
                                        <?php foreach ($homePlayers as $player): ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span>
                                                    <?php if (isset($player['number'])): ?>
                                                        <span class="badge bg-secondary"><?= htmlspecialchars($player['number']) ?></span>
                                                    <?php endif; ?>
                                                    <?= htmlspecialchars($player['name'] ?? $player['player'] ?? 'Joueur inconnu') ?>
                                                </span>
                                                <small class="text-muted">
                                                    <?= htmlspecialchars($player['position'] ?? '') ?>
                                                </small>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <p class="text-muted text-center">Aucun joueur disponible</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Équipe extérieure -->
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-header bg-danger text-white fw-bold text-center">
                                <i class="bi bi-shield-fill"></i> <?= htmlspecialchars($match['awayName'] ?? 'Équipe 2') ?>
                            </div>
                            <div class="card-body">
                                <?php 
                                $awayPlayers = $lineups['away'] ?? $lineups['awayTeam'] ?? [];
                                if (is_array($awayPlayers) && !empty($awayPlayers)): 
                                ?>
                                    <h6 class="text-muted mb-3">
                                        <i class="bi bi-people-fill"></i> Titulaires
                                    </h6>
                                    <ul class="list-group list-group-flush">
                                        <?php foreach ($awayPlayers as $player): ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span>
                                                    <?php if (isset($player['number'])): ?>
                                                        <span class="badge bg-secondary"><?= htmlspecialchars($player['number']) ?></span>
                                                    <?php endif; ?>
                                                    <?= htmlspecialchars($player['name'] ?? $player['player'] ?? 'Joueur inconnu') ?>
                                                </span>
                                                <small class="text-muted">
                                                    <?= htmlspecialchars($player['position'] ?? '') ?>
                                                </small>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <p class="text-muted text-center">Aucun joueur disponible</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- ONGLET STATISTIQUES -->
        <div class="tab-pane fade" id="stats" role="tabpanel">
            <?php if (empty($stats)): ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Statistiques non disponibles pour ce match.
                </div>
            <?php else: ?>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <?php 
                        // Les stats peuvent être dans différents formats selon l'API
                        $statistics = $stats['statistics'] ?? $stats;
                        
                        if (is_array($statistics) && !empty($statistics)):
                            foreach ($statistics as $stat):
                                $statName = $stat['type'] ?? $stat['name'] ?? 'Statistique';
                                $homeValue = $stat['home'] ?? $stat['homeValue'] ?? 0;
                                $awayValue = $stat['away'] ?? $stat['awayValue'] ?? 0;
                                
                                // Calculer les pourcentages pour la barre de progression
                                $total = is_numeric($homeValue) && is_numeric($awayValue) ? ($homeValue + $awayValue) : 0;
                                $homePercent = $total > 0 ? ($homeValue / $total) * 100 : 50;
                                $awayPercent = $total > 0 ? ($awayValue / $total) * 100 : 50;
                        ?>
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="fw-bold text-primary"><?= htmlspecialchars($homeValue) ?></span>
                                        <span class="text-muted"><?= htmlspecialchars($statName) ?></span>
                                        <span class="fw-bold text-danger"><?= htmlspecialchars($awayValue) ?></span>
                                    </div>
                                    <div class="progress" style="height: 25px;">
                                        <div class="progress-bar bg-primary" 
                                             style="width: <?= $homePercent ?>%"
                                             role="progressbar">
                                        </div>
                                        <div class="progress-bar bg-danger" 
                                             style="width: <?= $awayPercent ?>%"
                                             role="progressbar">
                                        </div>
                                    </div>
                                </div>
                        <?php 
                            endforeach;
                        else:
                        ?>
                            <p class="text-muted text-center">Aucune statistique disponible</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- ONGLET ÉVÉNEMENTS -->
        <div class="tab-pane fade" id="events" role="tabpanel">
            <?php 
            $incidents = $match['incidents'] ?? $stats['incidents'] ?? [];
            
            if (empty($incidents)): 
            ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Aucun événement disponible pour ce match.
                </div>
            <?php else: ?>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <?php foreach ($incidents as $event): ?>
                                <?php
                                $time = $event['time'] ?? $event['minute'] ?? 'N/A';
                                $type = $event['type'] ?? 'event';
                                $player = $event['player'] ?? $event['playerName'] ?? 'Joueur';
                                $team = $event['team'] ?? '';
                                
                                // Déterminer l'icône selon le type
                                $icon = 'bi-circle-fill';
                                $color = 'text-secondary';
                                
                                if (stripos($type, 'goal') !== false) {
                                    $icon = 'bi-circle-fill';
                                    $color = 'text-success';
                                } elseif (stripos($type, 'yellow') !== false) {
                                    $icon = 'bi-square-fill';
                                    $color = 'text-warning';
                                } elseif (stripos($type, 'red') !== false) {
                                    $icon = 'bi-square-fill';
                                    $color = 'text-danger';
                                } elseif (stripos($type, 'substitution') !== false) {
                                    $icon = 'bi-arrow-left-right';
                                    $color = 'text-info';
                                }
                                ?>
                                <li class="list-group-item">
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-secondary me-3"><?= htmlspecialchars($time) ?>'</span>
                                        <i class="bi <?= $icon ?> <?= $color ?> me-2"></i>
                                        <span class="fw-bold me-2"><?= htmlspecialchars($player) ?></span>
                                        <small class="text-muted"><?= htmlspecialchars($type) ?></small>
                                        <?php if (!empty($team)): ?>
                                            <small class="text-muted ms-2">(<?= htmlspecialchars($team) ?>)</small>
                                        <?php endif; ?>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </div>

    </div>

    <!-- Bouton retour -->
    <div class="mt-4 mb-4 text-center">
        <a href="/" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Retour aux matchs
        </a>
    </div>

    <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>