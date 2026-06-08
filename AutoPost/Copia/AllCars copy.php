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
    <div class="categories-inner" id="first">
              <button class="cat-pill" id="marque">Marque</button>
              <button class="cat-pill" id="categorie">Categorie</button>
              <button class="cat-pill" id="portes">Portes</button> 
    </div>
        <div class="categories-inner-second oculto" id="second">
              <button class="cat-pill active" id="marque">Marque</button>
          
      <?php

try{
$sql = "SELECT marque FROM specs group by marque";
$stmt = $pdo->query($sql);
$tags = $stmt->fetchAll(PDO::FETCH_ASSOC);
}catch(PDOException $e){
    echo "Error: " . $e->getMessage();
} 
foreach ($tags as $tag) : ?>
  <?php foreach ($tag as $key => $value) : ?>
    
    <?php if ($key === 'marque') : ?>
      <button class="cat-pill" id="<?php echo $key; ?>">
        <?php echo $value; ?>
      </button>
    <?php endif; ?>

  <?php endforeach; ?>
<?php endforeach; ?>  
    </div>
  </div>
  <div class="filtrados" id="filtrados">
    <div class="lista" id="lista">
    </div>
</div>
      <div class="grid-4" id="grid">
 
        <!-- Card 1 -->


        

   <?php
   /* =========================
    CONSULTA SQL
   ========================= */

try{
$sql = "SELECT * FROM specs";
$stmt = $pdo->query($sql);
$cards = $stmt->fetchAll(PDO::FETCH_ASSOC);
}catch(PDOException $e){
    echo "Error: " . $e->getMessage();
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

  <script>
    let array = [];
    const first = document.getElementById('first');
    const second = document.getElementById('second');
    const filtrados = document.getElementById('lista')
    const contenedor = document.getElementById('grid')
    let choix = document.getElementById('choix');
    let tags = document.querySelectorAll('.categories-inner .cat-pill');
      tags.forEach(tag => {
    tag.addEventListener('click', () => {
    mostrar()
    });
  });
      let choises = document.querySelectorAll('.categories-inner-second .cat-pill');
  

      choises.forEach(choice => {
    choice.addEventListener('click', () => {
      if(choice.textContent=="Marque"){
        mostrar()
      }else{
        let texto= choice.innerText;
        choice.classList.toggle("active")
        spawn(texto)
        link();
      }
    
    });
  });
      let decision = document.querySelectorAll('.filtrados .lista .cat-pill');
      decision.forEach(opcion => {
    opcion.addEventListener('click', () => {
    opcion.remove();
    });
  });

  


function mostrar(){
    
    first.classList.toggle("oculto");
    second.classList.toggle("oculto");
}

  function spawn(p){
let button = document.createElement("button")
button.classList.add("cat-pill");
button.id= 'choix'
button.innerText= p

  filtrados.appendChild(button)
}

  function spanwCard(p){
let button = document.createElement("button")
button.classList.add("cat-pill");
button.innerText= p

  filtrados.appendChild(button)
}

  function link(){
    let marcasSeleccionadas = [];

    document.querySelectorAll('.categories-inner-second .cat-pill.active').forEach(box => {
        if(box.innerHTML=='Marque'){
          return;
        }
        else if(box.id === 'marque') marcasSeleccionadas.push(box.innerText);
    });
       let datos = new FormData();
    datos.append('marcas', JSON.stringify(marcasSeleccionadas));

    fetch('get_cars.php', {
        method: 'POST',
        body: datos
    })
    .then(respuesta => respuesta.json()) // Recibimos la respuesta en formato JSON
    .then(coches => {
        // 3. Dibujar los coches en la pantalla
        let html = '';
        if(coches.length > 0) {
            coches.forEach(card => {
                // Aquí construyes tu tarjeta de coche
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
      <span class="car-spec">${card.Kilometrage} km</span>
      <span class="car-spec">${card.Carburant}</span>
    </div>
    <div class="car-footer">
      <div>
        <div class="car-price">${card.Prix} MAD</div>
      </div>
      <span class="car-location">📍 Tánger</span>
    </div>
  </div>
</div>
`;
            });
        } else {
            html = '<p>No se encontraron vehículos con esos filtros.</p>';
        }
        document.getElementById('grid').innerHTML = html;
    });
}

// Llamar a la función al cargar la página para mostrar todos los coches inicialmente
link();
</script>


    </script>