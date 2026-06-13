<?php
require 'Database.php';

try{
$sql= 'SELECT * FROM concessionnaire WHERE id_utilisateur = :id';
$strt= $pdo->prepare($sql);
$strt->execute([':id' => $_SESSION['id']]);

$concess= $strt->fetch(PDO::FETCH_ASSOC);

}catch(PDOException $e){
    echo "Erreur type: " . $e->getMessage();
}
?>