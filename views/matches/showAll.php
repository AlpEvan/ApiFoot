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
