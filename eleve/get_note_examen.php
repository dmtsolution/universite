<?php
session_start();
header('Content-Type: application/json');
require '../db.php';

try {
    // Récupérer les notes d'examen de l'élève
    $stmt = $pdo->prepare("
        SELECT
            c.nom_cours,
            e.points,
            e.explication,
            e.date_note
        FROM EXAMEN e
        INNER JOIN COURS c ON e.id_cours = c.id_cours
        WHERE e.id_eleve = ?
        ORDER BY c.nom_cours
    ");
    $id = $_SESSION['user_id'];
    $stmt->execute([$id]);
    $examGrades = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'grades' => $examGrades
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur de base de données: ' . $e->getMessage()
    ]);
}
?>
