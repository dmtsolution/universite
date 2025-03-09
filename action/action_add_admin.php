<?php
header('Content-Type: application/json');
require '../db.php';

$response = [
    'success' => false,
    'message' => ''
];

try {
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        throw new Exception('Méthode non autorisée');
    }

    // Get and validate input
    $nom = trim($_POST['nom_admin'] ?? '');
    $prenom = trim($_POST['prenom_admin'] ?? '');
    $email = trim($_POST['email_admin'] ?? '');
    $mot_de_passe = trim($_POST['mot_de_passe'] ?? '');

    // Check if all fields are provided
    if (empty($nom) || empty($prenom) || empty($email) || empty($mot_de_passe)) {
        throw new Exception('Tous les champs sont requis');
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Format d\'email invalide');
    }

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM UTILISATEUR WHERE email_utilisateur = ?");
    $stmt->execute([$email]);
    if ($stmt->fetchColumn() > 0) {
        throw new Exception('Cet email est déjà utilisé');
    }

    $pdo->beginTransaction();

    try {
        // Insert into UTILISATEUR table
        $hashed_password = password_hash($mot_de_passe, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO UTILISATEUR (email_utilisateur, mot_de_passe, role) VALUES (?, ?, 'admin')");
        $stmt->execute([$email, $hashed_password]);
        $id_admin = $pdo->lastInsertId();

        // Insert into ADMIN table
        $stmt = $pdo->prepare("INSERT INTO ADMIN (id_admin, nom_admin, prenom_admin) VALUES (?, ?, ?)");
        $stmt->execute([$id_admin, $nom, $prenom]);

        $pdo->commit();
        $response['success'] = true;
        $response['message'] = 'Administrateur ajouté avec succès';

    } catch (PDOException $e) {
        $pdo->rollBack();
        throw new Exception('Erreur lors de l\'insertion dans la base de données: ' . $e->getMessage());
    }

} catch (Exception $e) {
    $response['message'] = 'Erreur: ' . $e->getMessage();
}

echo json_encode($response);
?>
