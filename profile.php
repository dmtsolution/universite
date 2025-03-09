<?php
// Make sure we have a database connection
require_once 'db.php';

// Get user information from database based on role
function getUserInfo($userId) {
    global $pdo;

    try {
        // First get the user's role from UTILISATEUR table
        $userQuery = $pdo->prepare("
            SELECT id_utilisateur, email_utilisateur, role, date_inscription
            FROM UTILISATEUR
            WHERE id_utilisateur = ?
        ");

        $userQuery->execute([$userId]);
        $userData = $userQuery->fetch(PDO::FETCH_ASSOC);

        if (!$userData) {
            return false;
        }

        // Based on role, get additional information from the appropriate table
        $roleData = [];
        switch ($userData['role']) {
            case 'admin':
                $roleQuery = $pdo->prepare("
                    SELECT nom_admin as nom, prenom_admin as prenom
                    FROM ADMIN
                    WHERE id_admin = ?
                ");
                $roleQuery->execute([$userId]);
                $roleData = $roleQuery->fetch(PDO::FETCH_ASSOC);
                break;

            case 'secretaire':
                $roleQuery = $pdo->prepare("
                    SELECT nom_secretaire as nom, prenom_secretaire as prenom
                    FROM SECRETAIRE
                    WHERE id_secretaire = ?
                ");
                $roleQuery->execute([$userId]);
                $roleData = $roleQuery->fetch(PDO::FETCH_ASSOC);
                break;

            case 'eleve':
                $roleQuery = $pdo->prepare("
                    SELECT
                        nom_eleve as nom,
                        prenom_eleve as prenom,
                        date_naissance,
                        sexe,
                        g.nom_groupe
                    FROM ELEVE e
                    LEFT JOIN GROUPE g ON e.id_groupe = g.id_groupe
                    WHERE id_eleve = ?
                ");
                $roleQuery->execute([$userId]);
                $roleData = $roleQuery->fetch(PDO::FETCH_ASSOC);
                break;

            case 'enseignant':
                $roleQuery = $pdo->prepare("
                    SELECT
                        nom_enseignant as nom,
                        prenom_enseignant as prenom,
                        specialite,
                        fonction_enseignant
                    FROM ENSEIGNANT
                    WHERE id_enseignant = ?
                ");
                $roleQuery->execute([$userId]);
                $roleData = $roleQuery->fetch(PDO::FETCH_ASSOC);
                break;
        }

        // Merge the user data with role-specific data
        return array_merge($userData, $roleData ?? []);

    } catch (PDOException $e) {
        // Log error or handle it appropriately
        return false;
    }
}

// Get the user info if user is logged in
$userInfo = isset($_SESSION['user_id']) ? getUserInfo($_SESSION['user_id']) : null;
?>

<!-- User Profile Section - Make sure this ID matches what's used in JavaScript -->
<div id="user-profile" class="content-section">
    <div class="section-header">
        <h2><i class="fas fa-user-circle"></i> Mon Profil</h2>
    </div>

    <div class="profile-container">
        <?php if ($userInfo): ?>
            <div class="profile-card">
                <div class="profile-header">
                    <div class="profile-avatar">
                        <i class="fas fa-user-circle fa-5x"></i>
                    </div>
                    <h3><?php echo htmlspecialchars($userInfo['prenom'] . ' ' . $userInfo['nom']); ?></h3>
                    <p class="profile-role"><?php echo htmlspecialchars(ucfirst($userInfo['role'])); ?></p>
                </div>

                <div class="profile-details">
                    <div class="profile-info-item">
                        <span class="info-label"><i class="fas fa-envelope"></i> Email:</span>
                        <span class="info-value"><?php echo htmlspecialchars($userInfo['email_utilisateur']); ?></span>
                    </div>

                    <div class="profile-info-item">
                        <span class="info-label"><i class="fas fa-calendar-alt"></i> Inscrit depuis:</span>
                        <span class="info-value"><?php echo date('d/m/Y', strtotime($userInfo['date_inscription'])); ?></span>
                    </div>

                    <?php if ($userInfo['role'] === 'eleve'): ?>
                        <div class="profile-info-item">
                            <span class="info-label"><i class="fas fa-birthday-cake"></i> Date de naissance:</span>
                            <span class="info-value"><?php echo date('d/m/Y', strtotime($userInfo['date_naissance'])); ?></span>
                        </div>

                        <div class="profile-info-item">
                            <span class="info-label"><i class="fas fa-venus-mars"></i> Sexe:</span>
                            <span class="info-value"><?php echo $userInfo['sexe'] === 'masculin' ? 'Masculin' : 'Féminin'; ?></span>
                        </div>

                        <?php if (isset($userInfo['nom_groupe'])): ?>
                        <div class="profile-info-item">
                            <span class="info-label"><i class="fas fa-users"></i> Groupe:</span>
                            <span class="info-value"><?php echo htmlspecialchars($userInfo['nom_groupe'] ?? 'Non assigné'); ?></span>
                        </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if ($userInfo['role'] === 'enseignant'): ?>
                        <div class="profile-info-item">
                            <span class="info-label"><i class="fas fa-book"></i> Spécialité:</span>
                            <span class="info-value"><?php echo htmlspecialchars($userInfo['specialite']); ?></span>
                        </div>

                        <div class="profile-info-item">
                            <span class="info-label"><i class="fas fa-user-tie"></i> Fonction:</span>
                            <span class="info-value"><?php echo htmlspecialchars($userInfo['fonction_enseignant']); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="error-message">
                <p>Impossible de charger les informations de l'utilisateur.</p>
            </div>
        <?php endif; ?>
    </div>
</div>