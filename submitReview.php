<?php
// submitReview.php
session_start();
// Verifica se la richiesta è di tipo POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Ottieni il testo della recensione e l'ID del film inviati
    $data = json_decode(file_get_contents("php://input"), true);
    $reviewText = $data["reviewText"];
    $filmId = $data["filmId"]; // Assicurati che l'ID del film sia passato correttamente

    // Connessione al database
    $connection = require __DIR__ . '/connessioneDB.php';

    // Ottieni l'ID dell'utente corrente (dovresti già averlo autenticato)
    // In questo esempio, useremo un valore di esempio (sostituiscilo con la tua logica di autenticazione)
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    // Controlla se l'utente ha già lasciato una recensione per questo film
    $queryCheckReview = "SELECT id FROM recensioni WHERE id_utente = :userId AND id_film = :filmId";
    $stmtCheckReview = $connection->prepare($queryCheckReview);
    $stmtCheckReview->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmtCheckReview->bindParam(':filmId', $filmId, PDO::PARAM_INT);
    $stmtCheckReview->execute();

    $existingReview = $stmtCheckReview->fetch(PDO::FETCH_ASSOC);

    if ($existingReview) {
        // L'utente ha già lasciato una recensione per questo film
        $response = ["success" => false, "message" => "Hai già lasciato una recensione per questo film."];
    } else {
        // Esegui la query per inserire la recensione nel database
        $queryInsertReview = "INSERT INTO recensioni (id_utente, id_film, testo) VALUES (:userId, :filmId, :reviewText)";
        $stmtInsertReview = $connection->prepare($queryInsertReview);
        $stmtInsertReview->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmtInsertReview->bindParam(':filmId', $filmId, PDO::PARAM_INT);
        $stmtInsertReview->bindParam(':reviewText', $reviewText, PDO::PARAM_STR);

        try {
            $stmtInsertReview->execute();
            $response = ["success" => true, "message" => "Recensione inviata con successo!"];
        } catch (PDOException $e) {
            $response = ["success" => false, "message" => "Errore durante l'inserimento della recensione."];
        }
    }
} else {
    // La richiesta non è di tipo POST
    $response = ["success" => false, "message" => "Metodo di richiesta non valido."];
}

// Restituisci la risposta JSON al client
header("Content-Type: application/json");
echo json_encode($response);
?>
