document.addEventListener("DOMContentLoaded", function () {
    const reviewForm = document.forms["reviewForm"];
    const reviewsContainer = document.getElementById("reviewsContainer");

    reviewForm.addEventListener("submit", function (event) {
        event.preventDefault();

        const reviewTextarea = reviewForm.elements["review"];
        const reviewText = reviewTextarea.value;

        // Ottieni l'ID del film dalla URL o da dove è disponibile nella tua logica
        const filmId = document.querySelector('#addFavoriteForm [name="movieId"]').value;

        // Invia la recensione al server tramite AJAX o al tuo backend PHP
        fetch("submitReview.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ reviewText, filmId }),
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message); // Mostra un messaggio di successo o errore
            if (data.success) {
                // Aggiorna la pagina o carica nuovamente le recensioni
                loadReviews(filmId);
            }
        })
        .catch(error => {
            console.error("Errore durante l'invio della recensione:", error);
        });

        // Pulisci il campo della recensione
        reviewTextarea.value = "";
    });

    // Funzione per caricare le recensioni al caricamento della pagina
    function loadReviews(filmId) {
        fetch(`getReviews.php?filmId=${filmId}`)
        .then(response => response.json())
        .then(data => {
            // Aggiorna la sezione delle recensioni nel tuo HTML
            if (data.reviews) {
                reviewsContainer.innerHTML = "<h3>Recensioni:</h3><ul>";
                data.reviews.forEach(review => {
                    reviewsContainer.innerHTML += `<li><strong>Utente:</strong> ${review.username}<br><strong>Recensione:</strong> ${review.testo}<br><strong>Data:</strong> ${review.data_creazione}</li>`;
                });
                reviewsContainer.innerHTML += "</ul>";
            }
        })
        .catch(error => {
            console.error("Errore durante il recupero delle recensioni:", error);
        });
    }

    // Carica le recensioni al caricamento della pagina
    const filmId = 1; // Sostituisci con la tua logica per ottenere l'ID del film
    loadReviews(filmId);
});

