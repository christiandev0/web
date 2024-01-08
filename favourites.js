
    document.addEventListener('DOMContentLoaded', function () {
        // Trova tutti i bottoni dei preferiti
        var favoriteButtons = document.querySelectorAll('.favorite-button');

        // Aggiungi un gestore di eventi a ciascun bottone
        favoriteButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                var movieId = button.parentElement.getAttribute('data-movie-id');
                var currentState = button.getAttribute('data-state');

                // Cambia lo stato del bottone (vuoto o pieno)
                button.setAttribute('data-state', currentState === 'empty' ? 'full' : 'empty');

                // Cambia l'icona del cuore
                var heartIcon = button.querySelector('i');
                heartIcon.classList.toggle('far'); // Toggle classe cuore vuoto
                heartIcon.classList.toggle('fas'); // Toggle classe cuore pieno

                // Invia una richiesta al server per aggiungere ai preferiti
                addToFavorites(movieId);
            });
        });

        // Funzione per inviare una richiesta al server per aggiungere ai preferiti
        function addToFavorites(movieId) {
            // Esegui una richiesta AJAX o utilizza fetch per inviare i dati al server
            // Ad esempio, puoi usare l'API fetch per inviare una richiesta POST
            fetch('aggiungi_ai_preferiti.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    movieId: movieId,
                }),
            })
            .then(response => response.json())
            .then(data => {
                // Gestisci la risposta dal server (puoi fare qualcosa se l'aggiunta ai preferiti è riuscita o meno)
                console.log(data);
            })
            .catch(error => {
                console.error('Errore durante l\'aggiunta ai preferiti:', error);
            });
        }
    });

