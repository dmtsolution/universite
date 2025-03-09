<?php
session_start();
header('Content-Type: application/json');
require '../db.php';

try {
    $stmt = $pdo->prepare("
        SELECT DISTINCT
            c.nom_cours,
            ex.type_exercice,
            n.points,
            n.date_note
        FROM NOTE n
        INNER JOIN DEPO_EXERCICE d ON n.id_depo = d.id_depo
        INNER JOIN EXERCICE ex ON d.id_exercice = ex.id_exercice
        INNER JOIN SEANCE s ON ex.id_seance = s.id_seance
        INNER JOIN COURS c ON s.id_cours = c.id_cours
        WHERE n.id_eleve = ?
        ORDER BY c.nom_cours, ex.type_exercice
    ");
    $id = $_SESSION['user_id'];
    $stmt->execute([$id]);
    $grades = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'grades' => $grades
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur de base de données'
    ]);
}
?>