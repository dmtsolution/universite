document.addEventListener('DOMContentLoaded', () => {
    const courseForm = document.getElementById('course-form').querySelector('form');
    const teacherSelect = document.getElementById('id_enseignant');

    // Load teachers list
    async function loadTeachers() {
        try {
            const response = await fetch('../secretaire/get_teacher.php');
            const teachers = await response.json();

            teacherSelect.innerHTML = '<option value="">Sélectionner l\'enseignant</option>';
            teachers.forEach(teacher => {
                teacherSelect.innerHTML += `<option value="${teacher.id_enseignant}">${teacher.nom_enseignant} ${teacher.prenom_enseignant} - ${teacher.specialite}</option>`;
            });
        } catch (error) {
            console.error('Error loading teachers:', error);
            showToast('Erreur lors du chargement des enseignants', 'error');
        }
    }

    loadTeachers();

    courseForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        if (!courseForm.checkValidity()) {
            courseForm.reportValidity();
            return;
        }

        const formData = new FormData(courseForm);
        const submitBtn = courseForm.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.textContent;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Création...';

        try {
            const response = await fetch('../action/action_add_cours.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(formData).toString()
            });

            const result = await response.json();

            if (result.success) {
                showToast('Cours créé avec succès');
                courseForm.reset();
            } else {
                showToast(result.message || 'Erreur lors de la création du cours', 'error');
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