<?php
session_start();
header('Content-Type: application/json');
require '../db.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'enseignant'){
    header('Location: ../index.php');
    exit();
}

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id_depo'], $data['id_eleve'], $data['points'])) {
        throw new Exception('Données manquantes');
    }

    $points = floatval($data['points']);
    if ($points < 0 || $points > 20) {
        throw new Exception('La note doit être comprise entre 0 et 20');
    }

    $stmt = $pdo->prepare("INSERT INTO NOTE (id_eleve, id_depo, points) VALUES (?, ?, ?)");
    $stmt->execute([$data['id_eleve'], $data['id_depo'], $points]);

    echo json_encode([
        'success' => true,
        'message' => 'Note enregistrée avec succès'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur: ' . $e->getMessage()
    ]);
}
?>