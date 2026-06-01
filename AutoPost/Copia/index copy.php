<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Ajouter une Voiture</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css" />
</head>
<body>

  <div class="bg-grid"></div>

  <header class="site-header">
    <div class="header-inner">
      <div class="logo">
        <span class="logo-icon">⬡</span>
        <span class="logo-text">AUTO<strong>ADMIN</strong></span>
      </div>
      <div class="header-badge">Nouvelle annonce</div>
    </div>
  </header>

  <main class="container">

    <div class="page-title">
      <h1>Publier une Voiture</h1>
      <p>Remplissez les informations du véhicule et cliquez sur <em>Publier</em></p>
    </div>

    <!-- Notification area -->
    <div id="notification" class="notification hidden"></div>

    <form id="voitureForm" enctype="multipart/form-data" novalidate>

      <!-- ═══════════════════════════════════════
           SECTION 1 : Identité du véhicule
      ═══════════════════════════════════════ -->
      <section class="card">
        <div class="card-header">
          <span class="card-num">01</span>
          <h2>Identité du véhicule</h2>
        </div>
        <div class="grid-2">
          <div class="field">
            <label for="marque">Marque <span class="req">*</span></label>
            <input type="text" id="marque" name="marque" placeholder="ex: BMW, Toyota…" required />
          </div>
          <div class="field">
            <label for="modele">Modèle <span class="req">*</span></label>
            <input type="text" id="modele" name="modele" placeholder="ex: M3, Corolla…" required />
          </div>
          <div class="field">
            <label for="year">Année</label>
            <input type="number" id="year" name="year" min="1900" max="2100" placeholder="2024" />
          </div>
          <div class="field">
            <label for="doors">Portes</label>
            <select id="doors" name="doors">
              <option value="2">2 portes</option>
              <option value="3">3 portes</option>
              <option value="4" selected>4 portes</option>
              <option value="5">5 portes</option>
            </select>
          </div>
          <div class="field">
            <label for="class">Classe</label>
            <select id="class" name="class">
              <option value="">— Sélectionner —</option>
              <option value="Berline">Berline</option>
              <option value="SUV">SUV</option>
              <option value="Coupé">Coupé</option>
              <option value="Cabriolet">Cabriolet</option>
              <option value="Break">Break</option>
              <option value="Citadine">Citadine</option>
              <option value="Sportive">Sportive</option>
              <option value="Pickup">Pickup</option>
              <option value="Utilitaire">Utilitaire</option>
            </select>
          </div>
          <div class="field">
            <label for="drivetrain">Transmission</label>
            <select id="drivetrain" name="drivetrain">
              <option value="">— Sélectionner —</option>
              <option value="FWD">FWD — Traction avant</option>
              <option value="RWD">RWD — Propulsion</option>
              <option value="AWD">AWD — 4 roues motrices</option>
              <option value="4WD">4WD — 4x4</option>
            </select>
          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════
           SECTION 2 : Prix & Kilométrage
      ═══════════════════════════════════════ -->
      <section class="card">
        <div class="card-header">
          <span class="card-num">02</span>
          <h2>Prix & Kilométrage</h2>
        </div>
        <div class="grid-2">
          <div class="field field-icon-right">
            <label for="prix">Prix (MAD)</label>
            <input type="number" id="prix" name="prix" min="0" step="500" placeholder="350000" />
            <span class="field-suffix">MAD</span>
          </div>
          <div class="field field-icon-right">
            <label for="kilometrage">Kilométrage</label>
            <input type="number" id="kilometrage" name="kilometrage" min="0" placeholder="45000" />
            <span class="field-suffix">km</span>
          </div>
          <div class="field">
            <label for="id_concessionnaire">ID Concessionnaire</label>
            <input type="number" id="id_concessionnaire" name="id_concessionnaire" min="1" placeholder="1" />
          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════
           SECTION 3 : Performances
      ═══════════════════════════════════════ -->
      <section class="card">
        <div class="card-header">
          <span class="card-num">03</span>
          <h2>Performances</h2>
        </div>
        <div class="grid-4">
          <div class="field field-icon-right">
            <label for="top_speed">Vitesse max</label>
            <input type="number" id="top_speed" name="top_speed" min="0" placeholder="250" />
            <span class="field-suffix">km/h</span>
          </div>
          <div class="field field-icon-right">
            <label for="acceleration">0–100 km/h</label>
            <input type="number" id="acceleration" name="acceleration" min="0" step="0.1" placeholder="4.2" />
            <span class="field-suffix">s</span>
          </div>
          <div class="field field-icon-right">
            <label for="rpm_min">RPM min</label>
            <input type="number" id="rpm_min" name="rpm_min" min="0" placeholder="800" />
            <span class="field-suffix">rpm</span>
          </div>
          <div class="field field-icon-right">
            <label for="rpm_max">RPM max</label>
            <input type="number" id="rpm_max" name="rpm_max" min="0" placeholder="7200" />
            <span class="field-suffix">rpm</span>
          </div>
        </div>

        <!-- Barra visual de performance -->
        <div class="perf-bar-wrapper">
          <div class="perf-label">Indicateur vitesse max</div>
          <div class="perf-track">
            <div class="perf-fill" id="speedBar"></div>
            <span class="perf-val" id="speedVal">—</span>
          </div>
          <div class="perf-marks"><span>0</span><span>100</span><span>200</span><span>300</span><span>400+</span></div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════
           SECTION 4 : Description
      ═══════════════════════════════════════ -->
      <section class="card">
        <div class="card-header">
          <span class="card-num">04</span>
          <h2>Description</h2>
        </div>
        <div class="field">
          <label for="description">Description du véhicule</label>
          <textarea id="description" name="description" rows="5"
            placeholder="Décrivez le véhicule : état, options, entretien, historique…"></textarea>
          <div class="char-count"><span id="charCount">0</span> / 2000 caractères</div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════
           SECTION 5 : Photos
      ═══════════════════════════════════════ -->
      <section class="card">
        <div class="card-header">
          <span class="card-num">05</span>
          <h2>Photos du véhicule</h2>
        </div>
        <div class="photos-grid">

          <div class="photo-slot main-photo">
            <input type="file" id="photo_1" name="photo_1" accept="image/*" class="file-input" />
            <label for="photo_1" class="photo-label">
              <div class="photo-preview" id="preview_1">
                <div class="photo-placeholder">
                  <span class="photo-icon">📷</span>
                  <span class="photo-text">Photo principale</span>
                  <span class="photo-sub">Cliquer pour choisir</span>
                </div>
              </div>
            </label>
            <div class="photo-badge">Principal</div>
          </div>

          <div class="photo-slot">
            <input type="file" id="photo_2" name="photo_2" accept="image/*" class="file-input" />
            <label for="photo_2" class="photo-label">
              <div class="photo-preview" id="preview_2">
                <div class="photo-placeholder">
                  <span class="photo-icon">＋</span>
                  <span class="photo-text">Photo 2</span>
                </div>
              </div>
            </label>
          </div>

          <div class="photo-slot">
            <input type="file" id="photo_3" name="photo_3" accept="image/*" class="file-input" />
            <label for="photo_3" class="photo-label">
              <div class="photo-preview" id="preview_3">
                <div class="photo-placeholder">
                  <span class="photo-icon">＋</span>
                  <span class="photo-text">Photo 3</span>
                </div>
              </div>
            </label>
          </div>

        </div>
        <p class="photo-hint">Formats acceptés : JPG, PNG, WEBP — Max 10 MB par image</p>
      </section>

      <!-- ═══════════════════════════════════════
           BOUTON PUBLIER
      ═══════════════════════════════════════ -->
      <div class="form-footer">
        <button type="reset" class="btn-reset">Réinitialiser</button>
        <button type="submit" class="btn-publish" id="submitBtn">
          <span class="btn-text">Publier l'annonce</span>
          <span class="btn-arrow">→</span>
        </button>
      </div>

    </form>
  </main>

  <script src="script.js"></script>
</body>
</html>