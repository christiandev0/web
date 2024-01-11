

function editReview(reviewId, initialText) {
    // Rimuovi l'evento di click dal pulsante di modifica per evitare duplicati
    const editButton = document.querySelector(`#review-${reviewId} .edit-review-btn`);
    editButton.removeEventListener("click", editReview);

    // Crea il modulo di modifica
    const editForm = createEditReviewForm(reviewId, initialText);

    // Sostituisci solo il testo della recensione con il modulo di modifica
    const reviewTextElement = document.querySelector(`#review-${reviewId} .review-text`);
    reviewTextElement.innerHTML = ""; // Rimuovi il testo esistente
    reviewTextElement.appendChild(editForm);
}


function createEditReviewForm(reviewId, initialText) {
    const editForm = document.createElement("form");
    editForm.name = "editReviewForm";

    const textarea = document.createElement("textarea");
    textarea.className = "form-control";
    textarea.rows = "5";
    textarea.name = "editedReview";
    textarea.required = true;
    textarea.textContent = initialText;
    editForm.appendChild(textarea);

    const submitButton = document.createElement("button");
    submitButton.type = "button";
    submitButton.className = "btn btn-primary";
    submitButton.textContent = "Salva Modifiche";
    submitButton.onclick = function () {
        saveEditedReview(reviewId, textarea.value);
    };
    editForm.appendChild(submitButton);

    return editForm;
}

function saveEditedReview(reviewId, editedText) {
    fetch("editReview.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `reviewId=${encodeURIComponent(reviewId)}&editedText=${encodeURIComponent(editedText)}`,
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Aggiorna l'interfaccia utente con la recensione modificata
                updateReviewInUI(data.editedReview);
            } else {
                console.error("Errore durante la modifica della recensione.");
            }
        })
        .catch(error => {
            console.error("Errore durante la modifica della recensione:", error);
        });
}

function updateReviewInUI(editedReview) {
    // Aggiorna l'interfaccia utente con la recensione modificata
    const reviewElement = document.getElementById(`review-${editedReview.id}`);
    reviewElement.innerHTML = `<strong>Utente:</strong> ${editedReview.username}<br><span class="review-text"><strong>Recensione:</strong> ${editedReview.testo}<br><strong>Data:</strong> ${editedReview.data_creazione}</span>`;
    reviewElement.innerHTML += `<button class="edit-review-btn" onclick="editReview(${editedReview.id}, '${editedReview.testo}')">Modifica</button>`;
}
