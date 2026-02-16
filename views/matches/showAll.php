<?php
    $today = new DateTime();
    $selectedDate = $_GET['date'] ?? $today->format("Y-m-d");
?>

<div class="container mt-4">
    <div class="btn-toolbar mb-4" role="toolbar">
        <div class="btn-group me-2" role="group">

            <?php
            // 5 jours avant aujourd'hui
            for ($i = 5; $i >= 1; $i--) {
                $date = (clone $today)->modify("-$i day");
                $formatted = $date->format("Y-m-d");
                $active = ($formatted == $selectedDate) ? "active" : "";

                echo '<a href="?date='.$formatted.'" 
                    class="btn btn-outline-primary '.$active.'">
                    '.$date->format("d/m").'
                    </a>';
            }

            // aujourd'hui
            $todayFormatted = $today->format("Y-m-d");
            $activeToday = ($selectedDate == $todayFormatted) ? "active" : "";

            echo '<a href="?date='.$todayFormatted.'" 
                class="btn btn-primary '.$activeToday.'">
                Aujourd\'hui ('.$today->format("d/m").')
                </a>';

            // 5 jours après aujourd'hui
            for ($i = 1; $i <= 5; $i++) {
                $date = (clone $today)->modify("+$i day");
                $formatted = $date->format("Y-m-d");
                $active = ($formatted == $selectedDate) ? "active" : "";

                echo '<a href="?date='.$formatted.'" 
                    class="btn btn-outline-primary '.$active.'">
                    '.$date->format("d/m").'
                    </a>';
            }
            ?>

        </div>
    </div>

    <?php if (empty($matches)): ?>
        <div class="alert alert-info text-center">
            <h4>Aucun match trouvé pour le <?= date("d/m/Y", strtotime($selectedDate)) ?></h4>
            <p>Sélectionnez une autre date pour voir les matchs disponibles.</p>
        </div>
    <?php else: ?>
        <div class="mb-3">
            <h5>
                <?= count($matches) ?> match(s) trouvé(s) pour le <?= date("d/m/Y", strtotime($selectedDate)) ?>
            </h5>
        </div>

        <?php foreach($matches as $match): ?>
            <?php 
                // Extraction des données depuis SportDB API
                $eventId = $match['eventId'] ?? '';
                $homeName = $match['homeName'] ?? 'N/A';
                $awayName = $match['awayName'] ?? 'N/A';
                $homeLogo = $match['homeLogo'] ?? '';
                $awayLogo = $match['awayLogo'] ?? '';
                $homeScore = $match['homeScore'] ?? null;
                $awayScore = $match['awayScore'] ?? null;
                $tournamentName = $match['tournamentName'] ?? '';
                $eventStage = $match['eventStage'] ?? 'SCHEDULED';
                $startDate = $match['startDateTimeUtc'] ?? '';
                $gameTime = $match['gameTime'] ?? '-1';
                
                // Calculer le statut du match
                $isLive = ($eventStage === 'LIVE' || $eventStage === 'FIRST_HALF' || $eventStage === 'SECOND_HALF');
                $isFinished = ($eventStage === 'FINISHED' || $eventStage === 'AFTER_EXTRA_TIME' || $eventStage === 'AFTER_PENALTIES');
                $isScheduled = ($eventStage === 'SCHEDULED');
                
                // Badge de statut
                if ($isLive) {
                    $statusBadge = '<span class="badge bg-danger">EN DIRECT</span>';
                } elseif ($isFinished) {
                    $statusBadge = '<span class="badge bg-secondary">TERMINÉ</span>';
                } else {
                    $statusBadge = '<span class="badge bg-info">À VENIR</span>';
                }
            ?>
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center text-center text-md-start">

                        <!-- Logo Team Home -->
                        <div class="col-12 col-md-2 text-center mb-3 mb-md-0">
                            <?php if (!empty($homeLogo)): ?>
                                <img src="<?= htmlspecialchars($homeLogo) ?>" 
                                     class="img-fluid" 
                                     style="max-height:70px;"
                                     alt="<?= htmlspecialchars($homeName) ?>">
                            <?php else: ?>
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                     style="width:70px; height:70px; margin:0 auto;">
                                    <i class="bi bi-shield-fill fs-2 text-muted"></i>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Team Home name -->
                        <div class="col-12 col-md-2 fw-bold mb-2 mb-md-0">
                            <?= htmlspecialchars($homeName) ?>
                        </div>

                        <!-- Date + Score + Status -->
                        <div class="col-12 col-md-4 text-center mb-3 mb-md-0">
                            <!-- Badge de statut -->
                            <div class="mb-2">
                                <?= $statusBadge ?>
                            </div>
                            
                            <!-- Date et heure -->
                            <div class="fw-semibold mb-2">
                                <?php if (!empty($startDate)): ?>
                                    <?= date("d/m/Y H:i", strtotime($startDate)) ?>
                                <?php else: ?>
                                    Date non disponible
                                <?php endif; ?>
                            </div>
                            
                            <!-- Score -->
                            <?php if ($isFinished || $isLive): ?>
                                <div class="fs-3 fw-bold text-primary mb-2">
                                    <?= htmlspecialchars($homeScore ?? '0') ?> - <?= htmlspecialchars($awayScore ?? '0') ?>
                                </div>
                                
                                <?php if ($isLive && $gameTime !== '-1'): ?>
                                    <div class="small text-danger fw-bold">
                                        <?= htmlspecialchars($gameTime) ?>'
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="text-muted mb-2 fs-5">
                                    - vs -
                                </div>
                            <?php endif; ?>
                            
                            <!-- Nom du tournoi -->
                            <div class="small text-muted mt-2">
                                <i class="bi bi-trophy"></i>
                                <?= htmlspecialchars($tournamentName) ?>
                            </div>
                            
                            <!-- Bouton détails -->
                            <?php if (!empty($eventId)): ?>
                                <div class="mt-2">
                                    <a href="/match/<?= htmlspecialchars($eventId) ?>" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-info-circle"></i> Détails
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Team Away name -->
                        <div class="col-12 col-md-2 fw-bold text-md-end mb-2 mb-md-0">
                            <?= htmlspecialchars($awayName) ?>
                        </div>

                        <!-- Logo Team Away -->
                        <div class="col-12 col-md-2 text-center">
                            <?php if (!empty($awayLogo)): ?>
                                <img src="<?= htmlspecialchars($awayLogo) ?>" 
                                     class="img-fluid" 
                                     style="max-height:70px;"
                                     alt="<?= htmlspecialchars($awayName) ?>">
                            <?php else: ?>
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                     style="width:70px; height:70px; margin:0 auto;">
                                    <i class="bi bi-shield-fill fs-2 text-muted"></i>
                                </div>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Ajouter Bootstrap Icons si pas déjà inclus -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">