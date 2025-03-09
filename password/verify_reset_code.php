<?php
session_start();
header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => ''
];

try {
    // Get JSON data
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['email']) || empty($data['email']) || !isset($data['code']) || empty($data['code'])) {
        throw new Exception('Email et code requis');
    }

    $email = trim($data['email']);
    $code = trim($data['code']);

    // Check if reset code exists in session
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

    // Code is valid
    $response['success'] = true;
    $response['message'] = 'Code vérifié avec succès';

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>