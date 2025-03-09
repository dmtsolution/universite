<?php
session_start();
header('Content-Type: application/json');
require '../db.php';

$response = [
    'success' => false,
    'message' => '',
    'redirect' => ''
];

try {
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        throw new Exception('Méthode non autorisée');
    }

    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        throw new Exception('Tous les champs sont requis');
    }

    $stmt = $pdo->prepare("
        SELECT id_utilisateur, email_utilisateur, mot_de_passe, role
        FROM UTILISATEUR
        WHERE email_utilisateur = ?
    ");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['mot_de_passe'])) {
        throw new Exception('Email ou mot de passe incorrect');
    }

    // Set session variables
    $_SESSION['user_id'] = $user['id_utilisateur'];
    $_SESSION['role'] = $user['role'];

    $response['success'] = true;
    $response['redirect'] = 'index.php';

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>