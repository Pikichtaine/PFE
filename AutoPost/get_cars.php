<?php
// Conexión a la base de datos
require "Database.php";

// Recibir los datos del frontend y convertirlos de JSON a Arrays de PHP
$marcas = isset($_POST['marcas']) ? json_decode($_POST['marcas']) : [];

// Truco profesional: Empezar con WHERE 1=1. 
// Esto es siempre verdadero y nos permite concatenar los "AND" fácilmente.
$sql = "SELECT * FROM specs WHERE 1=1";
$params = []; // Aquí guardaremos los valores seguros

// Si el usuario seleccionó marcas...
if (!empty($marcas)) {
    // Crea signos de interrogación para PDO: ej. (?, ?)
    $placeholders = implode(',', array_fill(0, count($marcas), '?'));
    $sql .= " AND marque IN ($placeholders)";
    // Añadir los valores al array de parámetros
    $params = array_merge($params, $marcas);
}

// Si el usuario seleccionó categorías...

// Ejecutar la consulta segura
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Devolver los datos al JavaScript en formato JSON
header('Content-Type: application/json');
echo json_encode($resultados);
?>