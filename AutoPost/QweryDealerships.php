<?php

try {
    // Consulta SQL avanzada:
    // 1. Saca los datos del concesionario
    // 2. Cuenta cuántos coches tiene vinculados en la tabla 'specs'
    // 3. Cuenta cuántas marcas únicas tiene en la tabla 'specs'
 $sql_dealers = "
        SELECT 
            c.id, 
            c.titre,
            c.adresse, 
            c.ville, 
            c.rating, 
            c.logo,
            COUNT(s.id) AS total_coches,
            COUNT(DISTINCT s.marque) AS total_marcas
        FROM concessionnaire c
        LEFT JOIN specs s ON c.id = s.id_concessionnaire
        GROUP BY c.id
        ORDER BY c.id ASC
        LIMIT 3
    ";
    
    $stmt_dealers = $pdo->query($sql_dealers);
    $concesionarios = $stmt_dealers->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    $concesionarios = []; 
}
?>