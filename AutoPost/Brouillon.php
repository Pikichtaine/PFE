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
if (!empty($categorias)) {
    $placeholders = implode(',', array_fill(0, count($categorias), '?'));
    $sql .= " AND Categorie IN ($placeholders)";
    $params = array_merge($params, $categorias);
}

// Ejecutar la consulta segura
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Devolver los datos al JavaScript en formato JSON
header('Content-Type: application/json');
echo json_encode($resultados);
?>


<?php
// 1. Configuración de la conexión a la base de datos
$host = 'localhost'; // O la IP de tu servidor
$dbname = 'nombre_de_tu_base_de_datos';
$user = 'tu_usuario';
$pass = 'tu_contraseña';

try {
    // Crear la conexión PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    // Configurar el manejo de errores
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 2. Consulta SQL para sacar los coches
    // Puedes añadir "ORDER BY id DESC LIMIT 8" si solo quieres mostrar los últimos 8
    $sql = "SELECT id, marque, modele, Version, Annee, Kilometrage, Carburant, Boite, Prix, Photo1 FROM coches ORDER BY id DESC";
    $stmt = $pdo->query($sql);
    $coches = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    $coches = []; // Array vacío en caso de error
}
?>

<!-- ═══════════════════ BEST OF THIS WEEK ════════════ -->
<section class="section">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">Lo mejor de esta semana</h2>
      <a href="#" class="section-link">Ver todos →</a>
    </div>

    <div class="grid-4">

      <?php if (!empty($coches)): ?>
        <?php foreach ($coches as $coche): ?>
          
          <!-- Card de PHP Dinámico -->
          <div class="car-card">
            <div class="car-card-img">
              <!-- Reemplazamos el SVG por la imagen de la columna Photo1 -->
              <!-- Reemplazamos posibles barras invertidas por barras normales por si vienes de entorno Windows -->
              <?php $fotoPath = str_replace('\\', '/', $coche['Photo1']); ?>
              <img src="<?= htmlspecialchars($fotoPath) ?>" alt="<?= htmlspecialchars($coche['marque'] . ' ' . $coche['modele']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <span class="car-fav">♡</span>
            <div class="car-card-body">
              <div class="car-brand"><?= htmlspecialchars($coche['marque']) ?></div>
              
              <!-- Concatenamos el modelo y la versión -->
              <div class="car-name"><?= htmlspecialchars($coche['modele'] . ' ' . $coche['Version']) ?></div>
              
              <div class="car-specs">
                <span class="car-spec"><?= htmlspecialchars($coche['Annee']) ?></span>
                <span class="car-spec"><?= number_format($coche['Kilometrage'], 0, ',', '.') ?> km</span>
                <span class="car-spec"><?= htmlspecialchars($coche['Carburant']) ?></span>
                <span class="car-spec"><?= htmlspecialchars($coche['Boite']) ?></span>
              </div>
              <div class="car-footer">
                <div>
                  <!-- Formateamos el precio para que se vea como 48.900 € -->
                  <div class="car-price"><?= number_format($coche['Prix'], 0, ',', '.') ?> €</div>
                </div>
                <!-- La localización se deja estática como solicitaste, aunque podrías sacarla también si existiera la columna -->
                <span class="car-location">📍 Tánger</span>
              </div>
            </div>
          </div>
          
        <?php endforeach; ?>
      <?php else: ?>
        <p>No hay coches disponibles en este momento.</p>
      <?php endif; ?>

    </div>
  </div>
</section>








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
            c.ville, 
            c.rating, 
            c.logo,
            (SELECT COUNT(id) FROM specs WHERE id_concessionaire = c.id) as total_coches,
            (SELECT COUNT(DISTINCT marque) FROM specs WHERE id_concessionaire = c.id) as total_marcas
        FROM concessionnaire c
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

<!-- ═══════════════════ POPULAR DEALERS ══════════════ -->
<section class="section">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">Concesionarios populares</h2>
      <a href="#" class="section-link">Ver todos →</a>
    </div>

    <div class="grid-3">

      <?php if (!empty($concesionarios)): ?>
        <?php foreach ($concesionarios as $dealer): ?>
          
          <div class="dealer-card">
            <div class="dealer-hero-img">
              <!-- Si hay logo en la BD, lo usamos. Si es NULL, usamos una foto por defecto -->
              <?php 
                $fotoDealer = !empty($dealer['logo']) ? $dealer['logo'] : 'imagenes/dealer-default.jpg'; 
                // Corregimos por si vienen con barra invertida \
                $fotoDealer = str_replace('\\', '/', $fotoDealer);
              ?>
              <img src="<?= htmlspecialchars($dealer['logo']) ?>" alt="<?= htmlspecialchars($dealer['titre']) ?>">
            </div>
            
            <div class="dealer-body" style="padding-top:34px;">
              <div class="dealer-name"><?= htmlspecialchars($dealer['titre']) ?></div>
              
              <div class="dealer-meta">
                <span>📍 <?= htmlspecialchars($dealer['adress'] . ", " . $dealer['ville']) ?></span>
              </div>
              
              <div class="dealer-stats">
                <div class="dealer-stat">
                  <!-- Imprimimos el conteo total de coches obtenidos por la subconsulta -->
                  <div class="dealer-stat-val"><?= $dealer['total_coches'] ?></div>
                  <div class="dealer-stat-label">Coches</div>
                </div>
                
                <div class="dealer-stat">
                  <!-- Formateamos el rating para que siempre tenga 1 decimal (ej: 4.0 o 4.5) -->
                  <div class="dealer-stat-val"><?= number_format($dealer['rating'], 1) ?></div>
                  <div class="dealer-stat-label">Valoración</div>
                </div>
                
                <div class="dealer-stat">
                  <!-- Imprimimos el total de marcas únicas obtenidas por la subconsulta -->
                  <div class="dealer-stat-val"><?= $dealer['total_marcas'] ?></div>
                  <div class="dealer-stat-label">Marcas</div>
                </div>
              </div>
              
            </div>
          </div>

        <?php endforeach; ?>
      <?php else: ?>
        <p>No hay concesionarios disponibles.</p>
      <?php endif; ?>

    </div>
  </div>
</section>




<?php
session_start();
require 'Database.php';



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir los datos del formulario y el usuario de la sesión
    $username    = $_SESSION['utilisateur'];
    $dealer_name = trim($_POST['dealer_name']);
    $city        = trim($_POST['city']);
    $phone       = trim($_POST['phone']);

    // Comprobar que los campos no estén vacíos
    if (!empty($dealer_name) && !empty($city) && !empty($phone)) {
        
        try {
            // Preparar la consulta SQL
            // NOTA: Si tu variable de conexión en Database.php se llama diferente a $pdo (por ejemplo $conn), cámbiala aquí abajo
            $sql = "INSERT INTO dealer_requests (username, dealer_name, city, phone) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql); 
            
            // Ejecutar la inserción
            $stmt->execute([$username, $dealer_name, $city, $phone]);

            // Redirigir de vuelta al perfil con un mensaje de éxito
        } catch (Exception $e) {
            die("Error en la base de datos: " . $e->getMessage());
        }

    } else {
        // Redirigir con error si falta algún dato
        header('Location: profil.php?msg=error');
        exit;
    }
}
?>