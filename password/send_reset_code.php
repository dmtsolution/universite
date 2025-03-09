<?php
session_start();
header('Content-Type: application/json');
require '../db.php';
// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require '../vendor/autoload.php';

$response = [
    'success' => false,
    'message' => ''
];

try {
    // Get JSON data
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['email']) || empty($data['email'])) {
        throw new Exception('Email requis');
    }

    $email = trim($data['email']);

    // Check if email exists
    $stmt = $pdo->prepare("SELECT id_utilisateur FROM UTILISATEUR WHERE email_utilisateur = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        // For security reasons, don't reveal that the email doesn't exist
        // Instead, pretend we sent the code
        $response['success'] = true;
        $response['message'] = 'Si cet email existe, un code de vérification a été envoyé';
        echo json_encode($response);
        exit;
    }

    // Generate a 6-digit code
    $code = sprintf("%06d", mt_rand(1, 999999));

    // Store the code in the session with expiration time (10 minutes)
    $_SESSION['reset_code'] = [
        'email' => $email,
        'code' => $code,
        'expires' => time() + (10 * 60) // 10 minutes
    ];

    // Send email with PHPMailer using the working configuration
    $mail = new PHPMailer(true);

    try {
        // Configuration du serveur SMTP (using the working settings from your other project)
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'dmtwoleu@gmail.com';
        $mail->Password = 'wimf tucc iwdq sxyp';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Encodage UTF-8 pour le contenu de l'email
        $mail->CharSet = 'UTF-8';

        // Expéditeur
        $mail->setFrom('dmtwoleu@gmail.com', 'Admin Uni');

        // Destinataire
        $mail->addAddress($email);

        // Contenu de l'email
        $mail->isHTML(true);
        $mail->Subject = 'Réinitialisation de mot de passe - Admin Uni';
        $mail->Body = '
            <p>Bonjour / Bonsoir,</p>
            <p>Vous avez demandé la réinitialisation de votre mot de passe.</p>
            <h2 style="color: #2E86C1; text-align: center;">' . $code . '</h2>
            <p>Ce code est valable pendant 10 minutes.</p>
            <p>Si vous n\'avez pas demandé cette réinitialisation, veuillez ignorer cet email.</p>
            <br>
            <p>Cordialement,</p>
            <p>L\'équipe Admin Uni</p>
        ';
        $mail->AltBody = "Bonjour,\n\nVous avez demandé la réinitialisation de votre mot de passe.\nVoici votre code de vérification : {$code}\n\nCe code est valable pendant 10 minutes.\n\nSi vous n'avez pas demandé cette réinitialisation, veuillez ignorer cet email.\n\nCordialement,\nL'équipe Admin Uni";

        $mail->send();
        $response['success'] = true;
        $response['message'] = 'Si cet email existe, un code de vérification a été envoyé';
    } catch (Exception $e) {
        throw new Exception('Erreur lors de l\'envoi de l\'email: ' . $mail->ErrorInfo);
    }

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>