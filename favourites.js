function addToFavorites() {
    var movieId = document.querySelector('#addFavoriteForm [name="movieId"]').value;

    // Esegui una richiesta asincrona per verificare e aggiungere il film ai preferiti
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'addpreferiti.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        var notification = document.getElementById('notification');
        if (xhr.status === 200) {
            // Operazioni dopo la verifica e l'aggiunta ai preferiti
            notification.textContent = xhr.responseText;
            notification.style.color = 'green';
        } else {
            notification.textContent = xhr.responseText;
            notification.style.color = 'red';
        }
    };

    // Invia la richiesta con l'ID del film
    xhr.send('movieId=' + encodeURIComponent(movieId));
}
