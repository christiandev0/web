<?php
session_start();
$connection = require __DIR__ . '/connessioneDB.php';

// Assicurati che l'utente sia autenticato
if (!isset($_COOKIE['auth_token'])) {
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
    header("Location: login.html");
    exit();
}

// Assicurati che venga inviato l'id del film da rimuovere
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["movieId"])) {
    $movieId = $_POST["movieId"];

    // Esegui la query per rimuovere il film dai preferiti
    $removeFavoriteQuery = "DELETE FROM preferiti WHERE id_film = :movieId AND id_utente = :userId";
    $stmtRemoveFavorite = $connection->prepare($removeFavoriteQuery);
    $stmtRemoveFavorite->bindParam(':movieId', $movieId, PDO::PARAM_INT);
    $stmtRemoveFavorite->bindParam(':userId', $user['id'], PDO::PARAM_INT);

    // Esegui la query di eliminazione e gestisci il risultato
    if ($stmtRemoveFavorite->execute()) {
        echo "Film rimosso con successo";
    } else {
        echo "Errore durante la rimozione del film";
    }

    // Puoi aggiungere ulteriori controlli o reindirizzamenti qui, se necessario
    // Ad esempio, potresti reindirizzare l'utente dopo la rimozione
    header("Location: profilo.php");
    exit();
}
?>
