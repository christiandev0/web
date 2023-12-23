<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = filter_input(INPUT_POST, "username");
    $password = filter_input(INPUT_POST, "password");

    // Esegui ulteriori controlli di validazione lato server
    if (empty($username) || empty($password)) {
        echo "Username e password sono obbligatori.";
    } else {
        // Esegui ulteriori verifiche o elabora il login
        // Ad esempio, verifica l'autenticità delle credenziali e restituisci una risposta adeguata

        // Simuliamo una verifica di autenticità
        $hashedPasswordFromDatabase = password_hash("password123", PASSWORD_DEFAULT);

        if (password_verify($password, $hashedPasswordFromDatabase)) {
            // Login riuscito
            $_SESSION["username"] = $username; // Salva il nome utente in una variabile di sessione

            echo "Login riuscito. Redirecting...";
            // Puoi reindirizzare l'utente alla dashboard o a un'altra pagina
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Credenziali non valide.";
        }
    }
}
?>
