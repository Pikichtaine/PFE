<?php
require 'Database.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Ajouter une Voiture</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=Libre+Baskerville:wght@700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css" />
</head>
<body>

  <div class="layout">



    <!-- ══════════════════════════════
         CONTENIDO PRINCIPAL
    ══════════════════════════════ -->
    <main class="main-content">

      <header class="top-bar">
        <div class="top-bar-title">
          <h1>Publier une voiture</h1>
          <p>Remplissez les informations et cliquez sur <strong>Publier</strong></p>
        </div>

      </header>

<?php



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

        } else {
            die("Todos los campos obligatorios deben estar completos");
        }


/* =========================
   VERIFICAR LA FOTO
   ========================= */
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
$foto1 = subirFoto($_FILES['photo_1'], $carpeta);
$foto2 = subirFoto($_FILES['photo_2'], $carpeta);
$foto3 = subirFoto($_FILES['photo_3'], $carpeta);

            if (isset($foto1, $foto2, $foto3)) {
                try {

                    $sqlFoto = "INSERT INTO specs (
        marque, modele, Version, Kilometrage, Prix,
        Annee, Puissance, Consommation, Boite, Description,
        Photo1, Photo2, Photo3, Categorie, Portes,
        Transmission, Carburant, VitesseMax, Acceleration
    )
    VALUES (
        :marque, :modele, :version, :kilometrage, :prix,
        :annee, :puissance, :consommation, :boite, :description,
        :photo1, :photo2, :photo3, :Categorie, :portes,
        :transmission, :carburant, :vitesse_max, :acceleration
    )";

                    $stmt = $pdo->prepare($sqlFoto);
                    $stmt->bindParam(':marque', $marque);
                    $stmt->bindParam(':modele', $modele);
                    $stmt->bindParam(':version', $version);
                    $stmt->bindParam(':kilometrage', $kilometrage);
                    $stmt->bindParam(':prix', $prix);
                    $stmt->bindParam(':annee', $year);
                    $stmt->bindParam(':puissance', $puissance);
                    $stmt->bindParam(':consommation', $consommation);
                    $stmt->bindParam(':boite', $boite);
                    $stmt->bindParam(':description', $descripcion);
                    $stmt->bindParam(':photo1', $foto1);
                    $stmt->bindParam(':photo2', $foto2);
                    $stmt->bindParam(':photo3', $foto3);
                    $stmt->bindParam(':Categorie', $class);
                    $stmt->bindParam(':portes', $portes);
                    $stmt->bindParam(':transmission', $transmission);
                    $stmt->bindParam(':carburant', $carburant);
                    $stmt->bindParam(':vitesse_max', $vitesse_max);
                    $stmt->bindParam(':acceleration', $acceleration);

                    $stmt->execute();

                    echo "✅ Imagen subida correctamente";
                header("Location: Shop.html");
                exit;
                } catch(PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
}



    }

?>


      <!-- Notification -->
      <div id="notification" class="notification hidden"></div>

      <form action="" method="POST" id="voitureForm" enctype="multipart/form-data">

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
                <input type="text" id="marque" name="marque" placeholder="ex: BMW, Toyota, Mercedes…" required />
                <ul id="brands" class="suggestions"></ul>
              </div>
              <div class="field">
                <label for="modele">Modèle <span class="req">*</span></label>
                <input type="text" id="modele" name="modele" placeholder="ex: M3, Corolla, Classe C…" required />
                <ul id="modeles" class="suggestions"></ul>

              </div>
                <div class="field">
                <label for="version">Version <span class="req">*</span></label>
                <input type="text" id="version" name="version" placeholder="ex: GT, X, S…" required />
                <ul id="versions" class="suggestions"></ul>

              </div>
            </div>

            <div class="field-row">
              <div class="field">
                <label for="year">Année</label>
                <input type="number" id="year" name="year" min="1900" max="2100" value="2012" placeholder="2024" />
              </div>
              <div class="field">
                <label for="portes">Nombre de portes</label>
                <select id="portes" name="portes">
                  <option value="2">2 portes</option>
                  <option value="3">3 portes</option>
                  <option value="4" selected>4 portes</option>
                  <option value="5">5 portes</option>
                </select>
              </div>
            </div>

            <div class="field-row">
              <div class="field">
                <label for="class">Classe / Catégorie</label>
                <select id="class" name="class">
                  <option value="">— Sélectionner —</option>
                  <option value="Citadine Sport">Citadine Sport</option>
                  <option value="Compacte Sport">Compacte Sport</option>
                  <option value="Roadster">Roadster</option>
                  <option value="Berline">Berline</option>
                  <option value="SUV Compact">SUV Compact</option>
                  <option value="SUV">SUV</option>
                  <option value="Supercar">Supercar</option>
                  <option value="Compacte">Compacte</option>
                  <option value="SUV Electrique">SUV Electrique</option>
                  <option value="Citadine">Citadine</option>
                  <option value="Cabriolet">Cabriolet</option>
                  <option value="Coupe">Coupe</option>
                  <option value="Sportive">Sportive</option>
                  <option value="Citadine Electrique">Citadine Electrique</option>
                  <option value="Grand Tourisme">Grand Tourisme</option>
                  <option value="Berline Sport">Berline Sport</option>
                  <option value="Hypercar">Hypercar</option>
                  <option value="Fastback">Fastback</option>
                  <option value="Limousine">Limousine</option>
                  <option value="SUV Coupe">SUV Coupe</option>
                  <option value="Berline Electrique">Berline Electrique</option>
                  <option value="Coupe Sport">Coupe Sport</option>
                  <option value="Fastback Sport">Fastback Sport</option>
                  <option value="Limousine Sport">Limousine Sport</option>
                  <option value="Break Sport">Break Sport</option>
                  <option value="SUV Sport">SUV Sport</option>
                  <option value="SUV Coupe Sport">SUV Coupe Sport</option>
                  <option value="Grand Tourisme Sport">Grand Tourisme Sport</option>
                  <option value="Limousine Electrique">Limousine Electrique</option>
                  <option value="SUV Compact Electrique">SUV Compact Electrique</option>
                  <option value="SUV Hybride">SUV Hybride</option>
                  <option value="Pick-up">Pick-up</option>
                  <option value="Monospace">Monospace</option>
                  <option value="Break Compact">Break Compact</option>
                  <option value="Compacte Electrique">Compacte Electrique</option>
                  <option value="Crossover">Crossover</option>
                  <option value="Microcar Electrique">Microcar Electrique</option>
                  <option value="Break">Break</option>
                  <option value="Muscle Car">Muscle Car</option>
                  <option value="Monospace Compact">Monospace Compact</option>
                  <option value="Pick-up Electrique">Pick-up Electrique</option>
                  <option value="Fourgon">Fourgon</option>
                  <option value="SUV Hydrogene">SUV Hydrogene</option>
                  <option value="Crossover Electrique">Crossover Electrique</option>
                  <option value="Crossover Hybride">Crossover Hybride</option>
                  <option value="Hypercar Electrique">Hypercar Electrique</option>
                  <option value="SUV Coupe Electrique">SUV Coupe Electrique</option>
                  <option value="Grand Tourisme Electrique">Grand Tourisme Electrique</option>
                  <option value="Cabriolet Electrique">Cabriolet Electrique</option>
                  <option value="SUV Compact Sport">SUV Compact Sport</option>
                  <option value="Roadster Sport">Roadster Sport</option>
                  <option value="Break Electrique">Break Electrique</option>
                  <option value="Roadster Electrique">Roadster Electrique</option>
                </select>
              </div>
              <div class="field-column">
 
              <div class="field">
                <label for="transmission">Transmission</label>
                <select id="transmission" name="transmission">
                  <option value="">— Sélectionner —</option>
                  <option value="FWD">FWD — Traction avant</option>
                  <option value="RWD">RWD — Propulsion</option>
                  <option value="AWD">AWD — 4 roues motrices</option>
                  <option value="4WD">4WD — 4x4</option>
                </select>
              </div> 
                
              <div class="field">
                <label for="carburant">Carburant</label>
                <select id="carburant" name="carburant">
                  <option value="">— Sélectionner —</option>
                  <option value="Essence">Essence</option>
                  <option value="Diesel">Diesel</option> 
                  <option value="Hybride">Hybride</option>
                  <option value="Electrique">Électrique</option>
                </select>
              </div>

              <div class="field">
                <label for="boite">Boite</label>
                <select id="boite" name="boite">
                  <option value="">— Sélectionner —</option>
                  <option value="Manuelle">Manuelle</option>
                  <option value="Automatique">Automatique</option>
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
                  <input type="number" id="prix" name="prix" min="0" step="500" placeholder="350 000" />
                  <span class="unit-badge">MAD</span>
                </div>
              </div>
              <div class="field">
                <label for="kilometrage">Kilométrage</label>
                <div class="input-with-unit">
                  <input type="number" id="kilometrage" name="kilometrage" min="0" placeholder="45 000" />
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
                  <input type="number" id="vitesse_max" name="vitesse_max" min="0" placeholder="250" />
                  <span class="unit-badge">km/h</span>
                </div>
              </div>
              <div class="field">
                <label for="acceleration">Accélération 0–100</label>
                <div class="input-with-unit">
                  <input type="number" id="acceleration" name="acceleration" min="0" step="0.1" placeholder="4.2" />
                  <span class="unit-badge">sec</span>
                </div>
              </div>
            </div>
            <div class="field-row">
              <div class="field">
                <label for="puissance">Puissance</label>
                <div class="input-with-unit">
                  <input type="number" id="puissance" name="puissance" min="0" placeholder="200" />
                  <span class="unit-badge">ch</span>
                </div>
              </div>
              <div class="field">
                <label for="consommation">Consommation</label>
                <div class="input-with-unit">
                  <input type="number" id="consommation" name="consommation" min="0" step="0.1" placeholder="8.5" />
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
              <textarea id="desc" name="description" rows="6"
                placeholder="Décrivez le véhicule : état général, options disponibles, entretien effectué, historique du véhicule…"></textarea>
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
              <p>Ajoutez jusqu'à 3 photos — JPG, PNG, WEBP, max 10 MB</p>
            </div>
          </div>

          <div class="photos-row">

            <div class="photo-slot photo-main">
              <input type="file" id="photo_1" name="photo_1" accept="image/*" class="file-input" />
              <label for="photo_1" class="photo-drop">
                <div class="photo-inner" id="preview_1">
                  <div class="photo-empty">
                    <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Photo principale</span>
                    <small>Cliquer pour choisir</small>
                  </div>
                </div>
              </label>
              <div class="photo-tag">Principale</div>
            </div>

            <div class="photos-secondary">
              <div class="photo-slot">
                <input type="file" id="photo_2" name="photo_2" accept="image/*" class="file-input" />
                <label for="photo_2" class="photo-drop">
                  <div class="photo-inner" id="preview_2">
                    <div class="photo-empty small">
                      <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
                      </svg>
                      <span>Photo 2</span>
                    </div>
                  </div>
                </label>
              </div>

              <div class="photo-slot">
                <input type="file" id="photo_3" name="photo_3" accept="image/*" class="file-input" />
                <label for="photo_3" class="photo-drop">
                  <div class="photo-inner" id="preview_3">
                    <div class="photo-empty small">
                      <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
                      </svg>
                      <span>Photo 3</span>
                    </div>
                  </div>
                </label>
              </div>
            </div>

          </div>
        </section>

        <!-- ─── FOOTER MOBILE ─────────────────────── -->
        <div class="form-footer-mobile">
          <button type="reset" class="btn-secondary">Réinitialiser</button>
          <button type="submit" class="btn-primary" id="submitBtnMobile">Publier l'annonce</button>
        </div>

      </form>
    </main>
  </div>

  <script src="addCars.js"></script>
</body>
</html>
