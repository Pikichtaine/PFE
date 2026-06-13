<?php
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
}else if($status == 'pending'){
    header('Location: Profil_Pending.php');
    exit;
}else if($role == 'client' && $status !== 'pending'){
    header('Location: Profil.php');
    exit;
}
?>