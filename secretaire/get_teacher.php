<?php
header('Content-Type: application/json');
require '../db.php';

try {
    $stmt = $pdo->query("SELECT id_enseignant, nom_enseignant, prenom_enseignant, specialite
                         FROM ENSEIGNANT
                         ORDER BY nom_enseignant, prenom_enseignant");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>