<?php
session_start();
require_once '../db.php'; // Adapter selon votre projet

header('Content-Type: application/json');

// Vérification de la connexion et du rôle
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'enseignant') {
    echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
    exit();
}

$id_question = $_POST['id_question'] ?? null;
$reponse = trim($_POST['reponse'] ?? '');

if (!$id_question || empty($reponse)) {
    echo json_encode(['success' => false, 'message' => 'Données incomplètes']);
    exit();
}

// Récupérer l'ID de l'enseignant depuis la session
$id_enseignant = $_SESSION['user_id'];

$query = "INSERT INTO REPONSE (id_question, reponse, date_reponse, id_enseignant) VALUES (?, ?, NOW(), ?)";
$stmt = $pdo->prepare($query);

if ($stmt->execute([$id_question, $reponse, $id_enseignant])) {
    echo json_encode(['success' => true, 'message' => 'Réponse ajoutée avec succès']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout de la réponse']);
}
