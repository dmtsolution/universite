document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('exam-grading-form');
    const studentSelect = document.getElementById('student-select');
    const courseSelect = document.getElementById('course-select');
    let students = {};
    let courses = {};

    // Charger les étudiants
    async function loadStudents() {
        try {
            const response = await fetch('../enseignant/get_eleve.php');
            const data = await response.json();

            if (!data.success) {
                throw new Error(data.message);
            }

            studentSelect.innerHTML = '<option value="">Sélectionner un élève</option>';
            data.students.forEach(student => {
                students[student.id_eleve] = student;
                studentSelect.innerHTML += `
                    <option value="${student.id_eleve}">
                        ${student.nom_eleve} ${student.prenom_eleve}
                    </option>`;
            });
        } catch (error) {
            console.error('Erreur:', error);
            showToast('Erreur lors du chargement des élèves', 'error');
        }
    }

    // Charger les cours
    async function loadCourses() {
        try {
            const response = await fetch('../enseignant/get_course.php');
            const data = await response.json();

            courseSelect.innerHTML = '<option value="">Sélectionner un cours</option>';
            data.forEach(course => {
                courses[course.id_cours] = course;
                courseSelect.innerHTML += `
                    <option value="${course.id_cours}">
                        ${course.nom_cours} - ${course.nom_enseignant}
                    </option>`;
            });
        } catch (error) {
            console.error('Erreur:', error);
            showToast('Erreur lors du chargement des cours', 'error');
        }
    }

    // Gérer la soumission du formulaire
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const studentId = studentSelect.value;
        const courseId = courseSelect.value;
        const points = document.getElementById('exam-points').value;
        const explanation = document.getElementById('explanation').value;

        const submitBtn = form.querySelector('button[type="submit"]');
        const buttonText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enregistrement...';

        try {
            const response = await fetch('../action/action_add_exam_note.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    id_eleve: studentId,
                    id_cours: courseId,
                    points: points,
                    explication: explanation
                })
            });
        
            const result = await response.json();
            if (result.success) {
                showToast('Note enregistrée avec succès');
                form.reset();
            } else {
                // Vérifier si le message contient "Note déjà ajoutée"
                if (result.message === 'Note déjà ajoutée pour cet élève et ce cours') {
                    showToast('Note déjà ajoutée', 'error');
                } else {
                    throw new Error(result.message);
                }
            }
        } catch (error) {
            console.error('Erreur:', error);
            showToast('Erreur lors de l\'enregistrement de la note', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = buttonText;
        }
        
    });

    // Afficher un message de notification
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

    // Charger les données au chargement de la page
    loadStudents();
    loadCourses();
});
