<?php

$data = json_decode(file_get_contents(__DIR__ . '/faces.json'), true);

$profiles = [];
foreach ($data[2]['data'] as $row) {
    if ($row['face_descriptor'] !== null) {
        $desc = json_decode($row['face_descriptor'], true);
        if (is_array($desc) && count($desc) === 128) {
            $profiles[] = [
                'id' => $row['id'],
                'user_id' => $row['user_id'],
                'nama' => $row['nama_panggilan'] ?: 'User ' . $row['user_id'],
                'desc' => $desc
            ];
        }
    }
}

echo "Total valid descriptors: " . count($profiles) . "\n\n";

$minDist = 999;
$closestPair = null;
$duplicates = [];

for ($i = 0; $i < count($profiles); $i++) {
    for ($j = $i + 1; $j < count($profiles); $j++) {
        $sum = 0;
        for ($k = 0; $k < 128; $k++) {
            $diff = $profiles[$i]['desc'][$k] - $profiles[$j]['desc'][$k];
            $sum += $diff * $diff;
        }
        $distance = sqrt($sum);
        
        if ($distance < $minDist) {
            $minDist = $distance;
            $closestPair = [$profiles[$i], $profiles[$j]];
        }
        
        // Threshold 0.55 check
        if ($distance < 0.55) {
            $duplicates[] = [
                'p1' => $profiles[$i],
                'p2' => $profiles[$j],
                'dist' => $distance
            ];
        }
    }
}

echo "=== DUPLICATES (Distance < 0.55) ===\n";
if (empty($duplicates)) {
    echo "No duplicates found with threshold 0.55.\n";
} else {
    foreach ($duplicates as $dup) {
        echo sprintf("Match! %s (ID %s) and %s (ID %s) -> Distance: %.4f\n", 
            $dup['p1']['nama'], $dup['p1']['id'], 
            $dup['p2']['nama'], $dup['p2']['id'], 
            $dup['dist']
        );
    }
}

echo "\n=== CLOSEST PAIR OVERALL ===\n";
if ($closestPair) {
    echo sprintf("%s (ID %s) and %s (ID %s) -> Distance: %.4f\n", 
        $closestPair[0]['nama'], $closestPair[0]['id'], 
        $closestPair[1]['nama'], $closestPair[1]['id'], 
        $minDist
    );
}

// Generate random dummy descriptors to see average distance to existing
$randomFaces = [];
for ($r = 0; $r < 5; $r++) {
    $randDesc = [];
    $norm = 0;
    for ($k=0; $k<128; $k++) {
        $val = (mt_rand() / mt_getrandmax()) * 2 - 1; // -1 to 1
        $randDesc[] = $val;
        $norm += $val * $val;
    }
    $norm = sqrt($norm);
    for ($k=0; $k<128; $k++) {
        $randDesc[$k] /= $norm; // normalize to length 1
    }
    
    $minDistToRand = 999;
    foreach ($profiles as $p) {
        $sum = 0;
        for ($k=0; $k<128; $k++) {
            $diff = $randDesc[$k] - $p['desc'][$k];
            $sum += $diff * $diff;
        }
        $d = sqrt($sum);
        if ($d < $minDistToRand) $minDistToRand = $d;
    }
    $randomFaces[] = $minDistToRand;
}
echo "\nDistance of random noise to closest face: " . implode(', ', array_map(function($v) { return sprintf("%.4f", $v); }, $randomFaces)) . "\n";
