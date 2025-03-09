document.addEventListener('DOMContentLoaded', () => {
    const groupForm = document.querySelector('#group-form form');

    if (!groupForm) {
        console.error('Group form not found');
        return;
    }

    groupForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        // Use native form validation
        if (!groupForm.checkValidity()) {
            groupForm.reportValidity();
            return;
        }

        const formData = new FormData(groupForm);
        const submitBtn = groupForm.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.textContent;

        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Création...';

        try {
            const response = await fetch('../action/action_add_groupe.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(formData).toString()
            });

            const result = await response.json();

            if (result.success) {
                showToast('Groupe créé avec succès');
                groupForm.reset();
            } else {
                showToast(result.message || 'Erreur lors de la création du groupe', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('Erreur de connexion au serveur', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = originalBtnText;
        }
    });

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