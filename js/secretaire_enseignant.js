document.addEventListener('DOMContentLoaded', () => {
    const teacherForm = document.getElementById('add-teacher-form');

    if (!teacherForm) {
        console.error('Teacher form not found');
        return;
    }

    teacherForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        if (!teacherForm.checkValidity()) {
            teacherForm.reportValidity();
            return;
        }

        const formData = new FormData(teacherForm);
        const submitBtn = teacherForm.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.textContent;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Ajout en cours...';

        try {
            const response = await fetch('../action/action_add_enseignant.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(formData).toString()
            });

            const result = await response.json();

            if (result.success) {
                showToast('Enseignant ajouté avec succès');
                teacherForm.reset();
            } else {
                showToast(result.message || 'Erreur lors de l\'ajout de l\'enseignant', 'error');
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