document.addEventListener('DOMContentLoaded', () => {
    const gradesBody = document.getElementById('grades-body');

    async function loadGrades() {
        try {
            const response = await fetch('../eleve/get_eleve_note.php');
            const data = await response.json();

            if (!data.success) {
                throw new Error(data.message);
            }

            gradesBody.innerHTML = data.grades.map(grade => `
                <tr>
                    <td>${grade.nom_cours}</td>
                    <td>${grade.type_exercice}</td>
                    <td>${Number(grade.points).toFixed(2)}/20</td>
                    <td>${new Date(grade.date_note).toLocaleDateString('fr-FR')}</td>
                </tr>
            `).join('');

        } catch (error) {
            console.error('Error:', error);
            gradesBody.innerHTML = `
                <tr>
                    <td colspan="4" class="text-center">Erreur lors du chargement des notes</td>
                </tr>`;
        }
    }

    loadGrades();
});