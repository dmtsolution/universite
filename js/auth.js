document.addEventListener('DOMContentLoaded', function() {
    // Login form handling
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const buttonText = loginForm.querySelector('.button-text');
            const buttonLoader = loginForm.querySelector('.button-loader');
            const submitButton = loginForm.querySelector('button');

            // Show loading state
            buttonText.style.display = 'none';
            buttonLoader.style.display = 'inline-block';
            submitButton.disabled = true;

            try {
                const formData = new FormData(loginForm);
                const response = await fetch('../action/action_login.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    showError(data.message);
                }
            } catch (error) {
                showError('Erreur de connexion au serveur');
            } finally {
                // Reset button state
                buttonText.style.display = 'inline-block';
                buttonLoader.style.display = 'none';
                submitButton.disabled = false;
            }
        });

        function showError(message) {
            const existingError = document.querySelector('.error-message');
            if (existingError) existingError.remove();

            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            errorDiv.textContent = message;
            loginForm.appendChild(errorDiv);
        }
    }

    // Password reset functionality
    const forgotPasswordLink = document.getElementById('forgot-password-link');
    const backToLoginLink = document.getElementById('back-to-login-link');
    const loginFormElement = document.getElementById('login-form');
    const resetPasswordForm = document.getElementById('reset-password-form');

    // Email step elements
    const emailStep = document.getElementById('email-step');
    const sendCodeBtn = document.getElementById('send-code-btn');

    // Code verification step elements
    const codeStep = document.getElementById('code-step');
    const verifyCodeBtn = document.getElementById('verify-code-btn');

    // New password step elements
    const newPasswordStep = document.getElementById('new-password-step');
    const resetPasswordBtn = document.getElementById('reset-password-btn');

    // Toggle between login and reset password forms
    if (forgotPasswordLink) {
        forgotPasswordLink.addEventListener('click', function(e) {
            e.preventDefault();
            loginFormElement.style.display = 'none';
            resetPasswordForm.style.display = 'block';
            emailStep.style.display = 'block';
            codeStep.style.display = 'none';
            newPasswordStep.style.display = 'none';
        });
    }

    if (backToLoginLink) {
        backToLoginLink.addEventListener('click', function(e) {
            e.preventDefault();
            resetPasswordForm.style.display = 'none';
            loginFormElement.style.display = 'block';
        });
    }

    // Send verification code
    if (sendCodeBtn) {
        sendCodeBtn.addEventListener('click', function() {
            const email = document.getElementById('reset-email').value;
            if (!email) {
                showToast('Veuillez entrer votre email', 'error');
                return;
            }

            const buttonText = this.querySelector('.button-text');
            const buttonLoader = this.querySelector('.button-loader');

            // Show loader
            buttonText.style.display = 'none';
            buttonLoader.style.display = 'inline-block';

            // Send request to generate and send verification code
            fetch('password/send_reset_code.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ email: email })
            })
            .then(response => response.json())
            .then(data => {
                // Hide loader
                buttonText.style.display = 'inline-block';
                buttonLoader.style.display = 'none';

                if (data.success) {
                    emailStep.style.display = 'none';
                    codeStep.style.display = 'block';
                    showToast('Un code de vérification a été envoyé à votre email', 'success');
                } else {
                    showToast(data.message || 'Une erreur est survenue', 'error');
                }
            })
            .catch(error => {
                // Hide loader
                buttonText.style.display = 'inline-block';
                buttonLoader.style.display = 'none';

                showToast('Une erreur est survenue lors de l\'envoi du code', 'error');
                console.error('Error:', error);
            });
        });
    }

    // Verify code
    if (verifyCodeBtn) {
        verifyCodeBtn.addEventListener('click', function() {
            const email = document.getElementById('reset-email').value;
            const code = document.getElementById('verification-code').value;

            if (!code) {
                showToast('Veuillez entrer le code de vérification', 'error');
                return;
            }

            const buttonText = this.querySelector('.button-text');
            const buttonLoader = this.querySelector('.button-loader');

            // Show loader
            buttonText.style.display = 'none';
            buttonLoader.style.display = 'inline-block';

            // Verify the code
            fetch('password/verify_reset_code.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    email: email,
                    code: code
                })
            })
            .then(response => response.json())
            .then(data => {
                // Hide loader
                buttonText.style.display = 'inline-block';
                buttonLoader.style.display = 'none';

                if (data.success) {
                    codeStep.style.display = 'none';
                    newPasswordStep.style.display = 'block';
                    showToast('Code vérifié avec succès', 'success');
                } else {
                    showToast(data.message || 'Code invalide', 'error');
                }
            })
            .catch(error => {
                // Hide loader
                buttonText.style.display = 'inline-block';
                buttonLoader.style.display = 'none';

                showToast('Une erreur est survenue lors de la vérification du code', 'error');
                console.error('Error:', error);
            });
        });
    }

    // Reset password
    if (resetPasswordBtn) {
        resetPasswordBtn.addEventListener('click', function() {
            const email = document.getElementById('reset-email').value;
            const code = document.getElementById('verification-code').value;
            const newPassword = document.getElementById('new-password').value;
            const confirmPassword = document.getElementById('confirm-password').value;

            if (!newPassword || !confirmPassword) {
                showToast('Veuillez remplir tous les champs', 'error');
                return;
            }

            if (newPassword.length < 6) {
                showToast('Le mot de passe doit contenir au moins 6 caractères', 'error');
                return;
            }

            if (newPassword !== confirmPassword) {
                showToast('Les mots de passe ne correspondent pas', 'error');
                return;
            }

            const buttonText = this.querySelector('.button-text');
            const buttonLoader = this.querySelector('.button-loader');

            // Show loader
            buttonText.style.display = 'none';
            buttonLoader.style.display = 'inline-block';

            // Reset the password
            fetch('password/reset_password.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    email: email,
                    code: code,
                    password: newPassword
                })
            })
            .then(response => response.json())
            .then(data => {
                // Hide loader
                buttonText.style.display = 'inline-block';
                buttonLoader.style.display = 'none';

                if (data.success) {
                    resetPasswordForm.style.display = 'none';
                    loginFormElement.style.display = 'block';
                    showToast('Mot de passe réinitialisé avec succès', 'success');
                } else {
                    showToast(data.message || 'Une erreur est survenue', 'error');
                }
            })
            .catch(error => {
                // Hide loader
                buttonText.style.display = 'inline-block';
                buttonLoader.style.display = 'none';

                showToast('Une erreur est survenue lors de la réinitialisation du mot de passe', 'error');
                console.error('Error:', error);
            });
        });
    }

    // Toast message function
    function showToast(message, type = 'info') {
        const toast = document.getElementById('toast-message');
        toast.textContent = message;
        toast.className = 'toast-message';
        toast.classList.add(`toast-${type}`);
        toast.style.display = 'block';

        setTimeout(() => {
            toast.style.display = 'none';
        }, 5000);
    }
});