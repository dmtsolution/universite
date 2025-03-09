document.addEventListener('DOMContentLoaded', () => {
    const sessionForm = document.getElementById('add-session-form');
    const courseSelect = document.getElementById('id_cours_seance');

    if (!sessionForm) {
        console.error('Session form not found');
        return;
    }

    // Load courses list
    async function loadCourses() {
        try {
            const response = await fetch('../enseignant/get_course.php');
            const courses = await response.json();

            courseSelect.innerHTML = '<option value="">Sélectionner le cours</option>';
            courses.forEach(course => {
                courseSelect.innerHTML += `<option value="${course.id_cours}">${course.nom_cours} (${course.nom_enseignant})</option>`;
            });
        } catch (error) {
            console.error('Error loading courses:', error);
            showToast('Erreur lors du chargement des cours', 'error');
        }
    }

    loadCourses();

    sessionForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        if (!sessionForm.checkValidity()) {
            sessionForm.reportValidity();
            return;
        }

        // Validate time range
        const startTime = document.getElementById('debut_seance').value;
        const endTime = document.getElementById('fin_seance').value;
        if (startTime >= endTime) {
            showToast('L\'heure de fin doit être postérieure à l\'heure de début', 'error');
            return;
        }

        const formData = new FormData(sessionForm);
        const submitBtn = sessionForm.querySelector('button[type="submit"]');
        const buttonText = submitBtn.querySelector('.button-text');
        const buttonLoader = submitBtn.querySelector('.button-loader');

        // Show loading state
        buttonText.style.display = 'none';
        buttonLoader.style.display = 'inline-block';
        submitBtn.disabled = true;

        try {
            const response = await fetch('../action/action_add_seance.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(formData).toString()
            });

            const result = await response.json();

            if (result.success) {
                showToast('Séance créée avec succès');
                sessionForm.reset();
            } else {
                showToast(result.message || 'Erreur lors de la création de la séance', 'error');
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