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

    // Get and validate input
    $id_seance = intval($_POST['id_seance'] ?? 0);
    $question = trim($_POST['question_seance'] ?? '');

    // Using a default student ID for demonstration
    $id_eleve = $_SESSION['user_id'];;

    // Validate required fields
    if ($id_seance <= 0 || empty($question)) {
        throw new Exception('Tous les champs sont obligatoires');
    }

    // Verify if session exists
    $stmt = $pdo->prepare("SELECT id_seance FROM SEANCE WHERE id_seance = ?");
    $stmt->execute([$id_seance]);
    if (!$stmt->fetch()) {
        throw new Exception('Séance non trouvée');
    }

    // Insert question
    $stmt = $pdo->prepare("INSERT INTO QUESTION (id_seance, id_eleve, question)
                          VALUES (?, ?, ?)");
    $stmt->execute([$id_seance, $id_eleve, $question]);

    $response['success'] = true;
    $response['message'] = 'Question enregistrée avec succès';

} catch (Exception $e) {
    $response['message'] = 'Erreur: ' . $e->getMessage();
}

echo json_encode($response);
?>