<?php

session_start();

// Simulamos la variable para comprobar si está logueado
$is_logged_in = isset($_SESSION['id']); 

/* =========================
   VERIFICAR SESSION
   ========================= */

require 'Database.php';
require 'QweryAcceuil.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>AutoPost — Encuentra tu próximo coche</title>
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
        <button class="btn-luminous" id="btn-coches">Coches</button>
        <button class="btn-luminous" id="btn-concesionarios">Concesionarios</button>
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
  
  <!-- Lógica de Sesiones con tu diseño -->
  <?php if ($is_logged_in): ?>
    
    <!-- Enlaces con tu efecto "btn-luminous" (Neon) -->
    <a href="favoritos.php" class="btn-luminous">Favoritos</a>
    <a href="carrito.php" class="btn-luminous">Carrito</a>
    
    <!-- El botón principal ahora es el perfil -->
    <a href="Profil.php" class="btn-primary">Mi Perfil</a>

  <?php else: ?>
    
    <!-- Botón de Login si NO está logueado -->
    <a href="Login.php" class="btn-primary" id="btn-connexion">Connexion</a>
    
  <?php endif; ?>
</div>
  </nav>
 
  <!-- ═══════════════════ HERO ═══════════════════════════ -->
  <section class="hero">
    <div class="hero-bg"></div>
    <div class="hero-grid"></div>
   <img class="hero-car-img" src="medias/Urus.png">

 
    <div class="hero-inner">
      <img class="logo" src="medias/AutoHaus.png">
      <h1 class="hero-title">Lamborghini Urus</h1>
      <p class="hero-desc">Premier Super SUV hybride rechargeable de Lamborghini · V8 4.0 L bi-turbo + moteur électrique · 800 ch combinés · 0–100 en 3,4 s · Vmax 312 km/h · anthracite · intérieur Y-design alcantara & carbone · étriers Y-Brake jaunes.</p>
 

        <button class="btn-hero-primary">Ver anuncio</button>
    </div>
  </section>
     <!-- Thumbnail strip -->
    <div class="hero-thumbs">
      <div class="hero-thumb">
        <img class="hero-thumb-img" src="medias/Urus.png">
        <img class="hero-thumb-img-top" src="medias/AutoHaus.png">
      </div>
      <div class="hero-thumb">
        <img class="hero-thumb-img" src="medias/T-ROC.png">
        <img class="hero-thumb-img-top" src="medias/Tingis.png">   
      </div>
      <div class="hero-thumb">
        <img class="hero-thumb-img" src="medias/Audi RS q8.png">
        <img class="hero-thumb-img-top" src="medias/GT speed.png">
      </div>
      <div class="hero-thumb">
        <img class="hero-thumb-img" src="medias/Dacia.png">
        <img class="hero-thumb-img-top" src="medias/Dercaoui.png">      </div>
      <div class="hero-thumb active">
        <img class="hero-thumb-img" src="medias/Promocion.png">
      </div>
    </div>
  <!-- ═══════════════════ CATEGORIES ════════════════════ -->
  <div class="categories-wrap">
    <div class="categories-inner">
      <span class="cat-label">Categoría</span>
      <button class="cat-pill active">Todos</button>
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
          <div class="car-card" id="<?= $coche['id'] ?>">
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
 
  <div class="section-divider"></div>
 
  <!-- ═══════════════════ RECENT POSTS ══════════════════ -->
  <section class="section">
    <div class="container">
      <div class="section-header">
        <h2 class="section-title">Publicaciones recientes</h2>
        <a href="#" class="section-link">Ver todos →</a>
      </div>
 
      <div class="grid-4">
        <div class="car-card">
          <div class="car-card-img cg3">
            <svg class="car-silhouette" viewBox="0 0 200 80" fill="white" xmlns="http://www.w3.org/2000/svg">
              <path d="M10 55 C10 55 30 28 65 20 C85 15 115 15 135 20 C160 28 180 48 188 55 L192 62 L8 62 Z"/>
              <ellipse cx="45" cy="64" rx="13" ry="13"/>
              <ellipse cx="155" cy="64" rx="13" ry="13"/>
            </svg>
          </div>
          <span class="car-fav">♡</span>
          <div class="car-card-body">
            <div class="car-brand">Renault</div>
            <div class="car-name">Mégane E-Tech 220 CV</div>
            <div class="car-specs">
              <span class="car-spec">2024</span>
              <span class="car-spec">4.200 km</span>
              <span class="car-spec">Eléctrico</span>
            </div>
            <div class="car-footer">
              <div>
                <div class="car-price">38.200 €</div>
              </div>
              <span class="car-location">📍 Tánger</span>
            </div>
          </div>
        </div>
 
        <div class="car-card">
          <div class="car-card-img cg4">
            <svg class="car-silhouette" viewBox="0 0 200 80" fill="white" xmlns="http://www.w3.org/2000/svg">
              <path d="M6 56 C6 56 22 26 58 18 C78 13 122 13 142 18 C166 26 184 50 191 56 L194 63 L6 63 Z"/>
              <ellipse cx="43" cy="65" rx="14" ry="14"/>
              <ellipse cx="157" cy="65" rx="14" ry="14"/>
            </svg>
          </div>
          <span class="car-fav">♡</span>
          <div class="car-card-body">
            <div class="car-brand">Kia</div>
            <div class="car-name">EV9 GT-Line AWD</div>
            <div class="car-specs">
              <span class="car-spec">2024</span>
              <span class="car-spec">1.100 km</span>
              <span class="car-spec">Eléctrico</span>
            </div>
            <div class="car-footer">
              <div>
                <div class="car-price">71.500 €</div>
              </div>
              <span class="car-location">📍 Salé</span>
            </div>
          </div>
        </div>
 
        <div class="car-card">
          <div class="car-card-img cg1">
            <svg class="car-silhouette" viewBox="0 0 200 80" fill="white" xmlns="http://www.w3.org/2000/svg">
              <path d="M10 56 C10 56 30 28 62 20 C82 15 118 15 138 20 C162 28 182 50 188 56 L192 62 L8 62 Z"/>
              <ellipse cx="44" cy="64" rx="13" ry="13"/>
              <ellipse cx="156" cy="64" rx="13" ry="13"/>
            </svg>
          </div>
          <span class="car-fav">♡</span>
          <div class="car-card-body">
            <div class="car-brand">Jaguar</div>
            <div class="car-name">F-Pace SVR P550 AWD</div>
            <div class="car-specs">
              <span class="car-spec">2023</span>
              <span class="car-spec">14.000 km</span>
              <span class="car-spec">Gasolina</span>
            </div>
            <div class="car-footer">
              <div>
                <div class="car-price">86.700 €</div>
              </div>
              <span class="car-location">📍 Casablanca</span>
            </div>
          </div>
        </div>
 
        <div class="car-card">
          <div class="car-card-img cg2">
            <svg class="car-silhouette" viewBox="0 0 200 80" fill="white" xmlns="http://www.w3.org/2000/svg">
              <path d="M8 57 C8 57 24 27 58 19 C78 14 122 14 142 19 C166 27 183 50 190 57 L194 63 L6 63 Z"/>
              <ellipse cx="44" cy="65" rx="14" ry="14"/>
              <ellipse cx="156" cy="65" rx="14" ry="14"/>
            </svg>
          </div>
          <span class="car-fav">♡</span>
          <div class="car-card-body">
            <div class="car-brand">Volvo</div>
            <div class="car-name">XC90 Recharge T8 AWD</div>
            <div class="car-specs">
              <span class="car-spec">2024</span>
              <span class="car-spec">6.200 km</span>
              <span class="car-spec">Híbrido</span>
            </div>
            <div class="car-footer">
              <div>
                <div class="car-price">79.300 €</div>
              </div>
              <span class="car-location">📍 Tánger</span>
            </div>
          </div>
        </div>
 
        <div class="car-card">
          <div class="car-card-img cg6">
            <svg class="car-silhouette" viewBox="0 0 200 80" fill="white" xmlns="http://www.w3.org/2000/svg">
              <path d="M9 55 C9 55 25 26 60 18 C80 13 120 13 140 18 C164 26 183 49 189 55 L192 62 L8 62 Z"/>
              <ellipse cx="44" cy="64" rx="13" ry="13"/>
              <ellipse cx="155" cy="64" rx="13" ry="13"/>
            </svg>
          </div>
          <span class="car-fav">♡</span>
          <div class="car-card-body">
            <div class="car-brand">Lexus</div>
            <div class="car-name">RX 500h F Sport AWD</div>
            <div class="car-specs">
              <span class="car-spec">2023</span>
              <span class="car-spec">19.400 km</span>
              <span class="car-spec">Híbrido</span>
            </div>
            <div class="car-footer">
              <div>
                <div class="car-price">67.500 €</div>
              </div>
              <span class="car-location">📍 Agadir</span>
            </div>
          </div>
        </div>
 
        <div class="car-card">
          <div class="car-card-img cg5">
            <svg class="car-silhouette" viewBox="0 0 200 80" fill="white" xmlns="http://www.w3.org/2000/svg">
              <path d="M10 56 C10 56 28 27 62 19 C82 14 118 14 138 19 C162 27 181 50 187 56 L191 62 L9 62 Z"/>
              <ellipse cx="45" cy="64" rx="13" ry="13"/>
              <ellipse cx="155" cy="64" rx="13" ry="13"/>
            </svg>
          </div>
          <span class="car-fav">♡</span>
          <div class="car-card-body">
            <div class="car-brand">BMW</div>
            <div class="car-name">iX xDrive50 Sport</div>
            <div class="car-specs">
              <span class="car-spec">2024</span>
              <span class="car-spec">2.800 km</span>
              <span class="car-spec">Eléctrico</span>
            </div>
            <div class="car-footer">
              <div>
                <div class="car-price">103.900 €</div>
              </div>
              <span class="car-location">📍 Tánger</span>
            </div>
          </div>
        </div>
 
        <div class="car-card">
          <div class="car-card-img cg7">
            <svg class="car-silhouette" viewBox="0 0 200 80" fill="white" xmlns="http://www.w3.org/2000/svg">
              <path d="M8 57 C8 57 25 27 60 19 C80 14 120 14 140 19 C164 27 182 50 189 57 L192 63 L8 63 Z"/>
              <ellipse cx="44" cy="65" rx="14" ry="14"/>
              <ellipse cx="156" cy="65" rx="14" ry="14"/>
            </svg>
          </div>
          <span class="car-fav">♡</span>
          <div class="car-card-body">
            <div class="car-brand">Peugeot</div>
            <div class="car-name">508 PSE Hybrid 360 CV</div>
            <div class="car-specs">
              <span class="car-spec">2022</span>
              <span class="car-spec">28.500 km</span>
              <span class="car-spec">Híbrido</span>
            </div>
            <div class="car-footer">
              <div>
                <div class="car-price">42.100 €</div>
              </div>
              <span class="car-location">📍 Fez</span>
            </div>
          </div>
        </div>
 
        <div class="car-card">
          <div class="car-card-img cg8">
            <svg class="car-silhouette" viewBox="0 0 200 80" fill="white" xmlns="http://www.w3.org/2000/svg">
              <path d="M9 56 C9 56 26 27 60 19 C80 14 120 14 140 19 C163 27 182 49 188 56 L191 62 L9 62 Z"/>
              <ellipse cx="44" cy="64" rx="14" ry="14"/>
              <ellipse cx="154" cy="64" rx="14" ry="14"/>
            </svg>
          </div>
          <span class="car-fav">♡</span>
          <div class="car-card-body">
            <div class="car-brand">Ford</div>
            <div class="car-name">Mustang Mach-E GT AWD</div>
            <div class="car-specs">
              <span class="car-spec">2023</span>
              <span class="car-spec">11.200 km</span>
              <span class="car-spec">Eléctrico</span>
            </div>
            <div class="car-footer">
              <div>
                <div class="car-price">55.800 €</div>
              </div>
              <span class="car-location">📍 Marrakech</span>
            </div>
          </div>
        </div>
 
      </div>
    </div>
  </section>
 
  <div class="section-divider"></div>
 
  <!-- ═══════════════════ POPULAR DEALERS ══════════════ -->
<?php 
require 'QweryDealerships.php'
?>
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
                <span>📍 <?= htmlspecialchars($dealer['adresse'] . ", " . $dealer['ville']) ?></span>
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
<script>
  let coches = document.getElementById("btn-coches");
  coches.addEventListener('click', () => {
    window.location.href = "AllCars.php"
  });
    let concesionarios = document.getElementById("btn-concesionarios");
  concesionarios.addEventListener('click', () => {
    window.location.href = "AllDealerships.php"
  });
let cards = document.querySelectorAll('.car-card');
cards.forEach(card => {
  card.addEventListener('click', () => {
    // '_blank' fuerza la apertura en una nueva ventana/pestaña
      window.location.href = "Car.php?id=" + card.id;
  });
});
/* ═══════════════════════════════════════════
   HERO SLIDER — AutoPost
   ═══════════════════════════════════════════ */

// ── DATOS DE CADA SLIDE ──────────────────────
// Edita esto con tus datos reales
const slides = [
  {
    car:   'medias/Urus.png',
    logo:  'medias/AutoHaus.png',
    title: 'Lamborghini Urus',
    desc:  'Premier Super SUV hybride rechargeable · V8 4.0 L bi-turbo + moteur électrique · 800 ch · 0–100 en 3,4 s · Vmax 312 km/h.',
    link:  '#'
  },
  {
    car:   'medias/T-ROC.png',
    logo:  'medias/Tingis.png',
    title: 'Volkswagen T-Roc',
    desc:  'SUV compacto con diseño dinámico · Motor TSI 150 CV · DSG 7 velocidades · Conectividad total · Acabado Sport.',
    link:  '#'
  },
  {
    car:   'medias/Audi RS q8.png',
    logo:  'medias/GT speed.png',
    title: 'Audi RS Q8',
    desc:  'El SUV coupé más potente de Audi · V8 biturbo 600 CV · Quattro AWD · 0–100 en 3,8 s · RS Sport Exhaust.',
    link:  '#'
  },
  {
    car:   'medias/Dacia.png',
    logo:  'medias/Dercaoui.png',
    title: 'Dacia Duster',
    desc:  'El SUV más accesible del mercado · Motor TCe 130 · 4x4 disponible · Equipamiento completo · Mejor relación calidad-precio.',
    link:  '#'
  },
  {
    car:   'medias/Promocion.png',
    logo:  null,                     // este thumb no tiene logo
    title: 'Ofertas especiales',
    desc:  'Descubre nuestras promociones exclusivas de temporada · Financiación sin intereses · Hasta –30% en modelos seleccionados.',
    link:  '#'
  },
];

// ── REFERENCIAS AL DOM ────────────────────────
const heroImg   = document.querySelector('.hero-car-img');
const heroLogo  = document.querySelector('.hero-inner .logo');
const heroTitle = document.querySelector('.hero-title');
const heroDesc  = document.querySelector('.hero-desc');
const heroBtn   = document.querySelector('.btn-hero-primary');
const thumbs    = document.querySelectorAll('.hero-thumb');

// ── ESTADO ────────────────────────────────────
let current     = 4;          // índice activo al cargar (el último = Promoción)
let autoTimer   = null;
const AUTO_DELAY = 5000;       // ms entre slides automáticos — CAMBIA AQUÍ el tiempo

// ── ANIMACIÓN DE ENTRADA ──────────────────────
function animateIn() {
  // Selecciona los elementos que animas
  const targets = [heroImg, heroTitle, heroDesc, heroBtn];
  if (heroLogo) targets.unshift(heroLogo);

  targets.forEach((el, i) => {
    if (!el) return;
    el.style.transition = 'none';
    el.style.opacity    = '0';
    el.style.transform  = i === 0
      ? 'translateX(40px)'    // la imagen entra desde la derecha
      : 'translateY(16px)';   // el texto sube desde abajo

    // Forzar reflow para que el navegador "vea" el estado inicial
    el.getBoundingClientRect();

    el.style.transition = `opacity .45s ease ${i * 80}ms, transform .45s ease ${i * 80}ms`;
    el.style.opacity    = '1';
    el.style.transform  = 'none';
  });
}

// ── CAMBIAR SLIDE ─────────────────────────────
function goTo(index) {
  if (index === current) return;
  current = index;

  const s = slides[index];

  // Actualizar clases de thumbs
  thumbs.forEach((t, i) => t.classList.toggle('active', i === index));

  // Actualizar contenido del hero
  heroImg.src   = s.car;
  heroTitle.textContent = s.title;
  heroDesc.textContent  = s.desc;
  heroBtn.href          = s.link || '#';

  if (heroLogo) {
    if (s.logo) {
      heroLogo.src = s.logo;
      heroLogo.style.display = 'block';
    } else {
      heroLogo.style.display = 'none';
    }
  }

  // Lanzar animación
  animateIn();
}

// ── CLICKS EN THUMBNAILS ──────────────────────
thumbs.forEach((thumb, i) => {
  thumb.addEventListener('click', () => {
    resetAuto();   // reiniciar el timer automático al hacer click manual
    goTo(i);
  });
});

// ══════════════════════════════════════════════
//  DESFILE AUTOMÁTICO — para pausarlo, comenta
//  las tres líneas marcadas con /* AUTO */
// ══════════════════════════════════════════════
function startAuto() {                             /* AUTO */
  autoTimer = setInterval(() => {                  /* AUTO */
    goTo((current + 1) % slides.length);           /* AUTO */
  }, AUTO_DELAY);                                  /* AUTO */
}                                                  /* AUTO */

function resetAuto() {                             /* AUTO */
  clearInterval(autoTimer);                        /* AUTO */
  startAuto();                                     /* AUTO */
}                                                  /* AUTO */

// Pausar al pasar el ratón por encima del hero
document.querySelector('.hero').addEventListener('mouseenter', () => clearInterval(autoTimer));   /* AUTO */
document.querySelector('.hero').addEventListener('mouseleave', startAuto);                        /* AUTO */
document.querySelector('.hero-thumbs').addEventListener('mouseenter', () => clearInterval(autoTimer)); /* AUTO */
document.querySelector('.hero-thumbs').addEventListener('mouseleave', startAuto);                 /* AUTO */

// ── INIT ──────────────────────────────────────
animateIn();   // animación inicial de la slide activa
startAuto();   /* AUTO — comenta esta línea para desactivar el desfile */

</script>
