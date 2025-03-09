<?php
// action_add_exam_note.php - Ajoute une note d'examen
session_start();
header('Content-Type: application/json');
require '../db.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'enseignant'){
    header('Location: ../index.php');
    exit();
}

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['id_eleve'], $data['id_cours'], $data['points'])) {
        throw new Exception('Données manquantes');
    }
    
    $points = floatval($data['points']);
    if ($points < 0 || $points > 20) {
        throw new Exception('La note doit être comprise entre 0 et 20');
    }
    
    // Vérifier que l'élève existe
    $checkStudent = $pdo->prepare("SELECT id_eleve FROM ELEVE WHERE id_eleve = ?");
    $checkStudent->execute([$data['id_eleve']]);
    if (!$checkStudent->fetch()) {
        throw new Exception('Élève inexistant');
    }
    
    // Vérifier que le cours existe et appartient à l'enseignant
    $checkCourse = $pdo->prepare("SELECT id_cours FROM COURS WHERE id_cours = ? AND id_enseignant = ?");
    $checkCourse->execute([$data['id_cours'], $_SESSION['user_id']]);
    if (!$checkCourse->fetch()) {
        throw new Exception('Cours inexistant ou non autorisé');
    }
    
    // Vérifier si une note existe déjà pour cet élève et ce cours
    $checkExisting = $pdo->prepare("SELECT id_examen FROM EXAMEN WHERE id_eleve = ? AND id_cours = ?");
    $checkExisting->execute([$data['id_eleve'], $data['id_cours']]);
    if ($checkExisting->fetch()) {
        // Si une note existe déjà, renvoyer un message spécifique
        echo json_encode([
            'success' => false,
            'message' => 'Note déjà ajoutée pour cet élève et ce cours'
        ]);
        exit();
    }

    
    // Insérer la note d'examen
    $stmt = $pdo->prepare("INSERT INTO EXAMEN (id_eleve, id_cours, points, explication) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $data['id_eleve'],
        $data['id_cours'],
        $points,
        $data['explication'] ?? null
    ]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Note d\'examen enregistrée avec succès'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur: ' . $e->getMessage()
    ]);
}
?>