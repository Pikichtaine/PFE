<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Connexion</title>
<link rel="stylesheet" href="CSS/login.css">
</head>
<body class="center">
<form class="login-box" method="POST" action="">

    <h2>Connexion</h2>

<?php



/* =========================
   VERIFICAR SI TODOS LOS CAMPOS ESTAN LLENOS
   ========================= */

if($_SERVER['REQUEST_METHOD']=="POST") {
    $loginOK=false;
    if(isset($_POST['username'] , $_POST['password'])) {



/* =========================
   REPASAR TODA LA LISTA DE LA TABLA USUARIO
   ========================= */

require 'Search_Utilisateur.php';

$loginOK = false;

foreach ($utilisateur as $user) {
if (trim($_POST['username']) == $user['nom'] && trim($_POST['password']) == $user['mot_de_passe']) {
    $_SESSION['utilisateur'] = $user['nom'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['id'] = $user['id'];


        $loginOK = true;
        break;
    }
}



/* =========================
   SI ENCONTRO USUARIO...
   ========================= */

if ($loginOK) {
    header('Location: Profil.php');
    
    exit;
} else {
    echo "<div class='error'>Error, inténtalo de nuevo</div>";
}
}
}
?>

    <label>Nom: </label>
    <input type="text" name="username" required>


    <label>Mot de passe: </label>
    <input type="password" name="password" required>


    <button type="submit">Se connecter</button>

    <p class="signup-link">
    Vous n'avez pas de compte ?
    <a href="signup.php">S'inscrire</a>
    </p>

</form>

</body>
</html>