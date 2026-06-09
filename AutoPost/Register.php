<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Inscription</title>
<link rel="stylesheet" href="CSS/login.css">
</head>
<body class="center">
<form class="login-box" method="POST" action="">

    <h2>Inscription</h2>

<?php


if($_SERVER['REQUEST_METHOD']=="POST") {
    if(isset($_POST['username'] , $_POST['password'] , $_POST['email'])) {
        $nom=trim($_POST['username']);
        $email=trim($_POST['email']);

        require 'QweryAll.php';

        foreach($utilisateur as $user){
        if($email == $user['email']) {
        echo "<div class='error'>Esta cuenta ya existe</div>";
        $signUp = false;
            break;
        }
$signUp = true;
}   
if($signUp){
            try{
    $stmt= $pdo->prepare('INSERT INTO utilisateur (nom, email, mot_de_passe) VALUES (:nom, :email, :mot_de_passe);');
    $stmt->execute([
        ':nom'=>$nom,
        ':email'=>$email,
        ':mot_de_passe'=>trim($_POST['password'])
        
    ]);

    $_SESSION['utilisateur'] = $nom;
    $_SESSION['email'] = $email;

try{
$sql= 'SELECT * FROM utilisateur WHERE email = :email';
$strt= $pdo->prepare($sql);
$strt->execute([':email' => $email]);

$utilisateur= $strt->fetch(PDO::FETCH_ASSOC);

}catch(PDOException $e){
    echo "Erreur type: " . $e->getMessage();
}
    $_SESSION['id'] = $utilisateur['id'];
    $_SESSION['role'] = $utilisateur['role'];
 

     header('Location: Profil.php');

    }catch(PDOException $e){
    echo "<div class='error'>Erreur d'ajout</div>";

}
        }
    }
}
?>

    <label>Nom: </label>
    <input type="text" name="username" required>

    <label>Email: </label>
    <input type="email" name="email" required>

    <label>Mot de passe: </label>
    <input type="password" name="password" required>


    <button type="submit">S'inscrire</button>

    <p class="signup-link">
    Vous avez déjà un compte ?
    <a href="Login.php">Se connecter</a>
    </p>


</form>

</body>
</html>