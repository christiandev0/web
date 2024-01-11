document.addEventListener("DOMContentLoaded", function () {
    const modifyUsernameLink = document.getElementById("modifyUsernameLink");

    if (modifyUsernameLink) {
        modifyUsernameLink.addEventListener("click", function (event) {
            event.preventDefault();

            const userId = modifyUsernameLink.getAttribute("data-user-idUS");
            const newUsername = prompt("Inserisci il nuovo username:");

            if (userId && newUsername !== null) {
                console.log(userId)
                // Invia la richiesta di modifica dello username
                fetch(`api.php/utenti/${userId}`, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({ id: userId, username: newUsername }),
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if (data.status === 'success') {
                        // Aggiorna la pagina o esegui altre azioni necessarie
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Errore durante la richiesta di modifica dello username:', error);
                    console.log('Risposta del server:', error.responseText);
                });
            } else {
                console.error('Impossibile ottenere l\'ID dell\'utente o nuovo username non fornito.');
            }
        });
    }
});
