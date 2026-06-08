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
      
      <button class="btn-primary" id="btn-connexion">Connexion</button>
    </div>
  </nav>
  
  <!-- ═══════════════════ POPULAR DEALERS ══════════════ -->
  <section class="section">
    <div class="container">
      <div class="section-header">
        <h2 class="section-title all-cars">Concesionarios populares</h2>
        <a href="#" class="section-link">Ver todos →</a>
      </div>
 
      <div class="grid-3">
 
        <div class="dealer-card">
          <div class="dealer-hero-img">
          <img src="medias/AutoHaus.png" alt="AutoHaus"/>
          </div>
          <div class="dealer-body" style="padding-top:34px;">
            <div class="dealer-name">AutoHaus Tánger</div>
            <div class="dealer-meta">
              <span>📍 Boulevard Mohammed VI, Tánger</span>
            </div>
            <div class="dealer-stats">
              <div class="dealer-stat">
                <div class="dealer-stat-val">142</div>
                <div class="dealer-stat-label">Coches</div>
              </div>
              <div class="dealer-stat">
                <div class="dealer-stat-val">4.9</div>
                <div class="dealer-stat-label">Valoración</div>
              </div>
              <div class="dealer-stat">
                <div class="dealer-stat-val">12</div>
                <div class="dealer-stat-label">Marcas</div>
              </div>
            </div>
          </div>
        </div>
 
        <div class="dealer-card">
          <div class="dealer-hero-img dg2">
            <svg style="position:absolute;bottom:12px;left:50%;transform:translateX(-50%);width:75%;height:65%;opacity:.18;" viewBox="0 0 200 80" fill="white" xmlns="http://www.w3.org/2000/svg">
              <path d="M6 56 C6 56 22 26 58 18 C78 13 122 13 142 18 C166 26 184 50 191 56 L194 63 L6 63 Z"/>
              <ellipse cx="43" cy="65" rx="14" ry="14"/>
              <ellipse cx="157" cy="65" rx="14" ry="14"/>
            </svg>
          </div>
          <div class="dealer-body" style="padding-top:34px;">
            <div class="dealer-name">PremiumMotors Casablanca</div>
            <div class="dealer-meta">
              <span>📍 Quartier des Affaires, Casablanca</span>
            </div>
            <div class="dealer-stats">
              <div class="dealer-stat">
                <div class="dealer-stat-val">89</div>
                <div class="dealer-stat-label">Coches</div>
              </div>
              <div class="dealer-stat">
                <div class="dealer-stat-val">4.7</div>
                <div class="dealer-stat-label">Valoración</div>
              </div>
              <div class="dealer-stat">
                <div class="dealer-stat-val">7</div>
                <div class="dealer-stat-label">Marcas</div>
              </div>
            </div>
          </div>
        </div>
 
        <div class="dealer-card">
          <div class="dealer-hero-img dg3">
            <svg style="position:absolute;bottom:12px;left:50%;transform:translateX(-50%);width:75%;height:65%;opacity:.18;" viewBox="0 0 200 80" fill="white" xmlns="http://www.w3.org/2000/svg">
              <path d="M8 57 C8 57 24 27 58 19 C78 14 122 14 142 19 C166 27 183 50 190 57 L194 63 L6 63 Z"/>
              <ellipse cx="44" cy="65" rx="14" ry="14"/>
              <ellipse cx="156" cy="65" rx="14" ry="14"/>
            </svg>
          </div>
          <div class="dealer-body" style="padding-top:34px;">
            <div class="dealer-name">Marrakech Ride</div>
            <div class="dealer-meta">
              <span>📍 Avenue Mohammed V, Marrakech</span>
            </div>
            <div class="dealer-stats">
              <div class="dealer-stat">
                <div class="dealer-stat-val">211</div>
                <div class="dealer-stat-label">Coches</div>
              </div>
              <div class="dealer-stat">
                <div class="dealer-stat-val">4.8</div>
                <div class="dealer-stat-label">Valoración</div>
              </div>
              <div class="dealer-stat">
                <div class="dealer-stat-val">18</div>
                <div class="dealer-stat-label">Marcas</div>
              </div>
            </div>
          </div>
        </div>

                <div class="dealer-card">
          <div class="dealer-hero-img dg1">
            <svg style="position:absolute;bottom:12px;left:50%;transform:translateX(-50%);width:75%;height:65%;opacity:.18;" viewBox="0 0 200 80" fill="white" xmlns="http://www.w3.org/2000/svg">
              <path d="M5 58 C5 58 25 28 60 20 C80 15 120 15 140 20 C165 28 185 50 192 58 L195 64 L5 64 Z"/>
              <ellipse cx="44" cy="66" rx="14" ry="14"/>
              <ellipse cx="156" cy="66" rx="14" ry="14"/>
            </svg>
          </div>
          <div class="dealer-body" style="padding-top:34px;">
            <div class="dealer-name">AutoHaus Tánger</div>
            <div class="dealer-meta">
              <span>📍 Boulevard Mohammed VI, Tánger</span>
            </div>
            <div class="dealer-stats">
              <div class="dealer-stat">
                <div class="dealer-stat-val">142</div>
                <div class="dealer-stat-label">Coches</div>
              </div>
              <div class="dealer-stat">
                <div class="dealer-stat-val">4.9</div>
                <div class="dealer-stat-label">Valoración</div>
              </div>
              <div class="dealer-stat">
                <div class="dealer-stat-val">12</div>
                <div class="dealer-stat-label">Marcas</div>
              </div>
            </div>
          </div>
        </div>
 
        <div class="dealer-card">
          <div class="dealer-hero-img dg2">
            <svg style="position:absolute;bottom:12px;left:50%;transform:translateX(-50%);width:75%;height:65%;opacity:.18;" viewBox="0 0 200 80" fill="white" xmlns="http://www.w3.org/2000/svg">
              <path d="M6 56 C6 56 22 26 58 18 C78 13 122 13 142 18 C166 26 184 50 191 56 L194 63 L6 63 Z"/>
              <ellipse cx="43" cy="65" rx="14" ry="14"/>
              <ellipse cx="157" cy="65" rx="14" ry="14"/>
            </svg>
          </div>
          <div class="dealer-body" style="padding-top:34px;">
            <div class="dealer-name">PremiumMotors Casablanca</div>
            <div class="dealer-meta">
              <span>📍 Quartier des Affaires, Casablanca</span>
            </div>
            <div class="dealer-stats">
              <div class="dealer-stat">
                <div class="dealer-stat-val">89</div>
                <div class="dealer-stat-label">Coches</div>
              </div>
              <div class="dealer-stat">
                <div class="dealer-stat-val">4.7</div>
                <div class="dealer-stat-label">Valoración</div>
              </div>
              <div class="dealer-stat">
                <div class="dealer-stat-val">7</div>
                <div class="dealer-stat-label">Marcas</div>
              </div>
            </div>
          </div>
        </div>
 
        <div class="dealer-card">
          <div class="dealer-hero-img dg3">
            <svg style="position:absolute;bottom:12px;left:50%;transform:translateX(-50%);width:75%;height:65%;opacity:.18;" viewBox="0 0 200 80" fill="white" xmlns="http://www.w3.org/2000/svg">
              <path d="M8 57 C8 57 24 27 58 19 C78 14 122 14 142 19 C166 27 183 50 190 57 L194 63 L6 63 Z"/>
              <ellipse cx="44" cy="65" rx="14" ry="14"/>
              <ellipse cx="156" cy="65" rx="14" ry="14"/>
            </svg>
          </div>
          <div class="dealer-body" style="padding-top:34px;">
            <div class="dealer-name">Marrakech Ride</div>
            <div class="dealer-meta">
              <span>📍 Avenue Mohammed V, Marrakech</span>
            </div>
            <div class="dealer-stats">
              <div class="dealer-stat">
                <div class="dealer-stat-val">211</div>
                <div class="dealer-stat-label">Coches</div>
              </div>
              <div class="dealer-stat">
                <div class="dealer-stat-val">4.8</div>
                <div class="dealer-stat-label">Valoración</div>
              </div>
              <div class="dealer-stat">
                <div class="dealer-stat-val">18</div>
                <div class="dealer-stat-label">Marcas</div>
              </div>
            </div>
          </div>
        </div>
 
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
    window.location.href = "AllCars.php?id=" + coches.id;;
  });
</script>
