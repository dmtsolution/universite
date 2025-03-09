document.addEventListener('DOMContentLoaded', () => {
    const submissionForm = document.getElementById('submission-form');
    const exerciseSelect = document.getElementById('id_exercice');

    // Load all exercises
    async function loadExercises() {
        try {
            const response = await fetch('../eleve/get_exercice.php');
            const exercises = await response.json();

            exerciseSelect.innerHTML = '<option value="">Sélectionner un exercice</option>';
            exercises.forEach(exercise => {
                exerciseSelect.innerHTML += `<option value="${exercise.id_exercice}">${exercise.titre_exercice} (${exercise.type_exercice})</option>`;
            });
        } catch (error) {
            console.error('Error loading exercises:', error);
            showToast('Erreur lors du chargement des exercices', 'error');
        }
    }

    loadExercises();

    submissionForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        if (!submissionForm.checkValidity()) {
            submissionForm.reportValidity();
            return;
        }

        const formData = new FormData(submissionForm);
        const submitBtn = submissionForm.querySelector('button[type="submit"]');
        const buttonText = submitBtn.querySelector('.button-text');
        const buttonLoader = submitBtn.querySelector('.button-loader');

        buttonText.style.display = 'none';
        buttonLoader.style.display = 'inline-block';
        submitBtn.disabled = true;

        try {
            const response = await fetch('../action/action_add_depo.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                showToast('Exercice déposé avec succès');
                submissionForm.reset();
            } else {
                showToast(result.message || 'Erreur lors du dépôt de l\'exercice', 'error');
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