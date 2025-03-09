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
    $nom = trim($_POST['nom_eleve'] ?? '');
    $prenom = trim($_POST['prenom_eleve'] ?? '');
    $email = trim($_POST['email_eleve'] ?? '');
    $mot_de_passe = trim($_POST['mot_de_passe'] ?? '');
    $date_naissance = trim($_POST['date_naissance'] ?? '');
    $sexe = trim($_POST['sexe'] ?? '');
    $id_groupe = !empty($_POST['id_groupe']) ? $_POST['id_groupe'] : null;

    if (empty($nom) || empty($prenom) || empty($email) || empty($mot_de_passe) ||
        empty($date_naissance) || empty($sexe)) {
        throw new Exception('Tous les champs obligatoires sont requis');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Format d\'email invalide');
    }

    if (!in_array($sexe, ['masculin', 'feminin'])) {
        throw new Exception('Valeur invalide pour le sexe');
    }

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM UTILISATEUR WHERE email_utilisateur = ?");
    $stmt->execute([$email]);
    if ($stmt->fetchColumn() > 0) {
        throw new Exception('Cet email est déjà utilisé');
    }

    $pdo->beginTransaction();

    try {
        // Insert into UTILISATEUR first
        $hashed_password = password_hash($mot_de_passe, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO UTILISATEUR (email_utilisateur, mot_de_passe, role) VALUES (?, ?, 'eleve')");
        $stmt->execute([$email, $hashed_password]);
        $id_eleve = $pdo->lastInsertId();

        // Then insert into ELEVE
        $stmt = $pdo->prepare("INSERT INTO ELEVE (id_eleve, nom_eleve, prenom_eleve, date_naissance, sexe, id_groupe)
                              VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$id_eleve, $nom, $prenom, $date_naissance, $sexe, $id_groupe]);

        $pdo->commit();
        $response['success'] = true;
        $response['message'] = 'Élève ajouté avec succès';

    } catch (PDOException $e) {
        $pdo->rollBack();
        throw new Exception('Erreur lors de l\'insertion dans la base de données: ' . $e->getMessage());
    }

} catch (Exception $e) {
    $response['message'] = 'Erreur: ' . $e->getMessage();
}

echo json_encode($response);
?>