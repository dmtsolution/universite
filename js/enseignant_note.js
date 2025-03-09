document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('grading-form');
    const submissionSelect = document.getElementById('submission-select');
    const submissionDetails = document.getElementById('submission-details');
    let submissions = {};

    async function loadSubmissions() {
        try {
            const response = await fetch('../enseignant/get_depo_exercice.php');
            const data = await response.json();

            if (!data.success) {
                throw new Error(data.message);
            }

            submissionSelect.innerHTML = '<option value="">Sélectionner un exercice</option>';
            data.submissions.forEach(sub => {
                submissions[sub.id_depo] = sub;
                submissionSelect.innerHTML += `
                    <option value="${sub.id_depo}">
                        ${sub.nom_eleve} ${sub.prenom_eleve} - ${sub.titre_exercice} (${sub.type_exercice})
                    </option>`;
            });
        } catch (error) {
            console.error('Error:', error);
            showToast('Erreur lors du chargement des exercices', 'error');
        }
    }

    submissionSelect.addEventListener('change', () => {
        const submissionId = submissionSelect.value;
        if (!submissionId) {
            submissionDetails.style.display = 'none';
            return;
        }

        const submission = submissions[submissionId];
        const submissionInfo = document.querySelector('.submission-info');
        submissionInfo.innerHTML = `
            <div class="submission-card">
                <h3 class="submission-title">Détails de l'exercice</h3>
                <div class="submission-content">
                    <p class="info-row"><strong>Élève:</strong> ${submission.nom_eleve} ${submission.prenom_eleve}</p>
                    <p class="info-row"><strong>Exercice:</strong> ${submission.titre_exercice}</p>
                    <p class="info-row"><strong>Type:</strong> ${submission.type_exercice}</p>
                    <p class="info-row"><strong>Date de dépôt:</strong> ${new Date(submission.date_depo).toLocaleString()}</p>
                    <p class="info-row">
                        <strong>Fichier:</strong>
                        <a href="../fichier_depo/${submission.fichier_depo}" target="_blank" class="file-link">
                            Voir le fichier
                        </a>
                    </p>
                    ${submission.commentaire_depo ? `
                        <p class="info-row comment">
                            <strong>Commentaire:</strong>
                            <span class="comment-text">${submission.commentaire_depo}</span>
                        </p>
                    ` : ''}
                </div>
            </div>
        `;
        submissionDetails.style.display = 'block';
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const submissionId = submissionSelect.value;
        const points = document.getElementById('points').value;

        const submitBtn = form.querySelector('button[type="submit"]');
        const buttonText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enregistrement...';

        try {
            const response = await fetch('../action/action_add_note.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    id_depo: submissionId,
                    id_eleve: submissions[submissionId].id_eleve,
                    points: points
                })
            });

            const result = await response.json();
            if (result.success) {
                showToast('Note enregistrée avec succès');
                form.reset();
                submissionDetails.style.display = 'none';
                loadSubmissions(); // Reload list
            } else {
                throw new Error(result.message);
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('Erreur lors de l\'enregistrement de la note', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = buttonText;
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

    // Load submissions on page load
    loadSubmissions();
});