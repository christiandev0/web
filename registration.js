document.addEventListener('DOMContentLoaded', function() {
    const nomeInput = document.querySelector('#registrationForm input[placeholder="Nome"]');
    const cognomeInput = document.querySelector('#registrationForm input[placeholder="Cognome"]');
    const usernameInput = document.querySelector('#registrationForm input[placeholder="Username"]');
    const passwordInput = document.querySelector('#registrationForm input[placeholder="Password"]');

    const checkNome = document.getElementById('checkNome');
    const checkCognome = document.getElementById('checkCognome');
    const checkUsername = document.getElementById('checkUsername');
    const checkPassword = document.getElementById('checkPassword');

    nomeInput.addEventListener('input', function() {
        checkNome.checked = nomeInput.value.length > 0;
    });

    cognomeInput.addEventListener('input', function() {
        checkCognome.checked = cognomeInput.value.length > 0;
    });

    usernameInput.addEventListener('input', function() {
        checkUsername.checked = usernameInput.value.length > 0;
    });

    passwordInput.addEventListener('input', function() {
        checkPassword.checked = passwordInput.value.length > 0;
    });
});