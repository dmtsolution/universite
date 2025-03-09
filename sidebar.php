<?php
function generateSidebar() {
    if (!isset($_SESSION['role'])) {
        return '';
    }

    $role = $_SESSION['role'];

    $sidebar = '<nav class="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-university"></i>
            <h2>Admin Uni</h2>
        </div>
        <ul class="sidebar-menu">';

    // Admin section
    if ($role === 'admin') {
        $sidebar .= '
        <li class="menu-section">
            <h3>Admin</h3>
            <ul>
                <li data-target="timetable-view">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Emploi du temps</span>
                </li>
                <li data-target="adm-form">
                    <i class="fas fa-user-shield"></i>
                    <span>Ajout Admin</span>
                </li>
                <li data-target="secretary-form">
                    <i class="fas fa-user-tie"></i>
                    <span>Ajout Secrétaire</span>
                </li>
            </ul>
        </li>';
    }

    // Secretary section (visible to both secretary and admin)
    if ($role === 'secretaire') {
        $sidebar .= '
        <li class="menu-section">
            <h3>Secrétaire</h3>
            <ul>
                <li data-target="timetable-view">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Emploi du temps</span>
                </li>
                <li data-target="session-form">
                    <i class="fas fa-clock"></i>
                    <span>Création Séance</span>
                </li>
                <li data-target="student-form">
                    <i class="fas fa-user-graduate"></i>
                    <span>Ajout Élève</span>
                </li>
                <li data-target="teacher-form">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Ajout Enseignant</span>
                </li>
                <li data-target="course-form">
                    <i class="fas fa-book"></i>
                    <span>Création Cours</span>
                </li>
                <li data-target="group-form">
                    <i class="fas fa-users"></i>
                    <span>Gestion Groupes</span>
                </li>
            </ul>
        </li>';
    }

    // Teacher section
    if ($role === 'enseignant') {
        $sidebar .= '
        <li class="menu-section">
            <h3>Enseignant</h3>
            <ul>
                <li data-target="timetable-view">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Emploi du temps</span>
                </li>
                <li data-target="session-form">
                    <i class="fas fa-clock"></i>
                    <span>Création Séance</span>
                </li>
                <li data-target="grades-form">
                    <i class="fas fa-star"></i>
                    <span>Gestion Notes</span>
                </li>
                <li data-target="exam-grades-form">
                    <i class="fas fa-scroll"></i>
                    <span>Gestion D\'Examens</span>
                </li>
                <li data-target="manage-exercises">
                    <i class="fas fa-chart-bar"></i>
                    <span>Gestion Exercices</span>
                </li>
                <li data-target="questions-reponses-section">
                    <i class="fas fa-reply"></i>
                    <span>Questions Elèves</span>
                </li>
            </ul>
        </li>';
    }

    // Student section
    if ($role === 'eleve') {
        $sidebar .= '
        <li class="menu-section">
            <h3>Élève</h3>
            <ul>
                <li data-target="timetable-view">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Emploi du temps</span>
                </li>
                <li data-target="exercise-form">
                    <i class="fas fa-file-upload"></i>
                    <span>Dépôt Exercices</span>
                </li>
                <li data-target="question-form">
                    <i class="fas fa-question-circle"></i>
                    <span>Questions</span>
                </li>
                <li data-target="questions-reponses-section">
                    <i class="fas fa-reply"></i>
                    <span>Réponses aux questions</span>
                </li>
                <li data-target="view-grades">
                    <i class="fas fa-chart-bar"></i>
                    <span>Mes Notes D\'Exercice</span>
                </li>
                <li data-target="view-grades-exam">
                    <i class="fas fa-scroll"></i>
                    <span>Mes Notes D\'examen</span>
                </li>
            </ul>
        </li>';
    }

    $sidebar .= '</ul></nav>';

    return $sidebar;
}
?>