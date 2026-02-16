<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="container mt-4">

    <h2 class="mb-4">
        <i class="bi bi-search"></i> Résultats pour "<?= htmlspecialchars($search) ?>"
    </h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <?php if (isset($info)): ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> <?= htmlspecialchars($info) ?>
        </div>
    <?php endif; ?>

    <!-- ÉQUIPES (MATCHS TROUVÉS) -->
    <?php if (!empty($equipes)): ?>
    <div class="mb-4">
        <h3 class="border-bottom pb-2">
            <i class="bi bi-shield-fill"></i> Matchs de l'équipe "<?= htmlspecialchars($search) ?>" 
            <span class="badge bg-primary"><?= count($equipes) ?></span>
        </h3>
        
        <?php 
        // Trier les matchs par date
        usort($equipes, function($a, $b) {
            return strcmp($a['startDateTimeUtc'] ?? '', $b['startDateTimeUtc'] ?? '');
        });
        ?>
        
        <?php foreach($equipes as $match): ?>
            <?php 
                $homeName = $match['homeName'] ?? 'N/A';
                $awayName = $match['awayName'] ?? 'N/A';
                $homeLogo = $match['homeLogo'] ?? '';
                $awayLogo = $match['awayLogo'] ?? '';
                $homeScore = $match['homeScore'] ?? null;
                $awayScore = $match['awayScore'] ?? null;
                $tournamentName = $match['tournamentName'] ?? '';
                $eventStage = $match['eventStage'] ?? 'SCHEDULED';
                $startDate = $match['startDateTimeUtc'] ?? '';
                $eventId = $match['eventId'] ?? '';
                
                $isLive = in_array($eventStage, ['LIVE', 'FIRST_HALF', 'SECOND_HALF']);
                $isFinished = in_array($eventStage, ['FINISHED', 'AFTER_EXTRA_TIME', 'AFTER_PENALTIES']);
            ?>
            
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        
                        <!-- Logo équipe domicile -->
                        <div class="col-2 col-md-1 text-center">
                            <?php if (!empty($homeLogo)): ?>
                                <img src="<?= htmlspecialchars($homeLogo) ?>" 
                                     class="img-fluid" 
                                     style="max-height:50px;"
                                     alt="<?= htmlspecialchars($homeName) ?>">
                            <?php else: ?>
                                <i class="bi bi-shield-fill fs-3 text-muted"></i>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Nom équipe domicile -->
                        <div class="col-4 col-md-3 fw-bold">
                            <?= htmlspecialchars($homeName) ?>
                        </div>
                        
                        <!-- Score et infos -->
                        <div class="col-12 col-md-4 text-center my-2 my-md-0">
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
                            
                            <!-- Date -->
                            <div class="small text-muted mb-2">
                                <?php if (!empty($startDate)): ?>
                                    <?= date('d/m/Y H:i', strtotime($startDate)) ?>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Score -->
                            <?php if ($isFinished || $isLive): ?>
                                <div class="fs-4 fw-bold text-primary">
                                    <?= htmlspecialchars($homeScore ?? '0') ?> - <?= htmlspecialchars($awayScore ?? '0') ?>
                                </div>
                            <?php else: ?>
                                <div class="text-muted fs-5">vs</div>
                            <?php endif; ?>
                            
                            <!-- Tournoi -->
                            <div class="small text-muted mt-2">
                                <i class="bi bi-trophy"></i>
                                <?= htmlspecialchars($tournamentName) ?>
                            </div>
                            
                            <!-- Bouton détails -->
                            <?php if (!empty($eventId)): ?>
                                <div class="mt-2">
                                    <a href="/match/<?= htmlspecialchars($eventId) ?>" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-info-circle"></i> Voir détails
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Nom équipe extérieure -->
                        <div class="col-4 col-md-3 fw-bold text-end">
                            <?= htmlspecialchars($awayName) ?>
                        </div>
                        
                        <!-- Logo équipe extérieure -->
                        <div class="col-2 col-md-1 text-center">
                            <?php if (!empty($awayLogo)): ?>
                                <img src="<?= htmlspecialchars($awayLogo) ?>" 
                                     class="img-fluid" 
                                     style="max-height:50px;"
                                     alt="<?= htmlspecialchars($awayName) ?>">
                            <?php else: ?>
                                <i class="bi bi-shield-fill fs-3 text-muted"></i>
                            <?php endif; ?>
                        </div>
                        
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- JOUEURS - Non disponible -->
    <?php if (!empty($joueurs)): ?>
    <div class="mb-4">
        <h3 class="border-bottom pb-2">
            <i class="bi bi-person-fill"></i> Joueurs
        </h3>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> La recherche de joueurs n'est pas disponible avec l'API SportDB.
        </div>
    </div>
    <?php endif; ?>

    <!-- LIGUES - Non disponible -->
    <?php if (!empty($ligues)): ?>
    <div class="mb-4">
        <h3 class="border-bottom pb-2">
            <i class="bi bi-trophy-fill"></i> Ligues
        </h3>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> La recherche de ligues n'est pas disponible avec l'API SportDB.
        </div>
    </div>
    <?php endif; ?>

    <!-- PAYS - Non disponible -->
    <?php if (!empty($pays)): ?>
    <div class="mb-4">
        <h3 class="border-bottom pb-2">
            <i class="bi bi-globe"></i> Pays
        </h3>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> La recherche de pays n'est pas disponible avec l'API SportDB.
        </div>
    </div>
    <?php endif; ?>

    <!-- Aucun résultat -->
    <?php if (empty($equipes) && empty($joueurs) && empty($ligues) && empty($pays) && !isset($error)): ?>
    <div class="alert alert-warning text-center">
        <h4>
            <i class="bi bi-search"></i> Aucun match trouvé pour l'équipe "<?= htmlspecialchars($search) ?>"
        </h4>
        <p class="mb-1">Vérifiez l'orthographe ou essayez avec un autre nom d'équipe.</p>
        <small class="text-muted">
            La recherche couvre les matchs des 7 derniers jours et 7 prochains jours.
        </small>
    </div>
    <?php endif; ?>

    <!-- Bouton retour -->
    <div class="text-center mt-4 mb-4">
        <a href="/" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Retour à l'accueil
        </a>
    </div>

</div>