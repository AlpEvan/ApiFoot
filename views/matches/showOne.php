<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<div class="container mt-4">

    <!-- MATCH HEADER -->
    <div class="card mb-4 shadow">
        <div class="card-body">

            <div class="row align-items-center text-center">

            <div class="col-2">
                <img src="<?= $match['logo1'] ?>" class="img-fluid" style="max-height:80px;">
            </div>

            <div class="col-3 fw-bold">
                <?= $match['team1'] ?>
            </div>

            <div class="col-2">
                <div class="fs-4 fw-bold text-danger">
                <?= $match['score1'] ?> : <?= $match['score2'] ?>
                </div>
                <div class="text-muted">
                <?= $match['minute'] ?> | <?= $match['time'] ?>
                </div>
            </div>

            <div class="col-3 fw-bold text-end">
                <?= $match['team2'] ?>
            </div>

            <div class="col-2">
                <img src="<?= $match['logo2'] ?>" class="img-fluid" style="max-height:80px;">
            </div>

            </div>

            <div class="text-center mt-3 small text-secondary">
                <?= $match['stadium'] ?> • <?= $match['league'] ?>
            </div>

        </div>
    </div>

    <!-- PLAYERS -->
    <div class="row">

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header fw-bold text-center">
                    <?= $match['team1'] ?> joueurs
                </div>
                <ul class="list-group list-group-flush">
                    <?php foreach($match['players1'] as $p): ?>
                    <li class="list-group-item"><?= $p ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header fw-bold text-center">
                    <?= $match['team2'] ?> joueurs
                </div>
                <ul class="list-group list-group-flush">
                    <?php foreach($match['players2'] as $p): ?>
                    <li class="list-group-item"><?= $p ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

    </div>

</div>