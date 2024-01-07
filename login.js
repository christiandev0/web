document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("loginForm").addEventListener("submit", function (e) {
        e.preventDefault();

        fetch("login.php", {
            method: "POST",
            body: new URLSearchParams(new FormData(this)),
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
            } else if (data.success) {
                window.location.href = "dashboard.php";
            }
        })
        .catch(error => {
            console.error("Errore durante la richiesta al server", error);
            alert("Errore durante la richiesta al server");
        });
    });
});