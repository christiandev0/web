<?php
// get_preferred_movies.php

// Assicurati di includere la connessione al database
$connection = require __DIR__ . '/connessioneDB.php';

function getPreferredMovies($userId) {
    global $connection;

    $queryPreferredMovies = "SELECT film.id, film.titolo, film.immagine FROM preferiti
                            INNER JOIN film ON preferiti.id_film = film.id
                            WHERE preferiti.id_utente = :userId";
    $stmtPreferredMovies = $connection->prepare($queryPreferredMovies);
    $stmtPreferredMovies->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmtPreferredMovies->execute();

    return $stmtPreferredMovies->fetchAll(PDO::FETCH_ASSOC);
}
?>
