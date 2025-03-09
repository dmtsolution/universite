document.addEventListener('DOMContentLoaded', () => {
    const exerciseForm = document.getElementById('exercise-seance-form');
    const sessionSelect = document.getElementById('id_seance_exercice');

    // Load sessions list
    async function loadSessions() {
        try {
            const response = await fetch('../enseignant/get_seance.php');
            const sessions = await response.json();

            sessionSelect.innerHTML = '<option value="">Sélectionner une séance</option>';
            sessions.forEach(session => {
                sessionSelect.innerHTML += `<option value="${session.id_seance}">${session.titre_seance} (${session.date_seance})</option>`;
            });
        } catch (error) {
            console.error('Error loading sessions:', error);
            showToast('Erreur lors du chargement des séances', 'error');
        }
    }

    loadSessions();

    exerciseForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        if (!exerciseForm.checkValidity()) {
            exerciseForm.reportValidity();
            return;
        }

        const formData = new FormData(exerciseForm);
        const submitBtn = exerciseForm.querySelector('button[type="submit"]');
        const buttonText = submitBtn.querySelector('.button-text');
        const buttonLoader = submitBtn.querySelector('.button-loader');

        // Show loading state
        buttonText.style.display = 'none';
        buttonLoader.style.display = 'inline-block';
        submitBtn.disabled = true;

        try {
            const response = await fetch('../action/action_add_exercice.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                showToast('Exercice créé avec succès');
                exerciseForm.reset();
            } else {
                showToast(result.message || 'Erreur lors de la création de l\'exercice', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('Erreur de connexion au serveur', 'error');
        } finally {
            // Reset loading state
            buttonText.style.display = 'inline-block';
            buttonLoader.style.display = 'none';
            submitBtn.disabled = false;
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