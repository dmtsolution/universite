<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion | Admin Uni</title>
    <link rel="stylesheet" href="css/auth.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* YouTube Video Modal Styles */
        .video-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .video-container {
            position: relative;
            width: 80%;
            max-width: 800px;
        }

        .close-video {
            position: absolute;
            top: -40px;
            right: 0;
            color: white;
            font-size: 24px;
            cursor: pointer;
            background: transparent;
            border: none;
            padding: 5px 10px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: color 0.3s;
        }

        .close-video:hover {
            color: #ff0000;
            background-color: unset;
        }

        .video-iframe {
            width: 100%;
            height: 450px;
            border: none;
        }

        @media (max-width: 768px) {
            .video-container {
                width: 95%;
            }

            .video-iframe {
                height: 300px;
            }
        }
    </style>
</head>
<body>
    <!-- YouTube Video Modal -->
    <div class="video-modal" id="video-modal">
        <div class="video-container">
            <button class="close-video" id="close-video">
                <i class="fas fa-times"></i> Fermer
            </button>
            <iframe
                class="video-iframe"
                src="https://www.youtube.com/embed/SV14ZuYFDIg?si=Xwl37wG19t4nODgh"
                title="YouTube video player"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                referrerpolicy="strict-origin-when-cross-origin"
                allowfullscreen>
            </iframe>
        </div>
    </div>

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
    <script>
        // YouTube video modal functionality
        document.addEventListener('DOMContentLoaded', function() {
            const videoModal = document.getElementById('video-modal');
            const closeVideo = document.getElementById('close-video');

            // Close video when clicking the close button
            closeVideo.addEventListener('click', function() {
                videoModal.style.display = 'none';

                // Stop the video when closed
                const iframe = videoModal.querySelector('iframe');
                const iframeSrc = iframe.src;
                iframe.src = iframeSrc;
            });

            // Close video when clicking outside the video container
            videoModal.addEventListener('click', function(e) {
                if (e.target === videoModal) {
                    videoModal.style.display = 'none';

                    // Stop the video when closed
                    const iframe = videoModal.querySelector('iframe');
                    const iframeSrc = iframe.src;
                    iframe.src = iframeSrc;
                }
            });
        });
    </script>
</body>
</html>