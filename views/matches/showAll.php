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
            <h4>Aucun match prévu pour le <?= date("d/m/Y", strtotime($selectedDate)) ?></h4>
            <p>Sélectionnez une autre date pour voir les matchs disponibles.</p>
        </div>
    <?php else: ?>
        <?php foreach($matches as $match): ?>
            <?php 
                // Extraction des données du match depuis l'API Football
                $fixture = $match['fixture'] ?? [];
                $teams = $match['teams'] ?? [];
                $goals = $match['goals'] ?? [];
                $league = $match['league'] ?? [];
            ?>
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center text-center text-md-start">

                        <!-- Logo Team Home -->
                        <div class="col-2 text-center">
                            <img src="<?= $teams['home']['logo'] ?? '' ?>" 
                                 class="img-fluid" 
                                 style="max-height:70px;"
                                 alt="<?= htmlspecialchars($teams['home']['name'] ?? '') ?>">
                        </div>

                        <!-- Team Home name -->
                        <div class="col-2 fw-bold">
                            <?= htmlspecialchars($teams['home']['name'] ?? 'N/A') ?>
                        </div>

                        <!-- Date + Score + button -->
                        <div class="col-4 text-center">
                            <div class="fw-semibold mb-2">
                                <?= date("d/m/Y H:i", strtotime($fixture['date'] ?? 'now')) ?>
                            </div>
                            
                            <?php if (isset($goals['home']) && isset($goals['away'])): ?>
                                <div class="fs-4 fw-bold text-primary mb-2">
                                    <?= $goals['home'] ?> - <?= $goals['away'] ?>
                                </div>
                            <?php else: ?>
                                <div class="text-muted mb-2">
                                    Match à venir
                                </div>
                            <?php endif; ?>
                            
                            <div class="small text-muted">
                                <?= htmlspecialchars($league['name'] ?? '') ?>
                            </div>
                        </div>

                        <!-- Team Away name -->
                        <div class="col-2 fw-bold text-end">
                            <?= htmlspecialchars($teams['away']['name'] ?? 'N/A') ?>
                        </div>

                        <!-- Logo Team Away -->
                        <div class="col-2 text-center">
                            <img src="<?= $teams['away']['logo'] ?? '' ?>" 
                                 class="img-fluid" 
                                 style="max-height:70px;"
                                 alt="<?= htmlspecialchars($teams['away']['name'] ?? '') ?>">
                        </div>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>