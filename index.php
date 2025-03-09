
<?php
session_start();
 
// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>
 
<!-- Header -->
<?php include 'header.php' ?>
 
    <!-- Toggle button -->
    <button class="sidebar-toggle">
        <i class="fas fa-bars"></i>
    </button>
 
    <!-- Profile and Logout buttons -->
    <div class="top-right-controls">
        <!-- Profile button -->
        <button class="profile-btn" id="profile-btn">
            <i class="fas fa-user-circle"></i>
            <span>Profil</span>
        </button>
        
        <!-- Logout button -->
        <form action="action/action_deconnexion.php" method="POST" style="margin: 0;">
            <button type="submit" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Déconnexion</span>
            </button>
        </form>
    </div>
 
    <div class="sidebar-overlay"></div>
 
    <div class="wrapper">
        <!-- Sidebar -->
        <?php
        require_once 'sidebar.php';
        echo generateSidebar();
        ?>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Welcome Message -->
            <div id="welcome-message" class="content-section active">
                <h1>Bienvenue</h1>
                <p>Sélectionnez une option dans le menu pour commencer</p>
            </div>
     
            <!-- User Profile Section -->
            <?php require 'profile.php' ?>
            
            <!-- Admin Forms -->
            <?php require 'admin/admin_sector.php' ?>

            <!-- Secretary Forms -->
            <?php require 'secretaire/secretaire_sector.php' ?>
            
            <!-- Teacher Forms -->
            <?php require 'enseignant/enseignant_sector.php' ?>
            
            <!-- Student Forms -->
            <?php require 'eleve/eleve_sector.php' ?>

        </main>
    </div>

<!-- Footer -->
<?php require 'footer.php' ?>
