<?php
session_start();
header('Content-Type: application/json');
require '../db.php';

try {
    // Modified query to exclude already submitted exercises
    $stmt = $pdo->prepare("
        SELECT
            e.id_exercice,
            e.titre_exercice,
            e.type_exercice,
            s.titre_seance
        FROM EXERCICE e
        INNER JOIN SEANCE s ON e.id_seance = s.id_seance
        LEFT JOIN DEPO_EXERCICE d ON e.id_exercice = d.id_exercice AND d.id_eleve = ?
        WHERE d.id_depo IS NULL
        ORDER BY e.date_exercice DESC
    ");
    $id = $_SESSION['user_id'];
    $stmt->execute([$id]); 
    $exercises = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($exercises);
} catch (Exception $e) {
    error_log('Error in get_exercice.php: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'error' => 'Erreur lors de la récupération des exercices'
    ]);
}
?>