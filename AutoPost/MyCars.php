<?php

try{
$sql= 'SELECT * from specs
ORDER BY id desc;';
$stlt= $pdo->query($sql);
$cards= $stlt->fetchAll(PDO::FETCH_ASSOC);

}catch(PDOException $e){
    echo "Erreur type: " . $e->getMessage();
}

?>