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

// Verifica se l'ID del film è stato inviato
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["movieId"])) {
    $movieId = $_POST["movieId"];

    // Verifica se il film è già tra i preferiti dell'utente
    $checkFavoriteQuery = "SELECT COUNT(*) FROM preferiti WHERE id_utente = :userId AND id_film = :movieId";
    $stmtCheckFavorite = $connection->prepare($checkFavoriteQuery);
    $stmtCheckFavorite->bindParam(':userId', $user['id'], PDO::PARAM_INT);
    $stmtCheckFavorite->bindParam(':movieId', $movieId, PDO::PARAM_INT);
    $stmtCheckFavorite->execute();

    $isFavorite = (bool)$stmtCheckFavorite->fetchColumn();

    if ($isFavorite) {
        echo "Il film è già tra i tuoi preferiti";
    } else {
        // Esegui la query per aggiungere il film ai preferiti
        $addFavoriteQuery = "INSERT INTO preferiti (id_utente, id_film) VALUES (:userId, :movieId)";
        $stmtAddFavorite = $connection->prepare($addFavoriteQuery);
        $stmtAddFavorite->bindParam(':userId', $user['id'], PDO::PARAM_INT);
        $stmtAddFavorite->bindParam(':movieId', $movieId, PDO::PARAM_INT);

        // Esegui la query di inserimento e gestisci il risultato
        try {
            $stmtAddFavorite->execute();
            echo "Film aggiunto ai preferiti con successo";
        } catch (PDOException $e) {
            echo "Errore durante l'aggiunta del film ai preferiti: " . $e->getMessage();
        }
    }
}
?>
