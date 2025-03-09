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
                // Pagination setup
                $sessionsPerPage = 3;
                $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $offset = ($currentPage - 1) * $sessionsPerPage;
                
                // Get current month and year
                $currentMonth = isset($_GET['month']) ? $_GET['month'] : date('m');
                $currentYear = isset($_GET['year']) ? $_GET['year'] : date('Y');
                
                // Get total sessions for pagination
                $countQuery = "SELECT COUNT(*) FROM SEANCE WHERE MONTH(date_seance) = :month AND YEAR(date_seance) = :year";
                $countStmt = $pdo->prepare($countQuery);
                $countStmt->execute([':month' => $currentMonth, ':year' => $currentYear]);
                $totalSessions = $countStmt->fetchColumn();
                $totalPages = ceil($totalSessions / $sessionsPerPage);
                
                // Main query with pagination
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
                    LIMIT :limit OFFSET :offset
                ";
                
                $stmt = $pdo->prepare($query);
                $stmt->bindValue(':month', $currentMonth, PDO::PARAM_INT);
                $stmt->bindValue(':year', $currentYear, PDO::PARAM_INT);
                $stmt->bindValue(':limit', $sessionsPerPage, PDO::PARAM_INT);
                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
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

                    // Pagination controls
                    if ($totalPages > 1) {
                        echo "<div class='pagination'>";
                        for ($i = 1; $i <= $totalPages; $i++) {
                            $activeClass = ($i === $currentPage) ? 'active' : '';
                            echo "<a href='?page={$i}&month={$currentMonth}&year={$currentYear}' class='page-link {$activeClass}'>{$i}</a>";
                        }
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