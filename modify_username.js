document.addEventListener("DOMContentLoaded", function() {
    var editProfileButton = document.getElementById("editProfileButton");
    var editProfileMenu = document.querySelector(".edit-profile-menu");
    var modifyUsernameSection = document.getElementById("modifyUsernameSection"); // Aggiunto l'id qui
    var modifyUsernameLink = document.getElementById("modifyUsernameLink");

    modifyUsernameLink.addEventListener("click", function(event) {
        event.preventDefault();
        editProfileMenu.classList.remove("show");
        modifyUsernameSection.style.display = "block";
    });
});

function modifyUsername() {
    var newUsername = document.getElementById("newUsername").value;

    fetch('modify_username.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'newUsername=' + encodeURIComponent(newUsername),
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);

        if (data.success) {
            alert("Username modificato con successo!");
            location.reload();
            // Nascondi la sezione dopo il successo
            document.getElementById("modifyUsernameSection").style.display = "none";
        } else {
            alert("Errore durante la modifica dell'username: " + data.message);
        }
    })
    .catch(error => {
        console.error('Si è verificato un errore durante la richiesta AJAX:', error);
    });
}

