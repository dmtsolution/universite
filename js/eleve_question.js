document.addEventListener('DOMContentLoaded', () => {
    const questionForm = document.getElementById('question-form').querySelector('form');
    const sessionSelect = document.getElementById('id_seance_question');

    // Load sessions list
    async function loadSessions() {
        try {
            const response = await fetch('../eleve/get_seance.php');
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

    questionForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        if (!questionForm.checkValidity()) {
            questionForm.reportValidity();
            return;
        }

        const formData = new FormData(questionForm);
        const submitBtn = questionForm.querySelector('button[type="submit"]');
        submitBtn.disabled = true;

        try {
            const response = await fetch('../action/action_add_question.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(formData).toString()
            });

            const result = await response.json();

            if (result.success) {
                showToast('Question envoyée avec succès');
                questionForm.reset();
            } else {
                showToast(result.message || 'Erreur lors de l\'envoi de la question', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('Erreur de connexion au serveur', 'error');
        } finally {
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