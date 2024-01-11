

session_start();
$connection = require __DIR__ . '/connessioneDB.php';
<?php
// Verifica se l'utente è autenticato
if (!isset($_COOKIE['auth_token'])) {
    http_response_code(403); // Codice di stato "Forbidden"
    echo json_encode(["error" => "Utente non autenticato."]);
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
    http_response_code(403); // Codice di stato "Forbidden"
    echo json_encode(["error" => "Token non valido."]);
    exit();
}

// Ottieni l'ID dell'utente autenticato
$userId = $user['id'];

// Ottieni l'ID della recensione da modificare dalla richiesta POST
$reviewId = isset($_POST['reviewId']) ? intval($_POST['reviewId']) : null;

if (!$reviewId) {
    http_response_code(400); // Codice di stato "Bad Request"
    echo json_encode(["error" => "ID della recensione non valido."]);
    exit();
}

// Esegui la query per ottenere la recensione specifica
$queryGetReview = "SELECT * FROM recensioni WHERE id = :reviewId AND id_utente = :userId";
$stmtGetReview = $connection->prepare($queryGetReview);
$stmtGetReview->bindParam(':reviewId', $reviewId, PDO::PARAM_INT);
$stmtGetReview->bindParam(':userId', $userId, PDO::PARAM_INT);
$stmtGetReview->execute();

$review = $stmtGetReview->fetch(PDO::FETCH_ASSOC);

if (!$review) {
    http_response_code(403); // Codice di stato "Forbidden"
    echo json_encode(["error" => "Utente non autorizzato a modificare questa recensione."]);
    exit();
}

// Ottieni il nuovo testo della recensione dalla richiesta POST
$editedText = isset($_POST['editedText']) ? trim($_POST['editedText']) : null;

if (!$editedText) {
    http_response_code(400); // Codice di stato "Bad Request"
    echo json_encode(["error" => "Il campo di modifica è vuoto."]);
    exit();
}

// Esegui la query per aggiornare il testo della recensione
$queryUpdateReview = "UPDATE recensioni SET testo = :editedText WHERE id = :reviewId";
$stmtUpdateReview = $connection->prepare($queryUpdateReview);
$stmtUpdateReview->bindParam(':editedText', $editedText, PDO::PARAM_STR);
$stmtUpdateReview->bindParam(':reviewId', $reviewId, PDO::PARAM_INT);
$stmtUpdateReview->execute();

// Restituisci la recensione aggiornata come JSON al client
header("Content-Type: application/json");
echo json_encode(["success" => true, "editedReview" => ["id" => $reviewId, "testo" => $editedText]]);
?>