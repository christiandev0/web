<?php
// Registrazione lato server
session_start();

// Modifica le istruzioni die() per restituire un messaggio di errore JSON
if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["error" => "Inserisci un'email valida!"]);
    exit();
}

if (empty($_POST["username"])) {
    echo json_encode(["error" => "Username richiesto"]);
    exit();
}

if (strlen($_POST["password1"]) < 8) {
    echo json_encode(["error" => "La Password deve contenere almeno 8 caratteri"]);
    exit();
}

if (!preg_match("/[0-9]/", $_POST["password1"])) {
    echo json_encode(["error" =>"La password deve contenere almeno un numero."]);
    exit();
}

// Verifica che le due password siano identiche
if ($_POST["password1"] !== $_POST["password2"]) {
    echo json_encode(["error" =>"Le password non corrispondono."]);
    exit();
}

$connection = require __DIR__ . '/connessioneDB.php'; // Connessione al database

$username = $_POST["username"];
$email = $_POST["email"];

// Verifica se l'utente con lo stesso username o email è già presente nel database
$checkExistingUserQuery = "SELECT id, username, password FROM utenti WHERE username = :username OR email = :email";
$checkStmt = $connection->prepare($checkExistingUserQuery);
$checkStmt->bindParam(':username', $username, PDO::PARAM_STR);
$checkStmt->bindParam(':email', $email, PDO::PARAM_STR);
$checkStmt->execute();

$existingUser = $checkStmt->fetch(PDO::FETCH_ASSOC);

if ($existingUser) {
    // L'utente con lo stesso username o email è già presente
    echo json_encode(["error" => "Username o email già in uso.\nScegline un altro."]);
    exit();
}


// L'utente non esiste, procedi con l'inserimento
$password = password_hash($_POST["password1"], PASSWORD_DEFAULT); // Password criptata

$query = "INSERT INTO utenti (username, email, password) VALUES (:username, :email, :password)";
$stmt = $connection->prepare($query);

if (!$stmt) {
    echo json_encode(["error" => "Errore nella preparazione della query"]);
    exit();
}

// Associa i parametri
$stmt->bindParam(':username', $username, PDO::PARAM_STR);
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->bindParam(':password', $password, PDO::PARAM_STR);

if ($stmt->execute()) {
    // Ottieni l'ID appena inserito
    $user_id = $connection->lastInsertId();
    $_SESSION['user_id'] = $user_id;
    $token = bin2hex(random_bytes(32));

    // Salva il token nel database
    $queryUpdateToken = "UPDATE utenti SET token = :token WHERE id = :id";
    $stmtUpdateToken = $connection->prepare($queryUpdateToken);
    $stmtUpdateToken->bindParam(':token', $token, PDO::PARAM_STR);
    $stmtUpdateToken->bindParam(':id', $user_id, PDO::PARAM_INT);

    if ($stmtUpdateToken->execute()) {
        // Invia il token al client (ad esempio, come cookie)
        setcookie("auth_token", $token, time() + 3600, "/");
        echo json_encode(["success" => "Registrazione avvenuta con successo"]);
        exit();
    } else {
        echo json_encode(["error" => "Errore durante l'aggiornamento del token"]);
        exit();
    }
} else {
    echo json_encode(["error" => "Errore durante l'inserimento nel database"]);
    exit();
}
?>
