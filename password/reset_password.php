<?php
session_start();
header('Content-Type: application/json');
require '../db.php';

$response = [
    'success' => false,
    'message' => ''
];

try {
    // Get JSON data
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['email']) || empty($data['email']) ||
        !isset($data['code']) || empty($data['code']) ||
        !isset($data['password']) || empty($data['password'])) {
        throw new Exception('Tous les champs sont requis');
    }

    $email = trim($data['email']);
    $code = trim($data['code']);
    $password = trim($data['password']);

    // Verify code again
    if (!isset($_SESSION['reset_code']) ||
        $_SESSION['reset_code']['email'] !== $email ||
        $_SESSION['reset_code']['code'] !== $code) {
        throw new Exception('Code de vérification invalide');
    }

    // Check if code has expired
    if (time() > $_SESSION['reset_code']['expires']) {
        // Remove expired code
        unset($_SESSION['reset_code']);
        throw new Exception('Code de vérification expiré');
    }

    // Validate password
    if (strlen($password) < 6) {
        throw new Exception('Le mot de passe doit contenir au moins 6 caractères');
    }

    // Hash the new password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Update the password in the database
    $stmt = $pdo->prepare("UPDATE UTILISATEUR SET mot_de_passe = ? WHERE email_utilisateur = ?");
    $result = $stmt->execute([$hashedPassword, $email]);

    if (!$result) {
        throw new Exception('Erreur lors de la mise à jour du mot de passe');
    }

    // Clear the reset code from session
    unset($_SESSION['reset_code']);

    $response['success'] = true;
    $response['message'] = 'Mot de passe réinitialisé avec succès';

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>