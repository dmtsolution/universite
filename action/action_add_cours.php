<?php
session_start();
header('Content-Type: application/json');
require '../db.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'secretaire'){
    header('Location: ../index.php');
    exit();
}

$response = [
    'success' => false,
    'message' => ''
];

try {
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        throw new Exception('Méthode non autorisée');
    }

    // Get and validate input
    $nom_cours = trim($_POST['nom_cours'] ?? '');
    $volume_horaire = intval($_POST['volume_horaire'] ?? 0);
    $annee_cours = intval($_POST['annee_cours'] ?? 0);
    $semestre = intval($_POST['semestre'] ?? 0);
    $id_enseignant = intval($_POST['id_enseignant'] ?? 0);

    // Validate required fields
    if (empty($nom_cours) || $volume_horaire <= 0 || $annee_cours <= 0 ||
        !in_array($semestre, [1, 2]) || $id_enseignant <= 0) {
        throw new Exception('Tous les champs sont requis et doivent être valides');
    }

    // Check if teacher exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM ENSEIGNANT WHERE id_enseignant = ?");
    $stmt->execute([$id_enseignant]);
    if ($stmt->fetchColumn() == 0) {
        throw new Exception('Enseignant non trouvé');
    }

    // Insert course
    $stmt = $pdo->prepare("INSERT INTO COURS (nom_cours, volume_horaire, annee_cours, semestre, id_enseignant)
                          VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nom_cours, $volume_horaire, $annee_cours, $semestre, $id_enseignant]);

    $response['success'] = true;
    $response['message'] = 'Cours créé avec succès';

} catch (Exception $e) {
    $response['message'] = 'Erreur: ' . $e->getMessage();
}

echo json_encode($response);
?>