<?php
    $today = new DateTime();
    $selectedDate = $_GET['date'] ?? $today->format("Y-m-d");
?>

<div class="btn-toolbar" role="toolbar">
    <div class="btn-group me-2" role="group">

        <?php
        // 5 jours avant aujoud'hui
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
            Today ('.$today->format("d/m").')
            </a>';

        // 5 jours apres aujourd'hui
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



<div class="container mt-4">
<?php foreach($matches as $match): ?>
    <div class="card mb-3 shadow-sm">
        <div class="card-body">
            <div class="row align-items-center text-center text-md-start">

                <!-- Logo Team 1 -->
                <div class="col-2 text-center">
                    <img src="<?= $match['logo1'] ?>" class="img-fluid" style="max-height:70px;">
                </div>

                <!-- Team 1 name -->
                <div class="col-2 fw-bold">
                    <?= $match['team1'] ?>
                </div>

                <!-- Date + button -->
                <div class="col-4 text-center">
                    <div class="fw-semibold">
                    <?= date("d/m/Y H:i", strtotime($match['date'])) ?>
                    </div>
                    <a href="match.php?date=<?= $match['date'] ?>" class="btn btn-primary btn-sm mt-2">
                    Voir les détails
                    </a>
                </div>

                <!-- Team 2 name -->
                <div class="col-2 fw-bold text-end">
                    <?= $match['team2'] ?>
                </div>

                <!-- Logo Team 2 -->
                <div class="col-2 text-center">
                    <img src="<?= $match['logo2'] ?>" class="img-fluid" style="max-height:70px;">
                </div>

            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>