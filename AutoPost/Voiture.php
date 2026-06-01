<?php

$id=$_SESSION['id'];

require 'Database.php';

try{
$sql= 'SELECT * from voiture WHERE id_concessionnaire=:id
ORDER BY date_de_pub desc;';
$stlt= $pdo->prepare($sql);
$stlt->execute([
    ':id'=>$id
]);
$cards= $stlt->fetchAll(PDO::FETCH_ASSOC);

}catch(PDOException $e){
    echo "Erreur type: " . $e->getMessage();
}


?>