document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("registrationForm").addEventListener("submit", function (e) {
        e.preventDefault();

        var formData = new FormData(this);
        var xhr = new XMLHttpRequest();

        xhr.open("POST", "registrazione.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);

                    if (response.error) {
                        // Visualizza gli errori
                        document.getElementById("error-message").innerHTML = response.error;
                        document.getElementById("error-message").style.display = "block";
                    } else if (response.success) {
                        // Reindirizza l'utente alla pagina di dashboard o fai altre azioni
                        window.location.href = "dashboard.php";
                    }
                } else {
                    console.error("Errore durante la richiesta al server");
                    alert("Errore durante la richiesta al server");
                }
            }
        };

        xhr.send(new URLSearchParams(formData).toString());
    });
});
