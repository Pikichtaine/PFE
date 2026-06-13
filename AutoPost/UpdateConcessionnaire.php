<?php
session_start();
require 'Database.php';
// 1. Verificar que se llega por el método correcto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 2. Recoger y limpiar los datos de texto
    $nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $lien1 = isset($_POST['lien1']) ? trim($_POST['lien1']) : '';
    $banner_final = $_POST['banner_current'] ?? ''; // Por defecto dejamos la actual
    $address = isset($_POST['localisation']) ? trim($_POST['localisation']) : '';
    $id_concessionnaire = $_SESSION['dealer_id'] ?? null; // Aseguramos que tenemos el ID del concesionario

    // 3. Procesar la subida de la imagen si se seleccionó una nueva
if (isset($_FILES['banner']) && $_FILES['banner']['error'] === UPLOAD_ERR_OK) {

    $uploadFileDir = 'uploads/';
    
    // 1. Si la carpeta no existe, la creamos primero
    if (!is_dir($uploadFileDir)) {
        mkdir($uploadFileDir, 0755, true);
    } // <-- Aquí cerramos correctamente el if de la carpeta

    // 2. Definimos las variables de la imagen SIEMPRE (estén o no creadas las carpetas)
    $fileTmpPath = $_FILES['banner']['tmp_name'];
    $fileName = $_FILES['banner']['name'];
    
    // 3. Generamos la ruta de destino única
    $dest_path = $uploadFileDir . time() . '_' . $fileName;

    // 4. Movemos el archivo de la carpeta temporal a la definitiva
    if (move_uploaded_file($fileTmpPath, $dest_path)) {
        $banner_final = $dest_path; // Actualizamos la variable con la nueva ruta
        
        // Controlamos que no intente borrar algo vacío o inexistente
        if (!empty($_POST['banner_current']) && file_exists($_POST['banner_current'])) {
            unlink($_POST['banner_current']);
        }
    }
}


    $stmt = $pdo->prepare("UPDATE concessionnaire SET nom = ?, description = ?, adresse = ?, site_web = ?, photo_banniere = ? WHERE id = ?");
    $stmt->execute([$nom, $description, $address, $lien1, $banner_final, $id_concessionnaire]);
    // 5. Redireccionar al usuario para evitar que reenvíe el formulario al recargar
    header('Location: Profil_Dealer.php');
    exit;
}