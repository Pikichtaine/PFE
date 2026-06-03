
<?php
require 'Database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<title>Legendary Motorsport</title>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="CSS/Shop.css"/>
</head>
<body>

<!-- ── NAV ── -->
<nav>
  <div class="nav-logo">
    <div class="nav-logo-dot"></div>
    Legendary Motorsport
  </div>
  <div class="nav-links">
    <div class="nav-link active">Inventory</div>
    <div class="nav-link">About</div>
  </div>
  <div class="nav-spacer"></div>
  <div class="nav-search">
    <span class="search-icon">⌕</span>
    <input type="text" placeholder="Search vehicles…" readonly/>
  </div>
  <div class="wallet-chip">
    <span class="amt">❤️</span>
  </div>
  <button class="nav-cta">🛒</button>
  <button class="nav-cta">☀️</button>
</nav>

<!-- ── HERO ── -->
<section class="hero">
  <div class="hero-media"></div>
  <div class="hero-overlay"></div>
  <div class="hero-side-grad"></div>

  <div class="hero-content">
    
    <h1 class="hero-title">
      Legendary<br>
      <span class="hero-title-sub">Motorsport</span>
    </h1>
    <p class="hero-desc">
      The finest selection of high-performance vehicles. Curated for those who demand nothing less than exceptional.
    </p>
    
  </div>

</section>

<!-- ── SHOP ── -->
<section class="shop">
  <div class="section-header">
    <div></div>
    <div class="filters">
      <div class="filter-btn">Featured</div>
      <div class="filter-btn on">2 Door</div>
      <div class="filter-btn">4 Door</div>
      <div class="filter-btn">Motorcycles</div>
      <div class="filter-btn">Special</div>
    </div>
    <div class="sort-row">
      <select class="sort-select">
        <option>Price: Low–High</option>
        <option>Price: High–Low</option>
        <option>Newest</option>
      </select>
    </div>
  </div>

  <div class="grid">

<?php


/* =========================
    CONSULTA SQL
   ========================= */
try{

$sql = "SELECT *
FROM specs";

$stlt = $pdo->query($sql);
$cards = $stlt->fetchAll(PDO::FETCH_ASSOC);

}catch(PDOException $e){
    echo "Erreur type: " . $e->getMessage();
}

foreach ($cards as $card) : ?>

        <div class="card" id="<?php echo $card['id'] ?>">
      <div class="card-img">
        <img src= "<?php echo $card['Photo1'] ?>" alt="<?php echo $card['marque'] ?>"/>
        <div class="card-img-top">
        </div>
      </div>
      <div class="card-body">
        
          <div class="card-name"><?php echo $card['marque'] . ' ' . $card['modele'] . ' ' . $card['Version'] ?></div>
          
            <div class="card-price"><?php echo $card['Prix'] . "mad" ?></div>

      </div>
    </div>

    <?php endforeach; ?>



  </div>
</section>



<!-- ── FOOTER ── -->
<footer>
  <div class="footer-logo">
    <div class="nav-logo-dot"></div>
    Legendary Motorsport
  </div>
  <div class="footer-links">
    <span class="footer-link">Inventory</span>
    <span class="footer-link">Finance</span>
    <span class="footer-link">Trade-In</span>
    <span class="footer-link">Service</span>
    <span class="footer-link">Contact</span>
    <span class="footer-link">Privacy</span>
  </div>
  <div class="footer-copy">© 2026 Legendary Motorsport. All rights reserved.</div>
</footer>

</body>
</html>
<script>
  let cards = document.querySelectorAll('.card');
  cards.forEach(card => {
    card.addEventListener('click', () => {
      window.location.href = "Car.php?id=" + card.id;
    });
  });
</script>
