<?php
require 'Database.php';

try{
$sql= 'SELECT * FROM utilisateur';
$strt= $pdo->query($sql);
$utilisateur= $strt->fetchAll(PDO::FETCH_ASSOC);

}catch(PDOException $e){
    echo "Erreur type: " . $e->getMessage();
}
?>