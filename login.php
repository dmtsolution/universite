<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion | Admin Uni</title>
    <link rel="stylesheet" href="css/auth.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <i class="fas fa-university"></i>
                <h2>Admin Uni</h2>
            </div>
            <!-- Login Form -->
            <form id="login-form" method="POST">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        required
                        autocomplete="email"
                        placeholder="votre@email.com"
                    >
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="Votre mot de passe"
                    >
                </div>
                <button type="submit">
                    <span class="button-text">Se connecter</span>
                    <span class="button-loader" style="display: none;">
                        <i class="fas fa-spinner fa-spin"></i>
                    </span>
                </button>
                <div class="forgot-password">
                    <a href="#" id="forgot-password-link">Mot de passe oublié?</a>
                </div>
            </form>

            <!-- Password Reset Form (initially hidden) -->
            <form id="reset-password-form" style="display: none;">
                <div id="email-step">
                    <div class="form-group">
                        <label for="reset-email">Email</label>
                        <input
                            type="email"
                            id="reset-email"
                            name="reset-email"
                            required
                            placeholder="votre@email.com"
                        >
                    </div>
                    <button type="button" id="send-code-btn">
                        <span class="button-text">Envoyer le code</span>
                        <span class="button-loader" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i>
                        </span>
                    </button>
                </div>

                <div id="code-step" style="display: none;">
                    <div class="form-group">
                        <label for="verification-code">Code de vérification</label>
                        <input
                            type="text"
                            id="verification-code"
                            name="verification-code"
                            required
                            placeholder="Code à 6 chiffres"
                            maxlength="6"
                        >
                    </div>
                    <button type="button" id="verify-code-btn">
                        <span class="button-text">Vérifier le code</span>
                        <span class="button-loader" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i>
                        </span>
                    </button>
                </div>

                <div id="new-password-step" style="display: none;">
                    <div class="form-group">
                        <label for="new-password">Nouveau mot de passe</label>
                        <input
                            type="password"
                            id="new-password"
                            name="new-password"
                            required
                            placeholder="Minimum 6 caractères"
                            minlength="6"
                        >
                    </div>
                    <div class="form-group">
                        <label for="confirm-password">Confirmer le mot de passe</label>
                        <input
                            type="password"
                            id="confirm-password"
                            name="confirm-password"
                            required
                            placeholder="Confirmer le mot de passe"
                            minlength="6"
                        >
                    </div>
                    <button type="button" id="reset-password-btn">
                        <span class="button-text">Réinitialiser le mot de passe</span>
                        <span class="button-loader" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i>
                        </span>
                    </button>
                </div>

                <div class="back-to-login">
                    <a href="#" id="back-to-login-link">Retour à la connexion</a>
                </div>
            </form>

            <!-- Toast message for notifications -->
            <div id="toast-message" class="toast-message" style="display: none;"></div>
        </div>
    </div>
    <script src="js/auth.js"></script>
</body>
</html>
