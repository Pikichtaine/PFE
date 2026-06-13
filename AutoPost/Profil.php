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
try{
$sql= 'SELECT 
    u.role, 
    dr.status
FROM utilisateur u
LEFT JOIN dealer_requests dr 
    ON u.id = dr.id_utilisateur
    WHERE u.id = :id;';
$stmt= $pdo->prepare($sql);
$stmt->execute([':id' => $_SESSION['id']]);
$utilisateur= $stmt->fetch(PDO::FETCH_ASSOC);

}catch(PDOException $e){
    echo "Erreur type: " . $e->getMessage();
}

    $status = $utilisateur['status'] ?? 'aucun';
    $role = $utilisateur['role'];
if($role == 'dealer'){
    header('Location: Profil_Dealer.php');
    exit;
}else if($status == 'pending'){
    header('Location: Profil_Pending.php');
    exit;
}


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

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir los datos del formulario y el usuario de la sesión
    $id_utilisateur = $_SESSION['id'];
    $username    = $_SESSION['utilisateur'];
    $dealer_name = trim($_POST['dealer_name']);
    $city        = trim($_POST['city']);
    $phone       = trim($_POST['phone']);

    // Comprobar que los campos no estén vacíos
    if (!empty($dealer_name) && !empty($city) && !empty($phone)) {
        
        try {
            // Preparar la consulta SQL
            // NOTA: Si tu variable de conexión en Database.php se llama diferente a $pdo (por ejemplo $conn), cámbiala aquí abajo
            $sql = "INSERT INTO dealer_requests (id_utilisateur, username, dealer_name, city, phone) VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql); 
            
            // Ejecutar la inserción
            $stmt->execute([$id_utilisateur, $username, $dealer_name, $city, $phone]);
            header('Location: Profil_Pending.php?msg=success');

            // Redirigir de vuelta al perfil con un mensaje de éxito
        } catch (Exception $e) {
            die("Error en la base de datos: " . $e->getMessage());
        }

    } else {
        // Redirigir con error si falta algún dato
        echo "<div class='error'>No se envio la solicitud correctamente</div>";
    }
}

?>
  

        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="sidebar-user">
                <div class="sidebar-avatar">PK</div>
                <strong>@<?php echo $_SESSION['utilisateur'];?></strong><br>
                <span><?php echo $_SESSION['email']; ?></span>
            </div>

            <nav class="sidebar-menu">
                <a id="perfil" class="active">👤 Profil</a>
                <a id="dealerBtn">💼 Devenir Dealer</a>
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

        </main>
    </div>
<div class="dealer-overlay hidden" id="dealerOverlay">
  <div class="dealer-panel">

    <!-- Indicateur d'étape -->
    <div class="dealer-dots">
      <div class="dot active" id="dot1"></div>
      <div class="dot" id="dot2"></div>
    </div>

    <!-- ÉTAPE 1 : Présentation -->
    <div class="dealer-step" id="dealerStep1">
      <button class="dealer-close" id="closeDealer">✕</button>
      <div class="dealer-illus">🏎️</div>
      <h2 class="dealer-title">Devenez Dealer</h2>
      <p class="dealer-desc">
        Rejoignez le réseau <strong>Legendary Motorsport</strong> et vendez vos véhicules à des milliers de passionnés.
      </p>
      <div class="dealer-perks">
        <div class="perk">✅ &nbsp;Annonces illimitées</div>
        <div class="perk">✅ &nbsp;Page concessionnaire officielle</div>
        <div class="perk">✅ &nbsp;Statistiques & visibilité boostée</div>
      </div>
      <button class="dealer-btn-primary" id="dealerStart">Commencer →</button>
      <button class="dealer-btn-ghost" id="dealerNo">Non, merci</button>
    </div>

    <!-- ÉTAPE 2 : Formulaire -->
    <div class="dealer-step hidden" id="dealerStep2">
      <button class="dealer-close" id="closeDealer2">✕</button>
      <div class="dealer-illus">📋</div>
      <h2 class="dealer-title">Vos informations</h2>
      <p class="dealer-desc">Notre équipe vous contactera sous 24h pour finaliser votre compte dealer.</p>
      <form method="post" action="Profil.php" class="dealer-form">
        <input type="text" name="dealer_name" placeholder="Nom du concessionnaire" required>
        <input type="text" name="city"         placeholder="Ville" required>
        <input type="tel"  name="phone"        placeholder="Numéro de téléphone" required>
        <button type="submit" class="dealer-btn-primary">Envoyer la demande ✓</button>
      </form>
      <button class="dealer-btn-ghost" id="dealerBack">← Retour</button>
    </div>

  </div>
</div>
<script>
const perfilBtn = document.getElementById("perfil");

const perfilSection = document.getElementById("perfil-section");

perfilBtn.addEventListener("click", function() {
    perfilBtn.classList.add("active");
    articulosBtn.classList.remove("active");

    perfilSection.style.display = "block";
    articulosSection.style.display = "none";
});


const editBtn = document.getElementById("modificar");
const username = document.getElementById("usuario");
const usernameInput = document.getElementById("usuarioInput");
const saveBtn = document.getElementById("guardar");



editBtn.addEventListener("click", (e) => {
    e.preventDefault();
    username.classList.toggle("hidden");
    usernameInput.classList.toggle("hidden");
    editBtn.classList.toggle("hidden");
    saveBtn.classList.toggle("hidden");

});



// === DEALER PANEL (del paso anterior) ===
const dealerBtn     = document.getElementById('dealerBtn');
const dealerOverlay = document.getElementById('dealerOverlay');
const dealerStep1   = document.getElementById('dealerStep1');
const dealerStep2   = document.getElementById('dealerStep2');
const dot1          = document.getElementById('dot1');
const dot2          = document.getElementById('dot2');

function setDots(step) {
    dot1?.classList.toggle('active', step === 1);
    dot2?.classList.toggle('active', step === 2);
}
function closeDealerPanel() {
    dealerOverlay?.classList.add('hidden');
    setTimeout(() => {
        dealerStep2?.classList.add('hidden');
        dealerStep1?.classList.remove('hidden');
        setDots(1);
    }, 200);
}
function goToStep(from, to) {
    from.classList.add('hidden');
    to.classList.remove('hidden');
    to.style.animation = 'dealerStepIn 0.3s ease';
    setTimeout(() => to.style.animation = '', 300);
}

dealerBtn?.addEventListener('click', (e) => {
    e.preventDefault();
    dealerOverlay?.classList.remove('hidden');
});
document.getElementById('closeDealer')?.addEventListener('click', closeDealerPanel);
document.getElementById('closeDealer2')?.addEventListener('click', closeDealerPanel);
document.getElementById('dealerNo')?.addEventListener('click', closeDealerPanel);
dealerOverlay?.addEventListener('click', (e) => {
    if (e.target === dealerOverlay) closeDealerPanel();
});
document.getElementById('dealerStart')?.addEventListener('click', () => {
    goToStep(dealerStep1, dealerStep2);
    setDots(2);
});
document.getElementById('dealerBack')?.addEventListener('click', () => {
    goToStep(dealerStep2, dealerStep1);
    setDots(1);
});

</script>
</body>

</html>