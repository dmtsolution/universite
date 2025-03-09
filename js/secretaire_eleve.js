document.addEventListener('DOMContentLoaded', () => {
    const studentForm = document.getElementById('add-student-form');
    const groupSelect = document.getElementById('groupe_select');

    if (!studentForm) {
        console.error('Student form not found');
        return;
    }

    // Fetch and populate groups
    async function loadGroups() {
        try {
            const response = await fetch('../secretaire/get_groupe.php');
            const groups = await response.json();

            groupSelect.innerHTML = '<option value="">Sélectionner un groupe</option>';
            groups.forEach(group => {
                groupSelect.innerHTML += `<option value="${group.id_groupe}">${group.nom_groupe} - ${group.specialite_groupe}</option>`;
            });
        } catch (error) {
            console.error('Error loading groups:', error);
            showToast('Erreur lors du chargement des groupes', 'error');
        }
    }

    loadGroups();

    studentForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        console.log('Form submitted'); // Debug log

        if (!studentForm.checkValidity()) {
            studentForm.reportValidity();
            return;
        }

        const formData = new FormData(studentForm);
        const submitBtn = studentForm.querySelector('button[type="submit"]');
        const buttonText = submitBtn.querySelector('.button-text');
        const buttonLoader = submitBtn.querySelector('.button-loader');

        buttonText.style.display = 'none';
        buttonLoader.style.display = 'inline-block';
        submitBtn.disabled = true;

        try {
            const response = await fetch('../action/action_add_eleve.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(formData).toString()
            });

            const result = await response.json();

            if (result.success) {
                showToast('Élève ajouté avec succès');
                studentForm.reset();
                loadGroups();
            } else {
                showToast(result.message || 'Erreur lors de l\'ajout de l\'élève', 'error');
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