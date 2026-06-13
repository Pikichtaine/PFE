<?php
session_start();
require 'Database.php';

if($_SERVER['REQUEST_METHOD']=="POST"){
if(isset($_POST['borrar']) && !isset($_POST['edit'])) {

    $valorDelBoton=$_POST['borrar'];
try{
$sql='delete from specs where id=:id';

$stdt=$pdo->prepare($sql);
$stdt->execute([
    ':id'=> $valorDelBoton
]);

header('Location: Profil_Dealer.php');

}catch(PDOException $e){
    echo "Erreur type: " . $e->getMessage();
}

}
}
?>