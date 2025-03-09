document.addEventListener("DOMContentLoaded", () => {
    const questionsContainer = document.getElementById("questions-container");
    const loggedInTeacherId = 1; // Remplacez par l'ID du professeur connecté (dynamique en PHP)

    // Fonction pour charger les questions de la séance
    async function loadQuestions() {
        try {
            const response = await fetch(`../enseignant/question_seance.php?teacherId=${loggedInTeacherId}`); // Passer l'ID du professeur
            const data = await response.json();

            if (!data.success) {
                questionsContainer.innerHTML = "<p class='error-message'>Erreur lors du chargement des questions.</p>";
                return;
            }

            if (data.questions.length === 0) {
                questionsContainer.innerHTML = "<p class='text-center'>Aucune question posée pour cette séance.</p>";
                return;
            }

            // Générer l'affichage des questions
            questionsContainer.innerHTML = data.questions.map(q => `
                <div class="question-box">
                    <h3 style="color: red;">Séance : ${q.nom_seance}</h3> <!-- Affichage du titre de la séance en rouge -->
                    <p style="color: red;">Date de la séance : ${new Date(q.date_seance).toLocaleDateString('fr-FR')}</p> <!-- Affichage de la date de la séance en rouge -->
                    <p><strong>Élève:</strong> ${q.nom_eleve} ${q.prenom_eleve} - 
                        <em>${new Date(q.date_question).toLocaleString('fr-FR')}</em>
                    </p>
                    <p class="question-text">${q.question}</p>

                    <div class="response-container" id="response-container-${q.id_question}">
                        ${q.reponse ? `  
                            <div class="teacher-response">
                                <p><strong>Professeur:</strong> ${q.nom_professeur ? q.nom_professeur : 'Inconnu'} - 
                                    <em>${new Date(q.date_reponse).toLocaleString('fr-FR')}</em>
                                </p>
                                <p class="response-text">${q.reponse}</p>
                            </div>
                        ` : ""}
                    </div>

                    ${q.is_teacher ? `
                        <button class="reply-btn short-btn" onclick="toggleReplyForm(this, ${q.id_question})">
                            <i class="fas fa-reply"></i> Répondre
                        </button>
                    ` : ""}
                    
                    <div class="reply-form" id="reply-form-${q.id_question}" style="display: none;">
                        <textarea id="reply-text-${q.id_question}" placeholder="Écrire une réponse..."></textarea>
                        <button class="send-reply-btn" onclick="sendReply(${q.id_question})">Envoyer</button>
                    </div>
                </div>
            `).join("");
        } catch (error) {
            questionsContainer.innerHTML = "<p class='error-message'>Une erreur est survenue.</p>";
        }
    }

    // Fonction pour afficher/masquer le formulaire de réponse
    window.toggleReplyForm = (button, questionId) => {
        const form = document.getElementById(`reply-form-${questionId}`);
        form.style.display = form.style.display === "block" ? "none" : "block";
    };

    // Fonction pour envoyer une réponse
    window.sendReply = async (questionId) => {
        const replyText = document.getElementById(`reply-text-${questionId}`).value.trim();
        if (replyText === "") {
            alert("Veuillez écrire une réponse.");
            return;
        }

        try {
            const response = await fetch("../action/action_add_question_reponse.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `id_question=${questionId}&reponse=${encodeURIComponent(replyText)}`
            });

            const result = await response.json();
            if (result.success) {
                document.getElementById(`response-container-${questionId}`).innerHTML = ` 
                    <div class="teacher-response">
                        <p><strong>Professeur:</strong> Vous - 
                            <em>${new Date().toLocaleString('fr-FR')}</em>
                        </p>
                        <p class="response-text">${replyText}</p>
                    </div>
                `;
                document.getElementById(`reply-form-${questionId}`).style.display = "none";
            } else {
                alert("Erreur lors de l'envoi de la réponse.");
            }
        } catch (error) {
            alert("Une erreur est survenue.");
        }
    };

    loadQuestions();
});
