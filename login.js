document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');

    loginForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Evita l'invio del modulo predefinito

        // Esegui la validazione dei campi del modulo
        if (validateLoginForm()) {
            // Se la validazione è riuscita, invia il modulo al server
            loginForm.submit();
        }
    });

    function validateLoginForm() {
        var username = document.querySelector('#loginForm input[type="text"]').value;
        var password = document.querySelector('#loginForm input[type="password"]').value;

        // Esegui la tua logica di validazione
        if (username === "" || password === "") {
            errorMessage:
            return false;
        } else {
            // Puoi eseguire altre verifiche qui, se necessario
            // Ad esempio, verifica la lunghezza della password, il formato dell'email, ecc.
            return true;
        }
    }
});
