<?php
session_start();
header('Content-Type: application/json');
require '../db.php';

// Vérifier si l'utilisateur est connecté (ID et rôle dans la session)
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;

if (!$user_id || !$role) {
    echo json_encode(['error' => 'Utilisateur non connecté']);
    exit;
}

try {
    // Si l'utilisateur est secrétaire, récupérer tous les cours
    if ($role === 'secretaire') {
        $stmt = $pdo->prepare("SELECT c.id_cours, c.nom_cours, e.nom_enseignant
                               FROM COURS c
                               JOIN ENSEIGNANT e ON c.id_enseignant = e.id_enseignant
                               ORDER BY c.nom_cours");
    } else {
        // Sinon, récupérer uniquement les cours de l'enseignant connecté
        $stmt = $pdo->prepare("SELECT c.id_cours, c.nom_cours, e.nom_enseignant
                               FROM COURS c
                               JOIN ENSEIGNANT e ON c.id_enseignant = e.id_enseignant
                               WHERE c.id_enseignant = :user_id
                               ORDER BY c.nom_cours");

        // Lier l'ID de l'enseignant à la requête
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    }

    $stmt->execute();

    // Retourner les résultats sous forme de JSON
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (Exception $e) {
    // En cas d'erreur, renvoyer une erreur 500
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
