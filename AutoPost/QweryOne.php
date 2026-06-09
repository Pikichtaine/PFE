<?php
require 'Database.php';

try{
$sql= 'SELECT * FROM utilisateur WHERE id = :id';
$strt= $pdo->prepare($sql);
$strt->execute([':id' => $_SESSION['id']]);

$utilisateur= $strt->fetchAll(PDO::FETCH_ASSOC);

}catch(PDOException $e){
    echo "Erreur type: " . $e->getMessage();
}
?>