<?php
session_start();
require_once '../db.php'; // Adapter selon votre projet

header('Content-Type: application/json');

// Vérifier si l'utilisateur est connecté et récupérer son ID
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Vérifier le rôle de l'utilisateur (enseignant ou élève) dans la session
$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : null;

// Si l'utilisateur n'est pas connecté, renvoyer une erreur
if (!$user_id) {
    echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté']);
    exit;
}

// Requête de base pour récupérer les questions
$query = "
    SELECT 
        q.id_question, 
        q.question, 
        q.date_question, 
        e.nom_eleve, 
        e.prenom_eleve, 
        r.reponse, 
        r.date_reponse, 
        ens.nom_enseignant AS nom_professeur,
        s.titre_seance AS nom_seance,  -- Récupérer le titre de la séance
        s.date_seance AS date_seance  -- Récupérer la date de la séance
    FROM QUESTION q
    JOIN ELEVE e ON q.id_eleve = e.id_eleve
    LEFT JOIN REPONSE r ON q.id_question = r.id_question
    LEFT JOIN ENSEIGNANT ens ON r.id_enseignant = ens.id_enseignant
    LEFT JOIN SEANCE s ON q.id_seance = s.id_seance
";

// Si l'utilisateur est un enseignant, on filtre par les séances de cet enseignant
if ($user_role === 'enseignant') {
    $query .= "
        JOIN COURS c ON s.id_cours = c.id_cours
        WHERE c.id_enseignant = :user_id
    ";
} else {
    // Si l'utilisateur est un élève, il peut voir toutes les questions et réponses
    $query .= "ORDER BY q.date_question DESC";  // Optionnel, si vous souhaitez trier par date
}

// Préparer et exécuter la requête
$stmt = $pdo->prepare($query);

// Si l'utilisateur est un enseignant, on passe l'ID de l'enseignant à la requête
if ($user_role === 'enseignant') {
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
}

$stmt->execute();

// Récupérer les résultats
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ajouter une indication pour savoir si l'utilisateur est un élève ou un enseignant
foreach ($questions as &$question) {
    $question['is_teacher'] = $user_role === 'enseignant';  // Indiquer si l'utilisateur est un enseignant
}

// Retourner les résultats en JSON
echo json_encode(['success' => true, 'questions' => $questions]);
?>
