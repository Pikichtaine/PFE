<?php
require 'Database.php';

// Sacar todas las marcas
try{
    $sql = "SELECT marque FROM specs group by marque ORDER BY marque";
    $stmt = $pdo->query($sql);
    $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);
}catch(PDOException $e){
    echo "Error: " . $e->getMessage();
}

// Sacar todos los combustibles
try {
    $sqlCarb = "SELECT DISTINCT Carburant FROM specs WHERE Carburant IS NOT NULL ORDER BY Carburant";
    $carburantes = $pdo->query($sqlCarb)->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $carburantes = [];
}
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
<!-- ═══════════════════ ALL CARS Y FILTROS ════════════ -->
<section class="section">
  <div class="container">
    
    <div class="section-header">
      <h2 class="section-title all-cars">All Cars</h2>
    </div>
    <!-- Barra de filtros -->
    <div class="categories-wrap all-cars">
      <div class="categories-inner" id="filter-bar">
          <span class="cat-label">MARCA</span>
          <?php foreach ($tags as $tag) : ?>
              <button class="cat-pill"
                      data-type="marque"
                      data-value="<?php echo htmlspecialchars($tag['marque']); ?>">
                  <?php echo htmlspecialchars($tag['marque']); ?>
              </button>
          <?php endforeach; ?>
          
          <div class="filter-sep"></div>
          
          <span class="cat-label">COMBUSTIBLE</span>
          <?php foreach ($carburantes as $c) : ?>
              <button class="cat-pill"
                      data-type="carburant"
                      data-value="<?php echo htmlspecialchars($c['Carburant']); ?>">
                  <?php echo htmlspecialchars($c['Carburant']); ?>
              </button>
          <?php endforeach; ?>
      </div>
    </div>
    
    <!-- Chips de filtros activos (oculto por defecto) -->
    <div class="filtrados" id="filtrados" style="display:none;">
        <div class="lista" id="lista"></div>
    </div>
    <div class="grid-4" id="grid">
      <?php
      /* =========================
         CARGA INICIAL CON PHP
      ========================= */
      try {
          $sql = "SELECT * FROM specs";
          $stmt = $pdo->query($sql);
          $cards = $stmt->fetchAll(PDO::FETCH_ASSOC);
      } catch(PDOException $e) {
          echo "Error: " . $e->getMessage();
          $cards = [];
      }
      if (!empty($cards)):
          foreach ($cards as $card) : ?>
              <div class="car-card" id="<?php echo htmlspecialchars($card['id']); ?>">
                <div class="car-card-img">
                  <!-- Añadimos htmlspecialchars por seguridad -->
                  <img src="<?php echo htmlspecialchars($card['Photo1']); ?>" alt="<?php echo htmlspecialchars($card['marque']); ?>"/>
                </div>
                <span class="car-fav">♡</span>
                <div class="car-card-body">
                  <div class="car-brand"><?php echo htmlspecialchars($card['marque']); ?></div>
                  <div class="car-name"><?php echo htmlspecialchars($card['modele'] . " " . $card['Version']); ?></div>
                  <div class="car-specs">
                    <span class="car-spec"><?php echo htmlspecialchars($card['Annee']); ?></span>
                    <!-- Formato de miles para Kilometraje -->
                    <span class="car-spec"><?php echo number_format($card['Kilometrage'], 0, ',', '.'); ?> km</span>
                    <span class="car-spec"><?php echo htmlspecialchars($card['Carburant']); ?></span>
                  </div>
                  <div class="car-footer">
                    <div>
                      <!-- Formato de miles para el Precio -->
                      <div class="car-price"><?php echo number_format($card['Prix'], 0, ',', '.'); ?> MAD</div>
                    </div>
                    <span class="car-location">📍 Tánger</span>
                  </div>
                </div>
              </div>
          <?php endforeach; 
      else: ?>
          <p style="grid-column: 1/-1; text-align: center; color: var(--txt3);">No hay vehículos registrados aún.</p>
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
  let cards = document.querySelectorAll('.car-card');
  cards.forEach(card => {
    card.addEventListener('click', () => {
      window.location.href = "Car.php?id=" + card.id;
    });
  });
// Usamos const en lugar de var para mejores prácticas en JS moderno
const pills = document.querySelectorAll('#filter-bar .cat-pill');

// Activar/desactivar cada pill al hacer click
pills.forEach(pill => {
    pill.addEventListener('click', () => {
        pill.classList.toggle('active');
        actualizarChips();
        link();
    });
});

// Reconstruye los chips de "filtros activos"
function actualizarChips() {
    const lista = document.getElementById('lista');
    const filtrados = document.getElementById('filtrados');
    lista.innerHTML = '';

    const activos = document.querySelectorAll('#filter-bar .cat-pill.active');

    if (activos.length === 0) {
        filtrados.style.display = 'none';
        return;
    }

    filtrados.style.display = 'block';

    // Botón "Limpiar todo"
    const limpiar = document.createElement('button');
    limpiar.classList.add('cat-pill', 'limpiar-btn');
    limpiar.innerText = '✕ Limpiar todo';
    limpiar.addEventListener('click', () => {
        document.querySelectorAll('#filter-bar .cat-pill.active').forEach(p => p.classList.remove('active'));
        actualizarChips();
        link();
    });
    lista.appendChild(limpiar);

    // Un chip por cada filtro activo
    activos.forEach(pill => {
        const chip = document.createElement('button');
        chip.classList.add('cat-pill', 'chip-activo');
        chip.innerHTML = `${pill.dataset.value} <span>×</span>`;
        chip.addEventListener('click', () => {
            pill.classList.remove('active');
            actualizarChips();
            link();
        });
        lista.appendChild(chip);
    });
}

// Manda los filtros al servidor y actualiza el grid
function link() {
    const marcas = [];
    const combustibles = [];

    document.querySelectorAll('#filter-bar .cat-pill.active').forEach(pill => {
        if (pill.dataset.type === 'marque') marcas.push(pill.dataset.value);
        if (pill.dataset.type === 'carburant') combustibles.push(pill.dataset.value);
    });

    const datos = new FormData();
    datos.append('marcas', JSON.stringify(marcas));
    datos.append('combustibles', JSON.stringify(combustibles));

    // Formateador de números de JavaScript para poner los puntos en precios y kilómetros
    const formatter = new Intl.NumberFormat('es-ES', { maximumFractionDigits: 0 });

    fetch('get_cars.php', { method: 'POST', body: datos })
        .then(r => r.json())
        .then(coches => {
            let html = '';
            if (coches.length > 0) {
                coches.forEach(card => {
                    // Aquí usamos el formateador para replicar el `number_format` de PHP
                    const precioFormateado = formatter.format(card.Prix);
                    const kmFormateado = formatter.format(card.Kilometrage);

                    html += `
<div class="car-card">
  <div class="car-card-img">
    <img src="${card.Photo1}" alt="${card.marque}"/>
  </div>
  <span class="car-fav">♡</span>
  <div class="car-card-body">
    <div class="car-brand">${card.marque}</div>
    <div class="car-name">${card.modele} ${card.Version}</div>
    <div class="car-specs">
      <span class="car-spec">${card.Annee}</span>
      <span class="car-spec">${kmFormateado} km</span>
      <span class="car-spec">${card.Carburant}</span>
    </div>
    <div class="car-footer">
      <div><div class="car-price">${precioFormateado} MAD</div></div>
      <span class="car-location">📍 Tánger</span>
    </div>
  </div>
</div>`;
                });
            } else {
                html = '<p style="color:var(--txt3);padding:30px;text-align:center;grid-column:1/-1;">No se encontraron vehículos con estos filtros.</p>';
            }
            document.getElementById('grid').innerHTML = html;
        })
        .catch(error => console.error('Error al filtrar los coches:', error));
}


</script>