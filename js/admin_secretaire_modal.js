document.addEventListener('DOMContentLoaded', () => {
    // Form handling
    const secretaryForm = document.getElementById('add-secretary-form');
    const secretarySection = document.getElementById('secretary-form');

    // Section visibility handling
    const menuItems = document.querySelectorAll('[data-target]');
    menuItems.forEach(item => {
        item.addEventListener('click', () => {
            const target = item.getAttribute('data-target');
            const section = document.getElementById(target);

            // Hide all sections first
            document.querySelectorAll('.content-section').forEach(s => {
                s.style.display = 'none';
            });

            // Show target section
            if (section) {
                section.style.display = 'block';
            }
        });
    });

    function showToast(message, type = 'success') {
        const existingToast = document.querySelector('.toast-notification');
        if (existingToast) {
            existingToast.remove();
        }

        const toast = document.createElement('div');
        toast.className = `toast-notification ${type}`;
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => toast.classList.add('show'), 10);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    function showError(input, message) {
        const existingError = input.parentElement.querySelector('.error-message');
        if (existingError) existingError.remove();

        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.textContent = message;
        input.parentElement.appendChild(errorDiv);
    }

    if (secretaryForm) {
        secretaryForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(secretaryForm);
            const data = Object.fromEntries(formData.entries());
            let isValid = true;

            for (let [key, value] of formData.entries()) {
                if (!value.trim()) {
                    isValid = false;
                    const input = secretaryForm.querySelector(`[name="${key}"]`);
                    showError(input, 'Ce champ est requis');
                }
            }

            if (!isValid) return;

            const submitBtn = secretaryForm.querySelector('button[type="submit"]');
            const buttonText = submitBtn.querySelector('.button-text');
            const buttonLoader = submitBtn.querySelector('.button-loader');
            buttonText.style.display = 'none';
            buttonLoader.style.display = 'inline-block';
            submitBtn.disabled = true;

            try {
                const response = await fetch('../action/action_add_secretaire.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams(data).toString()
                });

                const result = await response.json();

                if (result.success) {
                    showToast('Secrétaire ajouté(e) avec succès');
                    secretaryForm.reset();
                } else {
                    showToast(result.message || 'Erreur lors de l\'ajout du/de la secrétaire', 'error');
                }
            } catch (error) {
                showToast('Erreur de connexion au serveur', 'error');
                console.error('Error:', error);
            } finally {
                buttonText.style.display = 'inline-block';
                buttonLoader.style.display = 'none';
                submitBtn.disabled = false;
            }
        });
    }
});