<?php
// getReviews.php

// Connessione al database
$connection = require __DIR__ . '/connessioneDB.php';

// Ottieni l'ID del film dalla richiesta GET
$filmId = isset($_GET['filmId']) ? intval($_GET['filmId']) : null;

if ($filmId) {
    // Esegui la query per ottenere tutte le recensioni associate al film specifico
    $queryGetReviews = "SELECT r.*, u.username FROM recensioni r
                        INNER JOIN utenti u ON r.id_utente = u.id
                        WHERE r.id_film = :filmId";
    $stmtGetReviews = $connection->prepare($queryGetReviews);
    $stmtGetReviews->bindParam(':filmId', $filmId, PDO::PARAM_INT);
    $stmtGetReviews->execute();

    $reviews = $stmtGetReviews->fetchAll(PDO::FETCH_ASSOC);

    // Restituisci le recensioni come JSON al client
    header("Content-Type: application/json");
    echo json_encode(["reviews" => $reviews]);
} else {
    // L'ID del film non è valido o non è stato fornito
    // Puoi gestire l'errore in modo appropriato
    header("Content-Type: application/json");
    echo json_encode(["error" => "ID del film non valido."]);
}
?>
