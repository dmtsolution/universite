document.addEventListener('DOMContentLoaded', () => {
    const adminForm = document.getElementById('add-adm-form');

    if (!adminForm) {
        console.error('Admin form not found');
        return;
    }

    // Form submission handler
    adminForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        console.log('Form submitted');

        const formData = new FormData(adminForm);
        const data = Object.fromEntries(formData.entries());
        console.log('Form data:', data);

        let isValid = true;
        const requiredFields = ['email_admin', 'mot_de_passe', 'nom_admin', 'prenom_admin'];

        // Clear previous errors
        adminForm.querySelectorAll('.error-message').forEach(el => el.remove());

        // Validate required fields
        requiredFields.forEach(fieldName => {
            const value = formData.get(fieldName);
            const input = adminForm.querySelector(`[name="${fieldName}"]`);

            if (!value || !value.trim()) {
                isValid = false;
                showError(input, 'Ce champ est requis');
            }
        });

        // Validate password length
        const password = formData.get('mot_de_passe');
        if (password && password.length < 6) {
            isValid = false;
            showError(adminForm.querySelector('[name="mot_de_passe"]'),
                     'Le mot de passe doit contenir au moins 6 caractères');
        }

        if (!isValid) {
            console.log('Form validation failed');
            return;
        }

        const submitBtn = adminForm.querySelector('button[type="submit"]');
        const buttonText = submitBtn.querySelector('.button-text');
        const buttonLoader = submitBtn.querySelector('.button-loader');

        buttonText.style.display = 'none';
        buttonLoader.style.display = 'inline-block';
        submitBtn.disabled = true;

        try {
            const response = await fetch('../action/action_add_admin.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(data).toString()
            });

            const result = await response.json();

            if (result.success) {
                showToast('Administrateur ajouté avec succès');
                adminForm.reset();
            } else {
                showToast(result.message || 'Erreur lors de l\'ajout de l\'administrateur', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('Erreur de connexion au serveur', 'error');
        } finally {
            buttonText.style.display = 'inline-block';
            buttonLoader.style.display = 'none';
            submitBtn.disabled = false;
        }
    });

    function showError(input, message) {
        const existingError = input.parentElement.querySelector('.error-message');
        if (existingError) existingError.remove();

        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.textContent = message;
        input.parentElement.appendChild(errorDiv);
    }

    function showToast(message, type = 'success') {
        const existingToast = document.querySelector('.toast-notification');
        if (existingToast) existingToast.remove();

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
});