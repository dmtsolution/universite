<?php
header('Content-Type: application/json');
require '../db.php';

try {
    $stmt = $pdo->prepare("SELECT id_seance, titre_seance, date_seance FROM SEANCE ORDER BY date_seance DESC");
    $stmt->execute();
    $sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($sessions);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>