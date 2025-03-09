<?php
// get_eleve.php - Récupère la liste des étudiants
header('Content-Type: application/json');
require '../db.php';

try {
    $stmt = $pdo->prepare("
        SELECT 
            id_eleve,
            nom_eleve,
            prenom_eleve
        FROM ELEVE
        ORDER BY nom_eleve, prenom_eleve
    ");
    
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'students' => $students
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur: ' . $e->getMessage()
    ]);
}
?>