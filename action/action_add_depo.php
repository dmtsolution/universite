<?php
session_start();
header('Content-Type: application/json');
require '../db.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'eleve'){
    header('Location: ../index.php');
    exit();
}

$response = [
    'success' => false,
    'message' => ''
];

try {
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        throw new Exception('Méthode non autorisée');
    }

    // Default student ID
    $id_eleve = $_SESSION['user_id'];
    $id_exercice = intval($_POST['id_exercice'] ?? 0);
    $commentaire = trim($_POST['commentaire_depo'] ?? '');

    // Validate exercise ID
    if ($id_exercice <= 0) {
        throw new Exception('Exercice non sélectionné');
    }

    // Validate and handle file upload
    if (!isset($_FILES['fichier_depo']) || $_FILES['fichier_depo']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Erreur lors du téléchargement du fichier');
    }

    $file = $_FILES['fichier_depo'];
    $fileName = basename($file['name']);
    $targetDir = '../fichier_depo/';

    // Create upload directory if it doesn't exist
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Generate unique filename
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
    $uniqueFileName = uniqid() . '_' . time() . '.' . $fileExtension;
    $targetPath = $targetDir . $uniqueFileName;

    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        throw new Exception('Erreur lors de l\'enregistrement du fichier');
    }

    // Insert submission into database
    $stmt = $pdo->prepare("INSERT INTO DEPO_EXERCICE (id_exercice, id_eleve, fichier_depo, commentaire_depo)
                          VALUES (?, ?, ?, ?)");

    $stmt->execute([$id_exercice, $id_eleve, $uniqueFileName, $commentaire]);

    $response['success'] = true;
    $response['message'] = 'Exercice déposé avec succès';

} catch (Exception $e) {
    $response['message'] = 'Erreur: ' . $e->getMessage();
    // Clean up uploaded file if it exists and there was an error
    if (isset($targetPath) && file_exists($targetPath)) {
        unlink($targetPath);
    }
}

echo json_encode($response);
?>