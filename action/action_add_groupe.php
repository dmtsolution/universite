<?php
session_start();
header('Content-Type: application/json');
require '../db.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'secretaire'){
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

    $nom_groupe = trim($_POST['nom_groupe'] ?? '');
    $specialite = trim($_POST['specialite_groupe'] ?? '');

    if (empty($nom_groupe) || empty($specialite)) {
        throw new Exception('Tous les champs sont requis');
    }

    // Check if group name already exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM GROUPE WHERE nom_groupe = ?");
    $stmt->execute([$nom_groupe]);
    if ($stmt->fetchColumn() > 0) {
        throw new Exception('Un groupe avec ce nom existe déjà');
    }

    // Insert new group
    $stmt = $pdo->prepare("INSERT INTO GROUPE (nom_groupe, specialite_groupe) VALUES (?, ?)");
    $stmt->execute([$nom_groupe, $specialite]);

    $response['success'] = true;
    $response['message'] = 'Groupe créé avec succès';

} catch (Exception $e) {
    $response['message'] = 'Erreur: ' . $e->getMessage();
}

echo json_encode($response);
?>