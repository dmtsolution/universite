<?php
session_start();
header('Content-Type: application/json');
require '../db.php';

try {
    $id_enseignant = $_SESSION['user_id']; 

    $stmt = $pdo->prepare("SELECT id_seance, titre_seance, date_seance
                          FROM SEANCE
                          WHERE id_enseignant = ?
                          ORDER BY date_seance DESC");
    $stmt->execute([$id_enseignant]);
    $sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($sessions);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>