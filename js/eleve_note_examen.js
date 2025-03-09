document.addEventListener('DOMContentLoaded', () => {
    const gradesExamBody = document.getElementById('grades-exam-body');

    // Fonction pour afficher un toast de notification
    function showToast(message, success = true) {
        const toast = document.createElement('div');
        toast.classList.add('toast');
        toast.classList.add(success ? 'toast-success' : 'toast-error');
        toast.textContent = message;
        document.body.appendChild(toast);

        // Supprimer le toast après 3 secondes
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }

    async function loadExamGrades() {
        try {
            const response = await fetch('../eleve/get_note_examen.php'); // Changer selon ton fichier pour les examens
            const data = await response.json();

            if (!data.success) {
                showToast('Erreur lors du chargement des notes d\'examen', false);
                return;
            }

            // Affichage des notes d'examen dans le tableau
            if (data.grades.length === 0) {
                gradesExamBody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center">Aucune note d'examen disponible</td>
                    </tr>`;
            } else {
                gradesExamBody.innerHTML = data.grades.map(grade => `
                    <tr>
                        <td>${grade.nom_cours}</td>
                        <td>Examen</td>
                        <td>${Number(grade.points).toFixed(2)}/20</td>
                        <td>
                            <i class="fas fa-eye" onclick="toggleExplication(this)"></i>
                            <p class="explication-text" style="display: none;">
                                ${grade.explication || 'Aucune explication'}
                            </p>
                        </td>
                        <td>${new Date(grade.date_note).toLocaleDateString('fr-FR')}</td>
                    </tr>
                `).join('');
            }

        } catch (error) {
            showToast('Erreur lors du chargement des notes d\'examen', false);
        }
    }

    // Fonction pour afficher/masquer l'explication
    window.toggleExplication = function(icon) {
        const explicationText = icon.nextElementSibling;
        const isVisible = explicationText.style.display === 'block';

        // Toggle l'affichage de l'explication
        if (isVisible) {
            explicationText.style.display = 'none';
        } else {
            explicationText.style.display = 'block';
        }
    };

    loadExamGrades();
});
