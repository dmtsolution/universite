
<!-- Formulaire pour Ajouter un Élève -->
<div id="student-form" class="content-section form-section">
    <h2>Ajouter un Élève</h2>
    <form id="add-student-form">
        <div class="form-group">
            <label for="email_eleve">Email :</label>
            <input type="email" id="email_eleve" name="email_eleve" placeholder="Email" required>
        </div>

        <div class="form-group">
            <label for="mot_de_passe">Mot de passe :</label>
            <input type="password" id="mot_de_passe" name="mot_de_passe" placeholder="Mot de passe" required minlength="6">
        </div>

        <div class="form-group">
            <label for="nom_eleve">Nom :</label>
            <input type="text" id="nom_eleve" name="nom_eleve" placeholder="Nom" required>
        </div>

        <div class="form-group">
            <label for="prenom_eleve">Prénom :</label>
            <input type="text" id="prenom_eleve" name="prenom_eleve" placeholder="Prénom" required>
        </div>

        <div class="form-group">
            <label for="date_naissance">Date de naissance :</label>
            <input type="date" id="date_naissance" name="date_naissance" required>
        </div>

        <div class="form-group">
            <label for="sexe">Sexe :</label>
            <select id="sexe" name="sexe" required>
                <option value="">Sélectionner</option>
                <option value="masculin">Masculin</option>
                <option value="feminin">Féminin</option>
            </select>
        </div>

        <div class="form-group">
            <label for="groupe_select">Groupe :</label>
            <select id="groupe_select" name="id_groupe">
                <option value="">Sélectionner un groupe</option>
                <!-- Options will be populated by JavaScript -->
            </select>
        </div>

        <button type="submit">
            <span class="button-text">Ajouter Élève</span>
            <span class="button-loader" style="display: none;">
                <i class="fas fa-spinner fa-spin"></i>
            </span>
        </button>
    </form>
</div>


<!-- Formulaire pour Ajouter un Enseignant -->
<div id="teacher-form" class="content-section form-section">
    <h2>Ajouter un Enseignant</h2>
    <form id="add-teacher-form">
        <div class="form-group">
            <label for="email_utilisateur">Email :</label>
            <input type="email" name="email_enseignant" id="email_utilisateur" placeholder="Email" required>
        </div>

        <div class="form-group">
            <label for="mot_de_passe">Mot de passe :</label>
            <input type="password" name="mot_de_passe" id="mot_de_passe" placeholder="Mot de passe" required>
        </div>

        <div class="form-group">
            <label for="nom_enseignant">Nom :</label>
            <input type="text" name="nom_enseignant" id="nom_enseignant" placeholder="Nom" required>
        </div>

        <div class="form-group">
            <label for="prenom_enseignant">Prénom :</label>
            <input type="text" name="prenom_enseignant" id="prenom_enseignant" placeholder="Prénom" required>
        </div>

        <div class="form-group">
            <label for="specialite">Spécialité :</label>
            <input type="text" name="specialite" id="specialite" placeholder="Spécialité" required>
        </div>

        <div class="form-group">
            <label for="fonction_enseignant">Fonction :</label>
            <select name="fonction_enseignant" id="fonction_enseignant" required>
                <option value="">Sélectionner la fonction</option>
                <option value="Vacataire">Vacataire</option>
                <option value="ATER">ATER</option>
                <option value="MdC">MdC</option>
                <option value="Professeur">Professeur</option>
            </select>
        </div>

        <button type="submit">Ajouter</button>
    </form>
</div>

<!-- Formulaire pour la Création de Cours -->
<div id="course-form" class="content-section form-section">
    <h2>Création de Cours</h2>
    <form>
        <div class="form-group">
            <label for="nom_cours">Nom du cours :</label>
            <input type="text" name="nom_cours" id="nom_cours" placeholder="Nom du cours" required>
        </div>

        <div class="form-group">
            <label for="volume_horaire">Volume horaire :</label>
            <input type="number" name="volume_horaire" id="volume_horaire" placeholder="Volume horaire" required>
        </div>

        <div class="form-group">
            <label for="annee_cours">Année :</label>
            <input type="number" name="annee_cours" id="annee_cours" placeholder="Année" required>
        </div>

        <div class="form-group">
            <label for="semestre">Semestre :</label>
            <select name="semestre" id="semestre" required>
                <option value="">Sélectionner le semestre</option>
                <option value="1">Semestre 1</option>
                <option value="2">Semestre 2</option>
            </select>
        </div>

        <div class="form-group">
            <label for="id_enseignant">Enseignant :</label>
            <select name="id_enseignant" id="id_enseignant" required>
                <option value="">Sélectionner l'enseignant</option>
            </select>
        </div>

        <button type="submit">Créer</button>
    </form>
</div>

<!-- Formulaire pour la Gestion des Groupes -->
<div id="group-form" class="content-section form-section">
    <h2>Gestion des Groupes</h2>
    <form>
        <div class="form-group">
            <label for="nom_groupe">Nom du groupe :</label>
            <input type="text" name="nom_groupe" id="nom_groupe" placeholder="Nom du groupe" required>
        </div>

        <div class="form-group">
            <label for="specialite_groupe">Spécialité :</label>
            <input type="text" name="specialite_groupe" id="specialite_groupe" placeholder="Spécialité" required>
        </div>

        <button type="submit">Créer Groupe</button>
    </form>
</div>

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