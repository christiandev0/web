<?php
session_start();
$connection = require __DIR__ . '/connessioneDB.php';

if (!isset($_COOKIE['auth_token'])) {
    // Se il cookie del token non è presente, reindirizza alla pagina di login
    header("Location: login.html");
    exit();
}

$token = $_COOKIE['auth_token'];

// Verifica il token nel database
$queryCheckToken = "SELECT id FROM utenti WHERE token = :token";
$stmtCheckToken = $connection->prepare($queryCheckToken);
$stmtCheckToken->bindParam(':token', $token, PDO::PARAM_STR);
$stmtCheckToken->execute();

$user = $stmtCheckToken->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    // Se il token non è valido, reindirizza alla pagina di login
    header("Location: login.html");
    exit();
}

// Controlla se è stata effettuata una richiesta di eliminazione del profilo
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["confirmDelete"])) {
    // Eseguire qui la logica per eliminare il profilo (cancellazione dal database, eliminazione dei file, ecc.)
    
    // Esempio: cancella l'utente dal database
    $deleteUserQuery = "DELETE FROM utenti WHERE id = :userId";
    $stmtDeleteUser = $connection->prepare($deleteUserQuery);
    $stmtDeleteUser->bindParam(':userId', $user['id'], PDO::PARAM_INT);
    
    try {
        $stmtDeleteUser->execute();
        
        // Distruggi la sessione e reindirizza alla pagina di login
        session_destroy();
        setcookie("auth_token", "", time() - 3600, "/");
        header("Location: login.html");
        exit();
    } catch (PDOException $e) {
        // Gestisci eventuali errori durante l'eliminazione dell'utente
        echo "Errore durante l'eliminazione dell'utente: " . $e->getMessage();
        exit();
    }
}
?>