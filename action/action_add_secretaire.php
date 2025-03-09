<?php
session_start();
header('Content-Type: application/json');
require '../db.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
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
    $nom = trim($_POST['nom_secretaire'] ?? '');
    $prenom = trim($_POST['prenom_secretaire'] ?? '');
    $email = trim($_POST['email_secretaire'] ?? '');
    $mot_de_passe = trim($_POST['mot_de_passe'] ?? '');

    if (empty($nom) || empty($prenom) || empty($email) || empty($mot_de_passe)) {
        throw new Exception('Tous les champs sont requis');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Format d\'email invalide');
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
        $stmt = $pdo->prepare("INSERT INTO UTILISATEUR (email_utilisateur, mot_de_passe, role) VALUES (?, ?, 'secretaire')");
        $stmt->execute([$email, $hashed_password]);
        $id_secretaire = $pdo->lastInsertId();

        // Then insert into SECRETAIRE
        $stmt = $pdo->prepare("INSERT INTO SECRETAIRE (id_secretaire, nom_secretaire, prenom_secretaire) VALUES (?, ?, ?)");
        $stmt->execute([$id_secretaire, $nom, $prenom]);

        $pdo->commit();
        $response['success'] = true;
        $response['message'] = 'Secrétaire ajouté avec succès';

    } catch (PDOException $e) {
        $pdo->rollBack();
        throw new Exception('Erreur lors de l\'insertion dans la base de données: ' . $e->getMessage());
    }

} catch (Exception $e) {
    $response['message'] = 'Erreur: ' . $e->getMessage();
}

echo json_encode($response);
?>