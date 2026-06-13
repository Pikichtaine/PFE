<?php
require 'Database.php';
// Récupérer l'ID depuis l'URL
$id = $_GET['id'] ?? null;
if (!$id) {
    die("ID de voiture manquant. Assurez-vous d'accéder à cette page avec un paramètre ?id=...");
}
// Récupérer les données actuelles de la voiture
$stmt = $pdo->prepare("SELECT * FROM specs WHERE id = :id");
$stmt->execute([':id' => $id]);
$car = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$car) {
    die("Voiture introuvable dans la base de données.");
}
$message = "";
if($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['marque'], $_POST['modele'], $_POST['version'], $_POST['year'], $_POST['portes'], $_POST['class'], $_POST['transmission'], $_POST['carburant'], $_POST['boite'], $_POST['prix'], $_POST['kilometrage'], $_POST['vitesse_max'], $_POST['acceleration'], $_POST['puissance'], $_POST['consommation'], $_POST['description'])) {
        
        $marque = trim($_POST['marque']);
        $modele = trim($_POST['modele']);
        $version = trim($_POST['version']);
        $year = trim($_POST['year']);
        $portes = trim($_POST['portes']);
        $class = trim($_POST['class']);
        $transmission = trim($_POST['transmission']);
        $carburant = trim($_POST['carburant']);
        $boite = trim($_POST['boite']);
        $prix = trim($_POST['prix']);
        $kilometrage = trim($_POST['kilometrage']);
        $vitesse_max = trim($_POST['vitesse_max']);
        $acceleration = trim($_POST['acceleration']);
        $puissance = trim($_POST['puissance']);
        $consommation = trim($_POST['consommation']);
        $descripcion = trim($_POST['description']);
        $carpeta = "imagenes/";
        if(!is_dir($carpeta)) {
            mkdir($carpeta, 0755, true);
        }
        function subirFoto($file, $carpeta) {
            if(isset($file) && $file['error'] == 0) {
                $nombre = $file['name'];
                $tmp = $file['tmp_name'];
                $ruta = $carpeta . uniqid() . "_" . $nombre;
                if(move_uploaded_file($tmp, $ruta)) {
                    return $ruta;
                }
            }
            return null;
        }
        // On upload seulement si une nouvelle image a été sélectionnée
        $foto1 = subirFoto($_FILES['photo_1'], $carpeta);
        $foto2 = subirFoto($_FILES['photo_2'], $carpeta);
        $foto3 = subirFoto($_FILES['photo_3'], $carpeta);
        try {
            // Requête UPDATE au lieu de INSERT
            $sql = "UPDATE specs SET 
                marque = :marque, modele = :modele, Version = :Version, Annee = :Annee, 
                Portes = :Portes, Categorie = :Categorie, Transmission = :Transmission, 
                Carburant = :Carburant, Boite = :Boite, Prix = :Prix, Kilometrage = :Kilometrage, 
                VitesseMax = :VitesseMax, Acceleration = :Acceleration, Puissance = :Puissance, 
                Consommation = :Consommation, Description = :Description";
            // Ajout conditionnel des photos (on ne remplace que si une nouvelle image est envoyée)
            if ($foto1) $sql .= ", Photo1 = :Photo1";
            if ($foto2) $sql .= ", Photo2 = :Photo2";
            if ($foto3) $sql .= ", Photo3 = :Photo3";
            $sql .= " WHERE id = :id";
            $stmtUpdate = $pdo->prepare($sql);
            $stmtUpdate->bindParam(':marque', $marque);
            $stmtUpdate->bindParam(':modele', $modele);
            $stmtUpdate->bindParam(':Version', $version);
            $stmtUpdate->bindParam(':Annee', $year);
            $stmtUpdate->bindParam(':Portes', $portes);
            $stmtUpdate->bindParam(':Categorie', $class);
            $stmtUpdate->bindParam(':Transmission', $transmission);
            $stmtUpdate->bindParam(':Carburant', $carburant);
            $stmtUpdate->bindParam(':Boite', $boite);
            $stmtUpdate->bindParam(':Prix', $prix);
            $stmtUpdate->bindParam(':Kilometrage', $kilometrage);
            $stmtUpdate->bindParam(':VitesseMax', $vitesse_max);
            $stmtUpdate->bindParam(':Acceleration', $acceleration);
            $stmtUpdate->bindParam(':Puissance', $puissance);
            $stmtUpdate->bindParam(':Consommation', $consommation);
            $stmtUpdate->bindParam(':Description', $descripcion);
            $stmtUpdate->bindParam(':id', $id);
            if ($foto1) $stmtUpdate->bindParam(':Photo1', $foto1);
            if ($foto2) $stmtUpdate->bindParam(':Photo2', $foto2);
            if ($foto3) $stmtUpdate->bindParam(':Photo3', $foto3);
            $stmtUpdate->execute();
            header("Location: Profil_Dealer.php");
            // Rafraîchir les données de $car pour afficher les modifications dans le formulaire
            $stmt = $pdo->prepare("SELECT * FROM specs WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $car = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            $message = "Erreur: " . $e->getMessage();
        }
    } else {
        $message = "Tous les champs obligatoires doivent être complétés";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Modifier une Voiture</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=Libre+Baskerville:wght@700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css" />
  <style>
    .message-banner { padding: 15px; margin-bottom: 20px; border-radius: 8px; text-align: center; font-weight: bold; }
    .success { background: #d4edda; color: #155724; }
    .error { background: #f8d7da; color: #721c24; }
  </style>
</head>
<body>
  <div class="layout">
    <main class="main-content">
      <header class="top-bar">
        <div class="top-bar-title">
          <h1>Modifier la voiture</h1>
          <p>Mettez à jour les informations et cliquez sur <strong>Enregistrer</strong></p>
        </div>
      </header>
      <?php if($message): ?>
        <div class="message-banner <?= strpos($message, '✅') !== false ? 'success' : 'error' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
      <?php endif; ?>
      <form action="?id=<?= htmlspecialchars($id) ?>" method="POST" id="voitureForm" enctype="multipart/form-data">
        <!-- ─── SECTION 1 : Identité ─────────────── -->
        <section class="form-section" id="identite">
          <div class="section-head">
            <div class="section-tag">01</div>
            <div>
              <h2>Identité du véhicule</h2>
              <p>Marque, modèle, catégorie et configuration</p>
            </div>
          </div>
          <div class="fields-stack">
            <div class="field-row">
              <div class="field">
                <label for="marque">Marque <span class="req">*</span></label>
                <input type="text" id="marque" name="marque" value="<?= htmlspecialchars($car['marque'] ?? '') ?>" placeholder="ex: BMW, Toyota, Mercedes…" required autocomplete="off" />
                <ul id="brands" class="suggestions"></ul>
              </div>
              <div class="field">
                <label for="modele">Modèle <span class="req">*</span></label>
                <input type="text" id="modele" name="modele" value="<?= htmlspecialchars($car['modele'] ?? '') ?>" placeholder="ex: M3, Corolla, Classe C…" required autocomplete="off" />
                <ul id="modeles" class="suggestions"></ul>
              </div>
                <div class="field">
                <label for="version">Version <span class="req">*</span></label>
                <input type="text" id="version" name="version" value="<?= htmlspecialchars($car['Version'] ?? '') ?>" placeholder="ex: GT, X, S…" required autocomplete="off" />
                <ul id="versions" class="suggestions"></ul>
              </div>
            </div>
            <?php function isSel($val1, $val2) { return ($val1 == $val2) ? 'selected' : ''; } ?>
            <div class="field-row">
              <div class="field">
                <label for="year">Année</label>
                <input type="number" id="year" name="year" min="1900" max="2100" value="<?= htmlspecialchars($car['Annee'] ?? '') ?>" placeholder="2024" />
              </div>
              <div class="field">
                <label for="portes">Nombre de portes</label>
                <select id="portes" name="portes">
                  <option value="2" <?= isSel($car['Portes'] ?? '', '2') ?>>2 portes</option>
                  <option value="3" <?= isSel($car['Portes'] ?? '', '3') ?>>3 portes</option>
                  <option value="4" <?= isSel($car['Portes'] ?? '', '4') ?>>4 portes</option>
                  <option value="5" <?= isSel($car['Portes'] ?? '', '5') ?>>5 portes</option>
                </select>
              </div>
            </div>
            <div class="field-row">
              <div class="field">
                <label for="class">Classe / Catégorie</label>
                <select id="class" name="class">
                  <option value="">— Sélectionner —</option>
                  <?php 
                    $categories = ["Citadine Sport", "Compacte Sport", "Roadster", "Berline", "SUV Compact", "SUV", "Supercar", "Compacte", "SUV Electrique", "Citadine", "Cabriolet", "Coupe", "Sportive", "Citadine Electrique", "Grand Tourisme", "Berline Sport", "Hypercar", "Fastback", "Limousine", "SUV Coupe", "Berline Electrique", "Coupe Sport", "Fastback Sport", "Limousine Sport", "Break Sport", "SUV Sport", "SUV Coupe Sport", "Grand Tourisme Sport", "Limousine Electrique", "SUV Compact Electrique", "SUV Hybride", "Pick-up", "Monospace", "Break Compact", "Compacte Electrique", "Crossover", "Microcar Electrique", "Break", "Muscle Car", "Monospace Compact", "Pick-up Electrique", "Fourgon", "SUV Hydrogene", "Crossover Electrique", "Crossover Hybride", "Hypercar Electrique", "SUV Coupe Electrique", "Grand Tourisme Electrique", "Cabriolet Electrique", "SUV Compact Sport", "Roadster Sport", "Break Electrique", "Roadster Electrique"];
                    foreach ($categories as $cat) {
                        echo '<option value="'.$cat.'" '.isSel($car['Categorie'] ?? '', $cat).'>'.$cat.'</option>';
                    }
                  ?>
                </select>
              </div>
              <div class="field-column">
                <div class="field">
                  <label for="transmission">Transmission</label>
                  <select id="transmission" name="transmission">
                    <option value="">— Sélectionner —</option>
                    <option value="FWD" <?= isSel($car['Transmission'] ?? '', 'FWD') ?>>FWD — Traction avant</option>
                    <option value="RWD" <?= isSel($car['Transmission'] ?? '', 'RWD') ?>>RWD — Propulsion</option>
                    <option value="AWD" <?= isSel($car['Transmission'] ?? '', 'AWD') ?>>AWD — 4 roues motrices</option>
                    <option value="4WD" <?= isSel($car['Transmission'] ?? '', '4WD') ?>>4WD — 4x4</option>
                  </select>
                </div> 
                <div class="field">
                  <label for="carburant">Carburant</label>
                  <select id="carburant" name="carburant">
                    <option value="">— Sélectionner —</option>
                    <option value="Essence" <?= isSel($car['Carburant'] ?? '', 'Essence') ?>>Essence</option>
                    <option value="Diesel" <?= isSel($car['Carburant'] ?? '', 'Diesel') ?>>Diesel</option> 
                    <option value="Hybride" <?= isSel($car['Carburant'] ?? '', 'Hybride') ?>>Hybride</option>
                    <option value="Electrique" <?= isSel($car['Carburant'] ?? '', 'Electrique') ?>>Électrique</option>
                  </select>
                </div>
                <div class="field">
                  <label for="boite">Boite</label>
                  <select id="boite" name="boite">
                    <option value="">— Sélectionner —</option>
                    <option value="Manuelle" <?= isSel($car['Boite'] ?? '', 'Manuelle') ?>>Manuelle</option>
                    <option value="Automatique" <?= isSel($car['Boite'] ?? '', 'Automatique') ?>>Automatique</option>
                  </select>
                </div> 
              </div>
            </div>
          </div>
        </section>
        <div class="divider"></div>
        <!-- ─── SECTION 2 : Prix & Km ────────────── -->
        <section class="form-section" id="prix">
          <div class="section-head">
            <div class="section-tag">02</div>
            <div>
              <h2>Prix & Kilométrage</h2>
              <p>Valeur commerciale et état kilométrique du véhicule</p>
            </div>
          </div>
          <div class="fields-stack">
            <div class="field-row">
              <div class="field">
                <label for="prix">Prix</label>
                <div class="input-with-unit">
                  <input type="number" id="prix" name="prix" min="0" step="500" value="<?= htmlspecialchars($car['Prix'] ?? '') ?>" placeholder="350 000" />
                  <span class="unit-badge">MAD</span>
                </div>
              </div>
              <div class="field">
                <label for="kilometrage">Kilométrage</label>
                <div class="input-with-unit">
                  <input type="number" id="kilometrage" name="kilometrage" min="0" value="<?= htmlspecialchars($car['Kilometrage'] ?? '') ?>" placeholder="45 000" />
                  <span class="unit-badge">km</span>
                </div>
              </div>
            </div>
          </div>
        </section>
        <div class="divider"></div>
        <!-- ─── SECTION 3 : Performances ─────────── -->
        <section class="form-section" id="performances">
          <div class="section-head">
            <div class="section-tag">03</div>
            <div>
              <h2>Performances</h2>
              <p>Données techniques et mécaniques du moteur</p>
            </div>
          </div>
          <div class="fields-stack">
            <div class="field-row">
              <div class="field">
                <label for="vitesse_max">Vitesse maximale</label>
                <div class="input-with-unit">
                  <input type="number" id="vitesse_max" name="vitesse_max" min="0" value="<?= htmlspecialchars($car['VitesseMax'] ?? '') ?>" placeholder="250" />
                  <span class="unit-badge">km/h</span>
                </div>
              </div>
              <div class="field">
                <label for="acceleration">Accélération 0–100</label>
                <div class="input-with-unit">
                  <input type="number" id="acceleration" name="acceleration" min="0" step="0.1" value="<?= htmlspecialchars($car['Acceleration'] ?? '') ?>" placeholder="4.2" />
                  <span class="unit-badge">sec</span>
                </div>
              </div>
            </div>
            <div class="field-row">
              <div class="field">
                <label for="puissance">Puissance</label>
                <div class="input-with-unit">
                  <input type="number" id="puissance" name="puissance" min="0" value="<?= htmlspecialchars($car['Puissance'] ?? '') ?>" placeholder="200" />
                  <span class="unit-badge">ch</span>
                </div>
              </div>
              <div class="field">
                <label for="consommation">Consommation</label>
                <div class="input-with-unit">
                  <input type="number" id="consommation" name="consommation" min="0" step="0.1" value="<?= htmlspecialchars($car['Consommation'] ?? '') ?>" placeholder="8.5" />
                  <span class="unit-badge">L/100km</span>
                </div>
              </div>
            </div>
          </div>
        </section>
        <div class="divider"></div>
        <!-- ─── SECTION 4 : Description ───────────── -->
        <section class="form-section" id="description">
          <div class="section-head">
            <div class="section-tag">04</div>
            <div>
              <h2>Description</h2>
              <p>Présentation détaillée, options et historique du véhicule</p>
            </div>
          </div>
          <div class="fields-stack">
            <div class="field full">
              <label for="desc">Description du véhicule</label>
              <textarea id="desc" name="description" rows="6" placeholder="Décrivez le véhicule..."><?= htmlspecialchars($car['Description'] ?? '') ?></textarea>
              <div class="field-hint"><span id="charCount">0</span> / 2000 caractères</div>
            </div>
          </div>
        </section>
        <div class="divider"></div>
        <!-- ─── SECTION 5 : Photos ────────────────── -->
        <section class="form-section" id="photos">
          <div class="section-head">
            <div class="section-tag">05</div>
            <div>
              <h2>Photos du véhicule</h2>
              <p>Laissez vide pour conserver les photos actuelles — JPG, PNG, WEBP, max 10 MB</p>
            </div>
          </div>
          <div class="photos-row">
            <div class="photo-slot photo-main">
              <input type="file" id="photo_1" name="photo_1" accept="image/*" class="file-input" />
              <label for="photo_1" class="photo-drop">
                <div class="photo-inner <?= !empty($car['Photo1']) ? 'has-image' : '' ?>" id="preview_1">
                  <?php if(!empty($car['Photo1'])): ?>
                    <img src="<?= htmlspecialchars($car['Photo1']) ?>" alt="Photo 1" />
                  <?php else: ?>
                    <div class="photo-empty">
                      <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                      </svg>
                      <span>Photo principale</span>
                      <small>Cliquer pour modifier</small>
                    </div>
                  <?php endif; ?>
                </div>
              </label>
              <div class="photo-tag">Principale</div>
            </div>
            <div class="photos-secondary">
              <div class="photo-slot">
                <input type="file" id="photo_2" name="photo_2" accept="image/*" class="file-input" />
                <label for="photo_2" class="photo-drop">
                  <div class="photo-inner <?= !empty($car['Photo2']) ? 'has-image' : '' ?>" id="preview_2">
                    <?php if(!empty($car['Photo2'])): ?>
                      <img src="<?= htmlspecialchars($car['Photo2']) ?>" alt="Photo 2" />
                    <?php else: ?>
                      <div class="photo-empty small">
                        <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Photo 2</span>
                      </div>
                    <?php endif; ?>
                  </div>
                </label>
              </div>
              <div class="photo-slot">
                <input type="file" id="photo_3" name="photo_3" accept="image/*" class="file-input" />
                <label for="photo_3" class="photo-drop">
                  <div class="photo-inner <?= !empty($car['Photo3']) ? 'has-image' : '' ?>" id="preview_3">
                    <?php if(!empty($car['Photo3'])): ?>
                      <img src="<?= htmlspecialchars($car['Photo3']) ?>" alt="Photo 3" />
                    <?php else: ?>
                      <div class="photo-empty small">
                        <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Photo 3</span>
                      </div>
                    <?php endif; ?>
                  </div>
                </label>
              </div>
            </div>
          </div>
        </section>
        <!-- ─── FOOTER MOBILE ─────────────────────── -->
        <div class="form-footer-mobile">
          <a href="Shop.php" class="btn-secondary" style="text-decoration:none; display:flex; align-items:center; justify-content:center;">Annuler</a>
          <button type="submit" class="btn-primary" id="submitBtnMobile">Enregistrer</button>
        </div>
      </form>
    </main>
  </div>
  <script src="ModifyCar.js"></script>
</body>
</html>