function addToFavorites() {
    var movieId = document.querySelector('#addFavoriteForm [name="movieId"]').value;

    // Esegui una richiesta asincrona per verificare e aggiungere il film ai preferiti
    fetch('api.php/preferiti', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ movieId: movieId }),
    })
    .then(response => {
        var notification = document.getElementById('notification');
        if (response.ok) {
            // Operazioni dopo la verifica e l'aggiunta ai preferiti
            return response.text();
        } else {
            return Promise.reject('Errore durante la richiesta: ' + response.statusText);
        }
    })
    .then(data => {
        notification.textContent = data;
        notification.style.color = 'green';
    })
    .catch(error => {
        notification.textContent = error;
        notification.style.color = 'red';
    });
}
