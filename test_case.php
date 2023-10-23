<?php
function fill_bucket($bucket_volume, $ball_volumes) {
    $ball_counts = [];

    foreach ($ball_volumes as $ball_color => $ball_volume) {
        $ball_count = (int)($bucket_volume / $ball_volume);
        $ball_counts[$ball_color] = $ball_count;
    }

    return $ball_counts;
}

$ball_volumes = [
    "pink" => 2.5,
    "red" => 2,
    "blue" => 1,
    "orange" => 0.8,
    "green" => 0.5
];

$ball_counts = fill_bucket(100, $ball_volumes);

foreach ($ball_counts as $ball_color => $ball_count) {
    echo "$ball_color: $ball_count\n";
}
?>

