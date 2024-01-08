<?php
session_start();
$connection = require __DIR__ . '/connessioneDB.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $filmId = $_GET['film_id'];
    $userId = $_SESSION['user_id']; // Assicurati di avere l'ID dell'utente disponibile in sessione

    // Esempio di query SQL per rimuovere il film dai preferiti
    $query = "DELETE FROM preferiti WHERE id_utente = :id_utente AND id_film = :id_film";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':id_utente', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':id_film', $filmId, PDO::PARAM_INT);
    $stmt->execute();

    // Puoi aggiungere ulteriori controlli e gestire eventuali errori

    echo "Film rimosso dai preferiti con successo";
}
?>
