<?php

try {
    // 2. Consulta SQL para sacar los coches
    // Puedes añadir "ORDER BY id DESC LIMIT 8" si solo quieres mostrar los últimos 8
    $sql = "SELECT id, marque, modele, Version, Annee, Kilometrage, Carburant, Boite, Prix, Photo1 FROM specs ORDER BY Favoris DESC LIMIT 8";
    $stmt = $pdo->query($sql);
    $coches = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    $coches = []; // Array vacío en caso de error
}
?>