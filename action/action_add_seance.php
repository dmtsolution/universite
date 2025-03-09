<?php
session_start();
header('Content-Type: application/json');
require '../db.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] != 'enseignant' && $_SESSION['role'] != 'secretaire')) {
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
    $titre = trim($_POST['titre_seance'] ?? '');
    $description = trim($_POST['description_seance'] ?? '');
    $date = trim($_POST['date_seance'] ?? '');
    $debut = trim($_POST['debut_seance'] ?? '');
    $fin = trim($_POST['fin_seance'] ?? '');
    $type = trim($_POST['type_seance'] ?? '');
    $salle = trim($_POST['salle_seance'] ?? '');
    $id_cours = intval($_POST['id_cours'] ?? 0);

    // Validate required fields
    if (empty($titre) || empty($date) || empty($debut) || empty($fin) ||
        empty($type) || empty($salle) || $id_cours <= 0) {
        throw new Exception('Tous les champs obligatoires doivent être remplis');
    }

    // Validate session type
    if (!in_array($type, ['CM', 'TD', 'TP'])) {
        throw new Exception('Type de séance invalide');
    }

    // Validate time range
    if ($debut >= $fin) {
        throw new Exception('L\'heure de fin doit être postérieure à l\'heure de début');
    }

    // Get teacher ID from course
    $stmt = $pdo->prepare("SELECT id_enseignant FROM COURS WHERE id_cours = ?");
    $stmt->execute([$id_cours]);
    $id_enseignant = $stmt->fetchColumn();

    if (!$id_enseignant) {
        throw new Exception('Cours non trouvé');
    }

    // Insert session
    $stmt = $pdo->prepare("INSERT INTO SEANCE (titre_seance, description_seance, date_seance,
                          debut_seance, fin_seance, type_seance, salle_seance,
                          id_cours, id_enseignant)
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->execute([$titre, $description, $date, $debut, $fin, $type,
                   $salle, $id_cours, $id_enseignant]);

    $response['success'] = true;
    $response['message'] = 'Séance créée avec succès';

} catch (Exception $e) {
    $response['message'] = 'Erreur: ' . $e->getMessage();
}

echo json_encode($response);
?>