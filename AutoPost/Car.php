<?php
require 'Database.php';

try {
  $stmt = $pdo->prepare("SELECT * FROM specs WHERE id = :id");
  $stmt->bindParam(':id', $_GET['id']);
  $stmt->execute();
  $cards = $stmt->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
  echo 'Database error: ' . $e->getMessage();
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<title><?php echo $cards['marque'] . ' ' . $cards['modele'] . ' ' . $cards['Version'] ?> — Legendary Motorsport</title>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="CSS/Car.css"/>
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
<div style="display: flex; gap: 12px;">
  <div class="nav-search">
    <span class="search-icon">⌕</span>
    <input type="text" placeholder="Search vehicles…" readonly/>
  </div>
  <button class="nav-cta">❤️</button>

  <button class="nav-cta">🛒</button>
  
  <button class="nav-cta">☀️</button>
</div>
</nav>

<!-- ── MAIN PAGE ── -->
<main class="page">

  <!-- LEFT -->
  <div class="left">

    <div class="car-header">
      <div class="car-brand-row">
        <span class="car-brand-badge"><?php echo $cards['marque'] ?></span>
      </div>
      <p class="car-full-name"><?php echo $cards['marque'] . ' ' . $cards['modele'] . ' ' . $cards['Version'] ?></p>
    </div>

    <!-- Price & CTA -->
    <div class="price-row">
      <div>
        <span class="price-tag">Starting from</span>
        <div class="price-main"><?php echo $cards['Prix'] . ' MAD' ?></div>
      </div>
    </div>
    <div class="price-cta-row" style="margin-bottom:32px">
      <button class="btn-buy">Purchase Vehicle</button>
      <button class="btn-save">♡</button>
    </div>

    <!-- Description -->
    <div class="section-label">About this vehicle</div>
    <p class="car-desc">
      <?php echo $cards['Description'] ?>
    </p>

    <!-- Stats -->
    <!-- Spec chips -->
      <div class="spec-chip">

      <div class="group">
        <div class="spec-chip-label">Top Speed</div>
        <div class="spec-chip-val"><?php echo $cards['VitesseMax'] . ' km/h' ?></div>
      </div>

      <div class="group">
        <div class="spec-chip-label">0–100 mph</div>
        <div class="spec-chip-val"><?php echo $cards['Acceleration'] . ' s' ?></div>
      </div>      

      <div class="group">
        <div class="spec-chip-label">Puissance</div>
        <div class="spec-chip-val"><?php echo $cards['Puissance'] ?></div>
      </div>

<hr class="linea">
      
      <div class="group">
        <div class="spec-chip-label">Class</div>
        <div class="spec-chip-val"><?php echo $cards['Categorie'] ?></div>
      </div>

      <div class="group">
        <div class="spec-chip-label">Drivetrain</div>
        <div class="spec-chip-val"><?php echo $cards['Transmission'] . ' ' . $cards['Carburant'] ?></div>
      </div>
      
      <div class="group">
        <div class="spec-chip-label">Manufacture Year</div>
        <div class="spec-chip-val"><?php echo $cards['Annee'] ?></div>
      </div>

    </div>






    



  </div><!-- /left -->

  <!-- RIGHT -->
  <div class="right">
    <div class="gallery">

      <!-- Main image -->
      <div class="gallery-main">
        <img
          src="<?php echo $cards['Photo1'] ?>"
          alt="Pfister 811 exterior"/>
      </div>

      <!-- Thumbnails -->
      <div class="gallery-thumbs">
        <div class="gallery-thumb active">
          <img
            src="<?php echo $cards['Photo2'] ?>"
            alt="Interior dashboard"/>
        </div>
        <div class="gallery-thumb">
          <img
            src="<?php echo $cards['Photo3'] ?>"
            alt="Side profile detail"/>
        </div>
      </div>
    <div class="tags-group">
        <span class="price-tag">Tags</span>
      <div class="tags-collection">
        <span class="car-brand-badge" id="<?php echo $cards['marque'] ?>"><?php echo $cards['marque'] ?></span>
      </div>

        </div>
      </div>
    </div>
    </div><!-- /gallery sticky -->

  </div><!-- /right -->

</main>

<!-- ── RELATED ── -->
<section class="related">
  <div class="related-header">
    <h2 class="related-title">More <em>Supercars</em></h2>
  </div>
  <div class="related-grid">

    <div class="r-card">
      <div class="r-card-img">
        <img src="https://images.unsplash.com/photo-1525609004556-c46c7d6cf023?w=600&q=80" alt="Jester RR"/>
      </div>
      <div class="r-card-body">
        <div class="r-card-name">Dinka Jester RR Widebody</div>
        <div class="r-card-cat">Sports · Twin-Turbo V8</div>
        <div class="r-card-footer">
          <span class="r-card-price">$2,290,000</span>
          <div class="r-card-btn">→</div>
        </div>
      </div>
    </div>

    <div class="r-card">
      <div class="r-card-img">
        <img src="https://images.unsplash.com/photo-1494976388531-d1058494cdd8?w=600&q=80" alt="Pipistrello"/>
      </div>
      <div class="r-card-body">
        <div class="r-card-name">Overflod Pipistrello</div>
        <div class="r-card-cat">Hyper · Mid-Engine V12</div>
        <div class="r-card-footer">
          <span class="r-card-price" style="color:var(--green)">$2,065,000</span>
          <div class="r-card-btn">→</div>
        </div>
      </div>
    </div>

    <div class="r-card">
      <div class="r-card-img">
        <img src="https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?w=600&q=80" alt="Infernus"/>
      </div>
      <div class="r-card-body">
        <div class="r-card-name">Pegassi Infernus Classic</div>
        <div class="r-card-cat">Sports Classic · NA V12</div>
        <div class="r-card-footer">
          <span class="r-card-price">$915,000</span>
          <div class="r-card-btn">→</div>
        </div>
      </div>
    </div>

  </div>

</section>

<!-- ── FOOTER ── -->
<footer>
  <div class="footer-logo">
    <div class="nav-logo-dot"></div>
    Legendary Motorsport
  </div>

  <div class="footer-copy">© 2026 Legendary Motorsport. All rights reserved.</div>
</footer>

</body>
</html>
<script>
  let tags = document.querySelectorAll('.car-brand-badge');
  tags.forEach(tag => {
    tag.addEventListener('click', () => {
      window.location.href = "AllCars.php?id=" + tag.id;
    });
  });
</script>