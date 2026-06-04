<?php
require 'Database.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>All Cars — Encuentra tu próximo coche</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Syne:wght@400;500;600;700;800&family=DM+Mono:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="CSS/Acceuil.css">
</head>
<body>
 
  <!-- ═══════════════════ NAV ════════════════════════════ -->
<nav class="nav">
    <!-- NUEVO: Agrupamos el logo y los enlaces a la izquierda -->
    <div class="header-left">
      <div class="nav-logo">
        <div class="nav-logo-icon">▲</div>
        <div class="nav-logo-text">Auto<span>Post</span></div>
      </div>
      
      <!-- Botones luminosos -->
      <div class="nav-links">
        <button class="btn-luminous">Coches</button>
        <button class="btn-luminous">Concesionarios</button>
      </div>
    </div>
 
    <div class="header-right">
      <div class="nav-search">
        <span class="nav-search-icon">⌕</span>
        <input type="text" placeholder="Buscar marca, modelo, ciudad…" />
      </div>

      <button class="nav-location">
        <span class="nav-location-dot"></span>
        Tánger, MA
      </button>
      
      <button class="btn-primary">Connexion</button>
    </div>
  </nav>
 
  <!-- ═══════════════════ CATEGORIES ════════════════════ -->

 
  <!-- ═══════════════════ BEST OF THIS WEEK ════════════ -->
  <section class="section">
    <div class="container">
      <div class="section-header">
        <h2 class="section-title all-cars">All Cars</h2>
      </div>
 
      <div class="categories-wrap all-cars">
    <div class="categories-inner">
      <button class="cat-pill">Sedán</button>
      <button class="cat-pill">SUV / 4x4</button>
      <button class="cat-pill">Deportivo</button>
      <button class="cat-pill">Eléctrico</button>
      <button class="cat-pill">Coupé</button>
      <button class="cat-pill">Familiar</button>
      <button class="cat-pill">Pickup</button>
      <button class="cat-pill">Clásico</button>
    </div>
  </div>

      <div class="grid-4">
 
        <!-- Card 1 -->

   <?php
   /* =========================
    CONSULTA SQL
   ========================= */

try{

$sql = "SELECT * FROM specs";

$stlt = $pdo->query($sql);
$cards = $stlt->fetchAll(PDO::FETCH_ASSOC);

}catch(PDOException $e){
    echo "Erreur type: " . $e->getMessage();
}

foreach ($cards as $card) : ?>

        <div class="car-card">
          <div class="car-card-img">
        <img src= "<?php echo $card['Photo1'] ?>" alt="<?php echo $card['marque'] ?>"/>

          </div>
          <span class="car-fav">♡</span>
          <div class="car-card-body">
            <div class="car-brand"><?php echo $card['marque'] ?></div>
            <div class="car-name"><?php echo $card['modele'] . " " . $card['Version'] ?></div>
            <div class="car-specs">
              <span class="car-spec"><?php echo $card['Annee'] ?></span>
              <span class="car-spec"><?php echo $card['Kilometrage']?> km</span>
              <span class="car-spec"><?php echo $card['Carburant'] ?></span>
            </div>
            <div class="car-footer">
              <div>
                <div class="car-price"><?php echo $card['Prix'] ?> MAD</div>
              </div>
              <span class="car-location">📍 Tánger</span>
            </div>
          </div>
        </div>

    <?php endforeach; ?>

 
      </div>
    </div>
  </section>
 
  <div class="section-divider"></div>
 
   
  <!-- ═══════════════════ FOOTER ════════════════════════ -->
  <footer>
    <div class="footer-inner">
      <div class="footer-brand">
        <div class="nav-logo">
          <div class="nav-logo-icon">▲</div>
          <div class="nav-logo-text">Auto<span>Post</span></div>
        </div>
        <p>El marketplace de coches más grande de Marruecos. Conectamos compradores y concesionarios de todo el país.</p>
      </div>
 
      <div class="footer-col">
        <h4>Explorar</h4>
        <ul>
          <li><a href="#">Coches nuevos</a></li>
          <li><a href="#">Coches de ocasión</a></li>
          <li><a href="#">Eléctricos</a></li>
          <li><a href="#">Concesionarios</a></li>
          <li><a href="#">Ofertas</a></li>
        </ul>
      </div>
 
      <div class="footer-col">
        <h4>Para concesionarios</h4>
        <ul>
          <li><a href="#">Publicar anuncio</a></li>
          <li><a href="#">Editor de tienda</a></li>
          <li><a href="#">Gestión de inventario</a></li>
          <li><a href="#">Planes y precios</a></li>
          <li><a href="#">Soporte</a></li>
        </ul>
      </div>
 
      <div class="footer-col">
        <h4>Empresa</h4>
        <ul>
          <li><a href="#">Sobre nosotros</a></li>
          <li><a href="#">Blog</a></li>
          <li><a href="#">Prensa</a></li>
          <li><a href="#">Trabaja con nosotros</a></li>
          <li><a href="#">Contacto</a></li>
        </ul>
      </div>
    </div>
 
    <div class="footer-bottom">
      <p>© 2026 AutoPost. Todos los derechos reservados.</p>
      <div class="footer-bottom-links">
        <a href="#">Privacidad</a>
        <a href="#">Términos</a>
        <a href="#">Cookies</a>
      </div>
    </div>
  </footer>
 
</body>
</html>