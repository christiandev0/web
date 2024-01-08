<?php
session_start();
$connection = require __DIR__ . '/connessioneDB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $movieId = $_POST['filmId'];
    $userId = $_SESSION['user']['id']; // Assicurati di avere l'ID dell'utente autenticato

    // Esegui la query per aggiungere ai preferiti
    $queryAddToFavorites = "INSERT INTO preferiti (user_id, movie_id) VALUES (:userId, :movieId)";
    $stmtAddToFavorites = $connection->prepare($queryAddToFavorites);
    $stmtAddToFavorites->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmtAddToFavorites->bindParam(':movieId', $movieId, PDO::PARAM_INT);
    $stmtAddToFavorites->execute();

    // Puoi inviare una risposta JSON al client se necessario
    echo json_encode(['success' => true]);
}
?>
