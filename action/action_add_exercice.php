<?php
session_start();
header('Content-Type: application/json');
require '../db.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'enseignant'){
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

    $id_enseignant = $_SESSION['user_id'];
    $id_seance = intval($_POST['id_seance'] ?? 0);
    $titre_exercice = trim($_POST['titre_exercice'] ?? '');
    $type_exercice = trim($_POST['type_exercice'] ?? '');
    $commentaire = trim($_POST['commentaire_exercice'] ?? '');

    // Validate required fields
    if ($id_seance <= 0 || empty($titre_exercice) || empty($type_exercice)) {
        throw new Exception('Tous les champs obligatoires doivent être remplis');
    }

    // Validate exercise type
    if (!in_array($type_exercice, ['TD', 'TP'])) {
        throw new Exception('Type d\'exercice invalide');
    }

    // Handle file upload
    if (!isset($_FILES['fichier_exercice']) || $_FILES['fichier_exercice']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Erreur lors du téléchargement du fichier');
    }

    $file = $_FILES['fichier_exercice'];
    $fileName = basename($file['name']);
    $targetDir = '../fichier_exercice/';

    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
    $uniqueFileName = uniqid() . '_' . time() . '.' . $fileExtension;
    $targetPath = $targetDir . $uniqueFileName;

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        throw new Exception('Erreur lors de l\'enregistrement du fichier');
    }

    // Insert exercise into database
    $stmt = $pdo->prepare("INSERT INTO EXERCICE (id_seance, id_enseignant, titre_exercice, type_exercice,
                          fichier_exercice, commentaire_exercice)
                          VALUES (?, ?, ?, ?, ?, ?)");

    $stmt->execute([$id_seance, $id_enseignant, $titre_exercice, $type_exercice,
                   $uniqueFileName, $commentaire]);

    $response['success'] = true;
    $response['message'] = 'Exercice créé avec succès';

} catch (Exception $e) {
    $response['message'] = 'Erreur: ' . $e->getMessage();
    if (isset($targetPath) && file_exists($targetPath)) {
        unlink($targetPath);
    }
}

echo json_encode($response);
?>