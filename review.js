document.addEventListener("DOMContentLoaded", function () {
    const reviewForm = document.forms["reviewForm"];
    const reviewsContainer = document.getElementById("reviewsContainer");
    const id_film = document.querySelector('#addFavoriteForm [name="movieId"]').value;

    reviewForm.addEventListener("submit", function (event) {
        event.preventDefault();
        
        // Estrai tutti i campi dal form
        const id_utente = reviewForm.elements["id_utente"].value;
        const reviewTextarea = reviewForm.elements["review"];
        const testo = reviewTextarea.value;
        const data_creazione = new Date().toISOString(); // Puoi modificare questa parte secondo le tue esigenze

        fetch("api.php/recensioni", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ id_utente, id_film, testo, data_creazione }),
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.status === 'success') {
                loadReviews(id_film);
            }
        })
        .catch(error => {
            console.error("Errore durante l'invio della recensione:", error);
        });

        reviewTextarea.value = "";
    });

    function loadReviews(movieId) {
        fetch(`api.php/recensioni?movieId=${movieId}`, {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
            },
        })
        .then(response => response.json())
        .then(data => {
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
});
