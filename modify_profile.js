document.addEventListener("DOMContentLoaded", function () {
    var editProfileButton = document.querySelector("#editProfileButton");
    var editProfileMenu = document.querySelector(".edit-profile-menu");
    var modifyImageLink = document.querySelector("#modifyImageLink");

    editProfileButton.addEventListener("click", function (event) {
        // Impedisci che l'evento si propaghi e raggiunga il listener del documento
        event.stopPropagation();

        // Mostra/nascondi il menu al clic
        editProfileMenu.classList.toggle("show");

        // Mostra il link "Modifica Immagine" solo quando si apre il menu "Modifica Profilo"
        modifyImageLink.style.display = editProfileMenu.classList.contains("show") ? "block" : "none";
    });

    // Nascondi il link quando la pagina è caricata
    modifyImageLink.style.display = "none";

    // Gestisci il click sul link "Modifica Immagine"
    modifyImageLink.addEventListener("click", function (event) {
        // Impedisci che l'evento si propaghi e raggiunga il listener del documento
        event.stopPropagation();

        // Crea un nuovo oggetto FormData per l'invio del file
        var formData = new FormData();

        // Crea un input file e simula il click per far selezionare un file all'utente
        var fileInput = document.createElement("input");
        fileInput.type = "file";
        fileInput.accept = "image/*";
        fileInput.style.display = "none";
        fileInput.addEventListener("change", function () {
            // Aggiungi il file selezionato all'oggetto FormData
            formData.append("fileToUpload", fileInput.files[0]);

            // Chiama la funzione di upload tramite AJAX
            uploadImage(formData);

            // Rimuovi l'input file dopo l'upload
            document.body.removeChild(fileInput);
        });

        document.body.appendChild(fileInput);

        // Simula il click sul nuovo input file
        fileInput.click();
    });

    // Funzione per l'upload dell'immagine tramite AJAX
    function uploadImage(formData) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "upload_image.php", true);

        // Aggiungi un listener per gestire la risposta del server
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    // Puoi gestire la risposta del server qui, se necessario
                    console.log(xhr.responseText);
                    alert("Immagine caricata con successo!");
                    location.reload();
                } else {
                    // Se ci sono errori, puoi gestirli qui
                    console.error("Errore durante l'upload dell'immagine.");
                }
            }
        };

        // Invia la richiesta al server con l'oggetto FormData contenente il file
        xhr.send(formData);
    }
});



