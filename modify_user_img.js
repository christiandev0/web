document.addEventListener("DOMContentLoaded", function () {
    const updateImageLink = document.getElementById("updateImageLink");

    if (updateImageLink) {
        updateImageLink.addEventListener("click", function (event) {
            event.preventDefault();

            const userId = updateImageLink.getAttribute("data-user-idImg");
            console.log(userId)
            const fileInput = document.createElement("input");
            fileInput.type = "file";
            fileInput.accept = "image/*";
            fileInput.addEventListener("change", function () {
                const file = fileInput.files[0];
                if (file) {
                    const formData = new FormData();
                    formData.append("image", file);

                    // Utilizza il formato JSON per trasmettere l'ID dell'utente
                    const jsonData = { utente_id: userId };
                    formData.append("json_data", JSON.stringify(jsonData));

                    // Invia la richiesta di upload dell'immagine come POST
                    fetch(`api.php/utenti/${userId}/image_path`, {
                        method: "POST",
                        body: formData,
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
                        console.error('Errore durante la richiesta di upload dell\'immagine:', error);
                        console.log('Risposta del server:', error.responseText);
                    });
                }
            });

            fileInput.click();
        });
    }
});
