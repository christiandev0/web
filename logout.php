<?php
// logout.php
session_start();
$connection = require __DIR__ . '/connessioneDB.php';

// Controlla se l'azione di logout è stata richiesta
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    // Assicurati di avere l'id dell'utente dalla sessione
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    
    if ($userId) {
        // Elimina il token dal database
        $deleteTokenQuery = "UPDATE utenti SET token = NULL WHERE id = :id";
        $deleteTokenStmt = $connection->prepare($deleteTokenQuery);
        $deleteTokenStmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $deleteTokenStmt->execute();

        // Cancella il cookie di autenticazione
        setcookie('auth_token', '', time() - 3600, '/'); // Tempo negativo per eliminare il cookie

        // Reindirizza alla pagina di login
        session_destroy();
        header('Location: index.html');
        exit();
    }
}

?>