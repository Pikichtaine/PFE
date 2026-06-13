<?php

/* =========================
   VERIFICAR SESSION
   ========================= */

session_start();
require 'Database.php';

if(!isset($_SESSION['utilisateur'])){
    header('Location: Login.php');
    exit;
}
require 'Redirecting.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="stylesheet" href="CSS/profil.css">
</head>

    <body>
        <?php /*if (isset($_GET['updated'])): ?>
<div class="toast-success" id="toastSuccess">✅ Annonce mise à jour avec succès</div>
<script>
    setTimeout(() => {
        const t = document.getElementById('toastSuccess');
        t.style.opacity = '0';
        setTimeout(() => t.remove(), 400);
    }, 3000);
</script>
<?php endif; */ ?>



<!-- ========================================
                    HEADER
======================================== -->

    <header class="top-bar">
        <img class="logo" src="medias/Solicode.blog.png" alt="Logo">

    <div class="icons">

    <a href="accueil.php">
    <img src="medias/home off.png" alt="Accueil">
    </a>
    
    <a href="ajouter.php">
    <img src="medias/add.png" alt="Ajouter">
    </a>

    <a href="profil.php">
    <img src="medias/user on.png" alt="Profil">
    </a>

    </div>

    <div></div>


    </header>


    <div class="app-layout">

  

        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="sidebar-user">
                <div class="sidebar-avatar">PK</div>
                <strong>@<?php echo $_SESSION['utilisateur'];?></strong><br>
                <span><?php echo $_SESSION['email']; ?></span>
            </div>

            <nav class="sidebar-menu">
                <a id="perfil" class="active">👤 Profil</a>
                <a id="articulos">🚗 Mes Voitures</a>
                <a id="concessionaireBtn">🏣 Concessionaire</a>
                
                <a>💳 Pagos</a>
                <a>⚙️ Ajustes</a>
                <a class='deconexion' href='Logout.php'>Se déconnecter</a>
            </nav>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main-content">
    <section id="perfil-section">

            <h1>Perfil</h1>
            <div class="profile-card">

                <div class="info-row">
                    <span>ID en línea</span>
                    <strong id="usuario">@<?php echo $_SESSION['utilisateur'];?></strong>

<form method="post" action="queryUser.php">
                    
                    <input type="text" class="hidden" id="usuarioInput" name="usuarioInput" value="<?php echo $_SESSION['utilisateur']; ?>" required>

                      

                    <button type="button" id="modificar">Modificar</button>
                    <button type="submit" class="hidden" id="guardar" >Guardar</button>
</form>
                </div>


                <div class="info-row">
                    <span>Nombre</span>
                    <strong>_</strong>
                    <button>Modificar</button>
                </div>

                <div class="info-row">
                    <span>Idioma</span>
                    <strong>_</strong>
                    <button>Modificar</button>
                </div>

                <div class="info-row">
                    <span>Sobre mí</span>
                    <strong>_</strong>
                    <button>Modificar</button>
                </div>
            </div>
    </section>


<!-- ========================================
                ARTICULOS
======================================== -->
<?php require 'MyCars.php'; ?>


<form method="post" action="queryDeleteArticle.php">
    <?php if($_SESSION['role'] == 'dealer'):?> 
        <section id="articulos-section" style="display: none;">

    <div class="voitures-header">
        <h1>Mes Voitures</h1>
        <span class="voitures-count"><?php echo count($cards); ?> annonce(s)</span>
        <a href="AddCar.php" class="btn-ajouter-voiture">+ Ajouter</a>  <!-- ← AÑADIR -->
    </div>

    <?php if (empty($cards)): ?>
    <div class="voitures-empty">
        <div class="empty-icon">🚗</div>
        <p>Vous n'avez pas encore publié de véhicule.</p>
        <a href="ajouter.php" class="btn-add-first">+ Ajouter mon premier véhicule</a>
    </div>

    <?php else: ?>
    <div class="voitures-list">
        <?php foreach ($cards as $card) : ?>
        <div class="voiture-row">

            <!-- 1. PHOTO -->
            <div class="voiture-thumb">
                <img src="<?php echo htmlspecialchars($card['Photo1'] ?? $card['photo_path'] ?? '') ?>"
                     alt="<?php echo htmlspecialchars($card['marque'] ?? '') ?>">
                <span class="voiture-badge en-vente">En vente</span>
            </div>

            <!-- 2. INFOS -->
            <div class="voiture-info">
                <h3 class="voiture-title">
                    <?php echo htmlspecialchars(($card['marque'] ?? '') . ' ' . ($card['modele'] ?? '') . ' ' . ($card['Version'] ?? '')) ?>
                </h3>
                <p class="voiture-desc">
                    <?php echo htmlspecialchars(mb_substr($card['Description'] ?? '', 0, 130)) ?>...
                </p>
                <div class="voiture-tags">
                    <?php if (!empty($card['Annee']))     echo "<span>{$card['Annee']}</span>"; ?>
                    <?php if (!empty($card['Categorie'])) echo "<span>{$card['Categorie']}</span>"; ?>
                    <?php if (!empty($card['Carburant'])) echo "<span>{$card['Carburant']}</span>"; ?>
                    <?php if (!empty($card['Boite']))     echo "<span>{$card['Boite']}</span>"; ?>
                </div>
            </div>

            <!-- 3. STATS -->
            <div class="voiture-stats">
                <div class="stat-prix">
                    <?php echo number_format((float)($card['Prix'] ?? 0), 0, ',', ' ') ?> MAD
                </div>
                <div class="stat-row">🛣️ <?php echo number_format((float)($card['Kilometrage'] ?? 0), 0, ',', ' ') ?> km</div>
                <div class="stat-row">⚡ <?php echo $card['Puissance'] ?? '—' ?> ch</div>
                <div class="stat-row">🏎️ 0–100 en <?php echo $card['Acceleration'] ?? '—' ?>s</div>
            </div>

            <!-- 4. ACTIONS -->
            <div class="voiture-actions">
                <a href="modifier.php?id=<?php echo $card['id'] ?>" class="btn-modifier">
                    ✏️ Modifier
                </a>
                <form method="post" action="queryDeleteArticle.php" style="margin:0">
                    <button type="submit" name="borrar" value="<?php echo $card['id'] ?>"
                            class="btn-supprimer"
                            onclick="return confirm('Supprimer cette annonce définitivement ?')">
                        🗑️ Supprimer
                    </button>
                </form>
            </div>

        </div>
        <?php endforeach; ?>
        <?php endif; ?>

    </div>

</section>
<?php endif; ?>
</form>

<!-- ==============================
       CONCESSIONNAIRE SECTION
============================== -->
<section id="concessionaire-section" style="display:none;">

    <div class="cs-topbar">
        <h1>Concessionnaire</h1>
        <div class="cs-actions">
            <button type="button" class="cs-btn-annuler" id="csAnnuler">Annuler</button>
            <button type="submit" form="formConcessionaire" class="cs-btn-publier">Publier</button>
        </div>
    </div>

    <form id="formConcessionaire" method="post" action="queryConcessionaire.php"
          enctype="multipart/form-data">

        <!-- BANNIÈRE -->
        <div class="cs-row">
            <div class="cs-row-left">
                <h3>Image de bannière</h3>
                <p>Apparaîtra en haut de la page de votre concessionnaire. Taille recommandée : 2048 × 512 px.</p>
            </div>
            <div class="cs-row-right">
                <div class="cs-banner-preview" id="bannerPreview">
                    <?php if (!empty($concess['banner'])): ?>
                        <img src="<?php echo htmlspecialchars($concess['banner']) ?>" alt="Bannière">
                    <?php else: ?>
                        <div class="cs-banner-placeholder">🖼️<br>Aucune bannière</div>
                    <?php endif; ?>
                </div>
                <input type="hidden" name="banner_current"
                       value="<?php echo htmlspecialchars($concess['banner'] ?? '') ?>">
                <label class="cs-btn-secondary" for="bannerInput">Modifier</label>
                <input type="file" id="bannerInput" name="banner"
                       accept="image/*" style="display:none">
            </div>
        </div>

        <div class="cs-divider"></div>

        <!-- NOM -->
        <div class="cs-row">
            <div class="cs-row-left">
                <h3>Nom du concessionnaire</h3>
                <p>Le nom affiché sur votre page et à côté de vos annonces.</p>
            </div>
            <div class="cs-row-right">
                <input type="text" name="nom" class="cs-input"
                       value="<?php echo htmlspecialchars($concess['nom'] ?? '') ?>"
                       placeholder="Ex: Prestige Motors Casablanca">
            </div>
        </div>

        <div class="cs-divider"></div>

        <!-- DESCRIPTION -->
        <div class="cs-row">
            <div class="cs-row-left">
                <h3>Description</h3>
                <p>Présentez votre concessionnaire, vos spécialités et vos marques.</p>
            </div>
            <div class="cs-row-right">
                <textarea name="description" class="cs-textarea" rows="5"
                          placeholder="Ex: Spécialiste des véhicules de luxe depuis 2010..."
                ><?php echo htmlspecialchars($concess['description'] ?? '') ?></textarea>
            </div>
        </div>

        <div class="cs-divider"></div>

        <!-- LIENS -->
        <div class="cs-row">
            <div class="cs-row-left">
                <h3>Liens</h3>
                <p>Site web, réseaux sociaux... Jusqu'à 3 liens.</p>
            </div>
            <div class="cs-row-right">
                <div class="cs-links-list">

                    <div class="cs-link-item" id="linkItem1">
                        <input type="url" name="lien1" class="cs-input"
                               value="<?php echo htmlspecialchars($concess['lien1'] ?? '') ?>"
                               placeholder="https://votresite.ma">
                    </div>

                    <div class="cs-link-item <?php echo empty($concess['lien2']) ? 'hidden' : '' ?>"
                         id="linkItem2">
                        <input type="url" name="lien2" class="cs-input"
                               value="<?php echo htmlspecialchars($concess['lien2'] ?? '') ?>"
                               placeholder="https://instagram.com/votre-page">
                        <button type="button" class="cs-btn-remove-link"
                                onclick="removeLink(2)">✕</button>
                    </div>

                    <div class="cs-link-item <?php echo empty($concess['lien3']) ? 'hidden' : '' ?>"
                         id="linkItem3">
                        <input type="url" name="lien3" class="cs-input"
                               value="<?php echo htmlspecialchars($concess['lien3'] ?? '') ?>"
                               placeholder="https://facebook.com/votre-page">
                        <button type="button" class="cs-btn-remove-link"
                                onclick="removeLink(3)">✕</button>
                    </div>

                </div>
                <button type="button" class="cs-btn-add-link" id="addLinkBtn"
                        onclick="addLink()">+ Ajouter un lien</button>
            </div>
        </div>

        <div class="cs-divider"></div>

        <!-- COORDONNÉES -->
        <div class="cs-row">
            <div class="cs-row-left">
                <h3>Coordonnées</h3>
                <p>Ces informations seront visibles sur votre page publique.</p>
            </div>
            <div class="cs-row-right" style="display:flex; flex-direction:column; gap:12px;">
                <input type="tel" name="telephone" class="cs-input"
                       value="<?php echo htmlspecialchars($concess['telephone'] ?? '') ?>"
                       placeholder="📞 Ex: +212 6 00 00 00 00">
                <input type="text" name="localisation" class="cs-input"
                       value="<?php echo htmlspecialchars($concess['localisation'] ?? '') ?>"
                       placeholder="📍 Ex: Casablanca, Quartier Maarif">
            </div>
        </div>

    </form>
</section>
        </main>
    </div>
<script>
// === UTILS NAVIGATION ===
const allSections = {
    'perfil-section':          'perfil',
    'articulos-section':       'articulos',
    'concessionaire-section':  'concessionaireBtn'
};

function switchSection(sectionId) {
    Object.keys(allSections).forEach(id => {
        const el = document.getElementById(id);
        if (el) el.style.display = 'none';
    });
    Object.values(allSections).forEach(btnId => {
        const el = document.getElementById(btnId);
        if (el) el.classList.remove('active');
    });
    document.getElementById(sectionId).style.display = 'block';
    const btnId = allSections[sectionId];
    if (document.getElementById(btnId)) {
        document.getElementById(btnId).classList.add('active');
    }
}

// === SIDEBAR BUTTONS ===
document.getElementById('perfil')
    ?.addEventListener('click', () => switchSection('perfil-section'));

document.getElementById('articulos')
    ?.addEventListener('click', () => switchSection('articulos-section'));

document.getElementById('concessionaireBtn')
    ?.addEventListener('click', () => switchSection('concessionaire-section'));

document.getElementById('csAnnuler')
    ?.addEventListener('click', () => switchSection('perfil-section'));

// === EDIT USERNAME ===
const editBtn       = document.getElementById('modificar');
const username      = document.getElementById('usuario');
const usernameInput = document.getElementById('usuarioInput');
const saveBtn       = document.getElementById('guardar');

editBtn?.addEventListener('click', (e) => {
    e.preventDefault();
    username.classList.toggle('hidden');
    usernameInput.classList.toggle('hidden');
    editBtn.classList.toggle('hidden');
    saveBtn.classList.toggle('hidden');
});

// === BANNER PREVIEW ===
document.getElementById('bannerInput')?.addEventListener('change', function () {
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('bannerPreview').innerHTML =
                `<img src="${e.target.result}" alt="Bannière">`;
        };
        reader.readAsDataURL(this.files[0]);
    }
});

// === LINKS ===
let linkCount = <?php
    if (!empty($concess['lien3'])) echo 3;
    elseif (!empty($concess['lien2'])) echo 2;
    else echo 1;
?>;

function addLink() {
    if (linkCount >= 3) return;
    linkCount++;
    document.getElementById(`linkItem${linkCount}`).classList.remove('hidden');
    if (linkCount >= 3) document.getElementById('addLinkBtn').style.display = 'none';
}

function removeLink(n) {
    const item = document.getElementById(`linkItem${n}`);
    item.classList.add('hidden');
    item.querySelector('input').value = '';
    linkCount--;
    document.getElementById('addLinkBtn').style.display = '';
}

</script>
</body>

</html>