<?php

require 'Database.php';

$stmt = $pdo->prepare("
    SELECT Class, Portes, Transmission, `Type de carburant`, `Vitesse maximale`, `Acceleration 0-100`, `RPM-min`, RPM_max
    FROM `vehicle-specs`
    WHERE modele = ?
");

$stmt->execute([
    $_GET['modele']
]);

echo json_encode(
    $stmt->fetch(PDO::FETCH_ASSOC)
);
?>