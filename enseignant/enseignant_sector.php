<!-- Session Form -->
<div id="session-form" class="content-section form-section">
    <h2>Création de Séance</h2>
    <form id="add-session-form">
        <div class="form-group">
            <label for="titre_seance">Titre :</label>
            <input type="text" name="titre_seance" id="titre_seance" placeholder="Titre de la séance" required>
        </div>

        <div class="form-group">
            <label for="description_seance">Description :</label>
            <textarea name="description_seance" id="description_seance" placeholder="Description"></textarea>
        </div>

        <div class="form-group">
            <label for="date_seance">Date :</label>
            <input type="date" name="date_seance" id="date_seance" required>
        </div>

        <div class="form-group">
            <label for="debut_seance">Heure de début :</label>
            <input type="time" name="debut_seance" id="debut_seance" required>
        </div>

        <div class="form-group">
            <label for="fin_seance">Heure de fin :</label>
            <input type="time" name="fin_seance" id="fin_seance" required>
        </div>

        <div class="form-group">
            <label for="type_seance">Type :</label>
            <select name="type_seance" id="type_seance" required>
                <option value="">Type de séance</option>
                <option value="CM">CM</option>
                <option value="TD">TD</option>
                <option value="TP">TP</option>
            </select>
        </div>

        <div class="form-group">
            <label for="salle_seance">Salle :</label>
            <input type="text" name="salle_seance" id="salle_seance" placeholder="Salle" required>
        </div>

        <div class="form-group">
            <label for="id_cours_seance">Cours :</label>
            <select name="id_cours" id="id_cours_seance" required>
                <option value="">Sélectionner le cours</option>
            </select>
        </div>

        <button type="submit">
            <span class="button-text">Créer</span>
            <span class="button-loader" style="display: none;">
                <i class="fas fa-spinner fa-spin"></i>
            </span>
        </button>
    </form>
</div>

 

<!-- Grade Submissions Form (Exercices) -->
<div id="grades-form" class="content-section form-section">
    <h2>Notation des Exercices</h2>
    <form id="grading-form">
        <div class="form-group">
            <label for="submission-select">Exercice à noter :</label>
            <select name="submission_id" id="submission-select" required>
                <option value="">Sélectionner un exercice</option>
            </select>
        </div>
        
        <div id="submission-details" style="display: none;">
            <div class="submission-info"></div>
            <div class="form-group">
                <label for="points">Note (/20) :</label>
                <input type="number" name="points" id="points" min="0" max="20" step="0.25" required>
            </div>
            <button type="submit">Enregistrer la note</button>
        </div>
    </form>
</div>

<!-- Exam Grading Form (Examens) -->
<div id="exam-grades-form" class="content-section form-section">
    <h2>Notation des Examens</h2>
    <form id="exam-grading-form">
        <div class="form-group">
            <label for="student-select">Élève :</label>
            <select name="student_id" id="student-select" required>
                <option value="">Sélectionner un élève</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="course-select">Type d'examen (cours) :</label>
            <select name="course_id" id="course-select" required>
                <option value="">Sélectionner un cours</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="exam-points">Note (/20) :</label>
            <input type="number" name="exam_points" id="exam-points" min="0" max="20" step="0.25" required>
        </div>
        
        <div class="form-group">
            <label for="explanation">Explication de la note :</label>
            <textarea name="explanation" id="explanation" rows="4"></textarea>
        </div>
        
        <button type="submit">Enregistrer la note d'examen</button>
    </form>
</div>


<!-- Manage Exercises Form -->
<div id="manage-exercises" class="content-section form-section">
    <h2>Gestion des Exercices</h2>
    <form id="exercise-seance-form" enctype="multipart/form-data">
        <div class="form-group">
            <label for="titre_exercice">Titre :</label>
            <input type="text" name="titre_exercice" id="titre_exercice" placeholder="Titre de l'exercice" required>
        </div>
        <div class="form-group">
            <label for="id_seance_exercice">Séance :</label>
            <select name="id_seance" id="id_seance_exercice" required>
                <option value="">Sélectionner une séance</option>
            </select>
        </div>
        <div class="form-group">
            <label for="type_exercice">Type :</label>
            <select name="type_exercice" id="type_exercice" required>
                <option value="">Sélectionner le type</option>
                <option value="TD">TD</option>
                <option value="TP">TP</option>
            </select>
        </div>
        <div class="form-group">
            <label for="commentaire_exercice">Commentaire :</label>
            <textarea name="commentaire_exercice" id="commentaire_exercice" placeholder="Commentaire sur l'exercice"></textarea>
        </div>
        <div class="form-group">
            <label for="fichier_exercice">Fichier :</label>
            <input type="file" name="fichier_exercice" id="fichier_exercice" required>
        </div>
        <button type="submit">
            <span class="button-text">Créer Exercice</span>
            <span class="button-loader" style="display: none;">
                <i class="fas fa-spinner fa-spin"></i>
            </span>
        </button>
    </form>
</div>

<!-- Questions et Réponses des Élèves -->
<div id="questions-reponses-section" class="content-section form-section">
    <h2>Questions des Élèves</h2>
    <div id="questions-container">
        <p class="text-center">Chargement des questions...</p>
    </div>
</div>
