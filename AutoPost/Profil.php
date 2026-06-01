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

  

        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="sidebar-user">
                <div class="sidebar-avatar">PK</div>
                <strong>@<?php echo $_SESSION['utilisateur'];?></strong><br>
                <span><?php echo $_SESSION['email']; ?></span>
            </div>

            <nav class="sidebar-menu">
                <a id="perfil" class="active">👤 Profil</a>
                <a id="articulos">🖼️ Mes Voitures</a>
                <a>🔒 Concessionaire</a>
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

<form method="post" action="queryDeleteArticle.php">

        <section id="articulos-section" style="display: none;">

        <h1>Artículos</h1>

    <div class="articulos-grid">

<?php 
require 'Voiture.php';

foreach ($cards as $card) : ?>

    <div class="articulo-item">

<div class="fotito">

    <img src="<?php echo $card['photo_path'] ?>" alt="Articulo">
    
    <div class="articulo-actions">

        <button type="submit" class="btn editar" name="editar" value="<?php echo $card['id'] ?>">✏️</a>
        <button type="submit" class="btn borrar" name="borrar" value="<?php echo $card['id'] ?>" onclick="return confirm('Estas seguro de que quieres borrar este articulo?')">🗑️</button>

    </div>

</div>
    
    </div>

            <?php endforeach; ?>

    </div>
</section>
</form>
        </main>
    </div>

<script>
const perfilBtn = document.getElementById("perfil");
const articulosBtn = document.getElementById("articulos");

const perfilSection = document.getElementById("perfil-section");
const articulosSection = document.getElementById("articulos-section");

perfilBtn.addEventListener("click", function() {
    perfilBtn.classList.add("active");
    articulosBtn.classList.remove("active");

    perfilSection.style.display = "block";
    articulosSection.style.display = "none";
});

articulosBtn.addEventListener("click", function() {
    articulosBtn.classList.add("active");
    perfilBtn.classList.remove("active");

    articulosSection.style.display = "block";
    perfilSection.style.display = "none";
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