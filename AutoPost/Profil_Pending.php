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

}else if($role == 'client' && $status !== 'pending'){
    header('Location: Profil.php');
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
if(isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
        <div class='success'>Su Solicitud fue enviada con exito</div>
<?php endif; ?>

    

  

        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="sidebar-user">
                <div class="sidebar-avatar">PK</div>
                <strong>@<?php echo $_SESSION['utilisateur'];?></strong><br>
                <span><?php echo $_SESSION['email']; ?></span>
            </div>

            <nav class="sidebar-menu">
                <a id="perfil" class="active">👤 Profil</a>
                    <a id="dealerBtn" class="loading">
                <span>💼 Devenir Dealer</span>
                <!-- Reemplaza el src con la ruta de tu imagen -->
                <img src="medias/loading.png" alt="cargando" class="spinner">
            </a>
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
    <section id="dealer-section" style="display:none;">
    <div class="dealer-content">

        <div class="dealer-check-icon">
            <svg viewBox="0 0 52 52" width="72" height="72">
                <circle cx="26" cy="26" r="23" fill="none"
                        stroke="rgba(138,180,248,0.3)" stroke-width="2.5"/>
                <circle cx="26" cy="26" r="23" fill="none"
                        stroke="#8ab4f8" stroke-width="2.5"
                        stroke-dasharray="145" stroke-dashoffset="145"
                        class="dealer-ring"/>
                <path d="M16 27 l7 7 13-13" fill="none"
                      stroke="#8ab4f8" stroke-width="2.5"
                      stroke-linecap="round" stroke-linejoin="round"
                      class="dealer-checkmark"/>
            </svg>
        </div>

        <h1 class="dealer-titre">Su Solicitud fue enviada</h1>
        <p class="dealer-subtitre">Espere a que el admin acepte tu solicitud</p>
        <p class="dealer-note">( Esto no tardará más de 24h )</p>

        <img src="medias/loading.png" alt="cargando" class="dealer-spinner">
    </div>
</section>

        </main>
    </div>

<script>
const perfilBtn      = document.getElementById("perfil");
const dealerBtn      = document.getElementById("dealerBtn");
const perfilSection  = document.getElementById("perfil-section");
const dealerSection  = document.getElementById("dealer-section");
// ── Cambiar entre secciones ──
perfilBtn.addEventListener("click", function () {
    perfilSection.style.display = "block";
    dealerSection.style.display = "none";
    perfilBtn.classList.add("active");
    dealerBtn.classList.remove("active");
});
dealerBtn.addEventListener("click", function () {
    dealerSection.style.display = "block";
    perfilSection.style.display = "none";
    dealerBtn.classList.add("active");
    perfilBtn.classList.remove("active");
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


</script>
</body>

</html>