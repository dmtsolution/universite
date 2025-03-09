<?php
header('Content-Type: application/json');
require '../db.php';

try {
    $stmt = $pdo->prepare("
        SELECT
            d.id_depo,
            d.fichier_depo,
            d.commentaire_depo,
            d.date_depo,
            e.id_eleve,
            e.nom_eleve,
            e.prenom_eleve,
            ex.titre_exercice,
            ex.type_exercice
        FROM DEPO_EXERCICE d
        INNER JOIN ELEVE e ON d.id_eleve = e.id_eleve
        INNER JOIN EXERCICE ex ON d.id_exercice = ex.id_exercice
        LEFT JOIN NOTE n ON d.id_depo = n.id_depo
        WHERE n.id_note IS NULL
        ORDER BY d.date_depo DESC
    ");

    $stmt->execute();
    $submissions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($submissions)) {
        echo json_encode([
            'success' => true,
            'message' => 'Aucun exercice à noter',
            'submissions' => []
        ]);
        return;
    }

    echo json_encode([
        'success' => true,
        'submissions' => $submissions
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur: ' . $e->getMessage()
    ]);
}
?>