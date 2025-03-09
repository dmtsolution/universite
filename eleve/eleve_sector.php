<?php require 'db.php'?>

<!-- Timetable View Section -->
<div id="timetable-view" class="content-section">
    <div class="timetable-container">
        <div class="timetable-header">
            <h1>Emploi du Temps</h1>
        </div>

        <div class="sessions-container">
            <?php
            $frenchDays = array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi');
            $frenchMonths = array('janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre');

            try {
                // Récupérer le mois et l'année courants
                $currentMonth = isset($_GET['month']) ? $_GET['month'] : date('m');
                $currentYear = isset($_GET['year']) ? $_GET['year'] : date('Y');

                // Requête pour récupérer toutes les séances du mois et de l'année sélectionnés
                $query = "
                    SELECT 
                        date_seance,
                        titre_seance,
                        TIME_FORMAT(debut_seance, '%H:%i') as debut,
                        TIME_FORMAT(fin_seance, '%H:%i') as fin,
                        type_seance,
                        salle_seance,
                        nom_cours,
                        nom_enseignant
                    FROM SEANCE s
                    LEFT JOIN COURS c ON s.id_cours = c.id_cours
                    LEFT JOIN ENSEIGNANT e ON s.id_enseignant = e.id_enseignant
                    WHERE MONTH(date_seance) = :month 
                    AND YEAR(date_seance) = :year
                    ORDER BY date_seance ASC, debut_seance ASC
                ";

                $stmt = $pdo->prepare($query);
                $stmt->bindValue(':month', $currentMonth, PDO::PARAM_INT);
                $stmt->bindValue(':year', $currentYear, PDO::PARAM_INT);
                $stmt->execute();
                $seances = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($seances) > 0) {
                    $currentDate = null;

                    foreach ($seances as $seance) {
                        $date = new DateTime($seance['date_seance']);
                        $formattedDate = $frenchDays[$date->format('w')] . ' ' . 
                                       $date->format('d') . ' ' . 
                                       $frenchMonths[$date->format('n')-1] . ' ' . 
                                       $date->format('Y');

                        if ($currentDate !== $seance['date_seance']) {
                            echo "<div class='date-header'>{$formattedDate}</div>";
                            $currentDate = $seance['date_seance'];
                        }

                        echo "<div class='session-card'>";
                        echo "<div class='session-time'>";
                        echo "<span class='time'>{$seance['debut']} - {$seance['fin']}</span>";
                        echo "<span class='session-type {$seance['type_seance']}'>{$seance['type_seance']}</span>";
                        echo "</div>";

                        echo "<div class='session-info'>";
                        echo "<div class='course-title'>{$seance['nom_cours']}</div>";
                        echo "<div class='session-title'>{$seance['titre_seance']}</div>";
                        echo "</div>";

                        echo "<div class='session-details'>";
                        echo "<div class='room'><i class='fas fa-door-open'></i> {$seance['salle_seance']}</div>";
                        echo "<div class='teacher'><i class='fas fa-user'></i> {$seance['nom_enseignant']}</div>";
                        echo "</div>";

                        echo "</div>";
                    }
                } else {
                    echo "<div class='no-sessions'>Aucune séance programmée pour ce mois.</div>";
                }
            } catch (PDOException $e) {
                echo "<div class='error-message'>Erreur: " . htmlspecialchars($e->getMessage()) . "</div>";
            }
            ?>
        </div>
    </div>
</div>



<!-- Exercise Submission Form -->
<div id="exercise-form" class="content-section form-section">
    <h2>Dépôt d'Exercices</h2>
    <form id="submission-form" enctype="multipart/form-data">
        <div class="form-group">
            <label for="id_exercice">Exercice :</label>
            <select name="id_exercice" id="id_exercice" required>
                <option value="">Sélectionner un exercice</option>
            </select>
        </div>
        <div class="form-group">
            <label for="fichier_depo">Fichier :</label>
            <input type="file" name="fichier_depo" id="fichier_depo" required>
        </div>
        <div class="form-group">
            <label for="commentaire_depo">Commentaire :</label>
            <textarea name="commentaire_depo" id="commentaire_depo" placeholder="Commentaire (optionnel)"></textarea>
        </div>
        <button type="submit">
            <span class="button-text">Déposer</span>
            <span class="button-loader" style="display: none;">
                <i class="fas fa-spinner fa-spin"></i>
            </span>
        </button>
    </form>
</div>


<!-- Question Form -->
<div id="question-form" class="content-section form-section">
    <h2>Poser une Question</h2>
    <form>
        <div class="form-group">
            <label for="id_seance_question">Séance :</label>
            <select name="id_seance" id="id_seance_question" required>
                <option value="">Sélectionner une séance</option>
            </select>
        </div>
        <div class="form-group">
            <label for="question_texte">Question :</label>
            <textarea name="question_seance" id="question_texte" placeholder="Votre question" required></textarea>
        </div>
        <button type="submit">Envoyer</button>
    </form>
</div>

<!-- View Grades Section -->
<div id="view-grades" class="content-section form-section">
    <h2 class="section-title">Mes Notes</h2>
    <div class="grades-table">
        <table class="grades-list">
            <thead>
                <tr>
                    <th>Cours</th>
                    <th>Type</th>
                    <th>Note</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody id="grades-body">
            </tbody>
        </table>
    </div>
</div>

<!-- View Exam Grades Section -->
<div id="view-grades-exam" class="content-section form-section">
    <h2 class="section-title">Mes Notes d'Examen</h2>
    <div class="grades-table">
        <table class="grades-list">
            <thead>
                <tr>
                    <th>Modules</th>
                    <th>Type</th>
                    <th>Note</th>
                    <th>Explication</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody id="grades-exam-body">
            </tbody>
        </table>
    </div>
</div>

