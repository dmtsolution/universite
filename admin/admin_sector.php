<!-- Admin Form Section -->
<div id="adm-form" class="content-section form-section">
    <h2>Ajouter un Administrateur</h2>
    <form id="add-adm-form">
        <div class="form-group">
            <label for="adm_email">Email :</label>
            <input type="email" id="adm_email" name="email_admin" placeholder="Email" required>
        </div>

        <div class="form-group">
            <label for="adm_password">Mot de passe :</label>
            <input type="password" id="adm_password" name="mot_de_passe" placeholder="Mot de passe" required minlength="6">
        </div>

        <div class="form-group">
            <label for="adm_nom">Nom :</label>
            <input type="text" id="adm_nom" name="nom_admin" placeholder="Nom" required>
        </div>

        <div class="form-group">
            <label for="adm_prenom">Prénom :</label>
            <input type="text" id="adm_prenom" name="prenom_admin" placeholder="Prénom" required>
        </div>

        <input type="hidden" name="role" value="admin">

        <button type="submit">
            <span class="button-text">Ajouter Administrateur</span>
            <span class="button-loader" style="display: none;">
                <i class="fas fa-spinner fa-spin"></i>
            </span>
        </button>
    </form>
</div>

<!-- Secretary Form Section -->
<div id="secretary-form" class="content-section form-section">
    <h2>Ajouter un(e) Secrétaire</h2>
    <form id="add-secretary-form">
        <div class="form-group">
            <label for="secretary_email">Email :</label>
            <input type="email" id="secretary_email" name="email_secretaire" placeholder="Email" required>
        </div>

        <div class="form-group">
            <label for="secretary_password">Mot de passe :</label>
            <input type="password" id="secretary_password" name="mot_de_passe" placeholder="Mot de passe" required minlength="6">
        </div>

        <div class="form-group">
            <label for="secretary_nom">Nom :</label>
            <input type="text" id="secretary_nom" name="nom_secretaire" placeholder="Nom" required>
        </div>

        <div class="form-group">
            <label for="secretary_prenom">Prénom :</label>
            <input type="text" id="secretary_prenom" name="prenom_secretaire" placeholder="Prénom" required>
        </div>

        <input type="hidden" name="role" value="secretaire">

        <button type="submit">
            <span class="button-text">Ajouter Secrétaire</span>
            <span class="button-loader" style="display: none;">
                <i class="fas fa-spinner fa-spin"></i>
            </span>
        </button>
    </form>
</div>