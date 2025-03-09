<?php
header('Content-Type: application/json');
require '../db.php';

try {
    $stmt = $pdo->query("SELECT id_groupe, nom_groupe, specialite_groupe FROM GROUPE ORDER BY nom_groupe");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>