document.addEventListener("DOMContentLoaded", function () {
    const deleteAccountLink = document.getElementById("deleteAccountLink");

    if (deleteAccountLink) {
        deleteAccountLink.addEventListener("click", function (event) {
            event.preventDefault();

            const userId = deleteAccountLink.getAttribute("data-user-id");

            if (userId) {
                const confirmDelete = confirm("Sei sicuro di voler eliminare il tuo account? Questa azione non può essere annullata.");

                if (confirmDelete) {
                    fetch(`api.php/utenti/${userId}`, {
                        method: "DELETE",
                        headers: {
                            "Content-Type": "application/json",
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        if (data.status === 'success') {
                            window.location.href = 'logout.php?action=logout';
                        }
                    })
                    .catch(error => {
                        console.error('Errore durante la richiesta di eliminazione:', error);
                    });
                }
            } else {
                console.error('Impossibile ottenere l\'ID dell\'utente.');
            }
        });
    }
});
