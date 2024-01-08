<?php
session_start();
$connection = require __DIR__ . '/connessioneDB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica l'autenticazione dell'utente
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401); // Unauthorized
        exit();
    }

    $userId = $_SESSION['user_id'];
    $filmName = $_POST['film_name']; // Assumendo che il nome del film venga inviato dal client

    // Recupera l'ID del film dal database
    $queryGetFilmId = "SELECT id FROM film WHERE titolo = :film_name";
    $stmtGetFilmId = $connection->prepare($queryGetFilmId);
    $stmtGetFilmId->bindParam(':film_name', $filmName, PDO::PARAM_STR);
    $stmtGetFilmId->execute();

    $filmId = $stmtGetFilmId->fetchColumn();

    if (!$filmId) {
        http_response_code(404); // Not Found
        exit();
    }

    // Inserisci il record nella tabella Preferiti
    $queryAddToFavorites = "INSERT INTO preferiti (user_id, film_id) VALUES (:user_id, :film_id)";
    $stmtAddToFavorites = $connection->prepare($queryAddToFavorites);
    $stmtAddToFavorites->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmtAddToFavorites->bindParam(':film_id', $filmId, PDO::PARAM_INT);
    $stmtAddToFavorites->execute();

    http_response_code(200); // OK
} else {
    http_response_code(405); // Method Not Allowed
}
?>
