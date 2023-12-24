<?php
// Registrazione lato server
session_start();
if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    die("You Need To Put a Valid Email!");
}

if (empty($_POST["username"])) {
    die("Username Requested");
}

if (strlen($_POST["password1"]) < 8) {
    die("Password needs to be a minimum of 8 characters long");
}

if (!preg_match("/[0-9]/", $_POST["password1"])) {
    die("Password needs to contain at least one number.");
}

// Verifica che le due password siano identiche
if ($_POST["password1"] !== $_POST["password2"]) {
    die("Le password non corrispondono.");
}

$connection = require __DIR__ . '/connessioneDB.php'; // Connessione al database

$username = $_POST["username"];
$email = $_POST["email"];

// Verifica se l'utente con lo stesso username o email è già presente nel database
$checkExistingUserQuery = "SELECT id, username, password FROM utente WHERE username = :username";
$checkStmt = $connection->prepare($checkExistingUserQuery);
$checkStmt->bindParam(':username', $username, PDO::PARAM_STR);
$checkStmt->execute();

$existingUser = $checkStmt->fetch(PDO::FETCH_ASSOC);

if ($existingUser) {
    // L'utente con lo stesso username è già presente
    die("Username già in uso. Scegliere un altro.");
}

// L'utente non esiste, procedi con l'inserimento
$password = password_hash($_POST["password1"], PASSWORD_DEFAULT); // Password criptata

$query = "INSERT INTO utente (username, email, password) VALUES (:username, :email, :password)";
$stmt = $connection->prepare($query);

if (!$stmt) {
    die("SQL Error" . print_r($connection->errorInfo(), true));
}

// Associa i parametri
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->bindParam(':username', $username, PDO::PARAM_STR);
$stmt->bindParam(':password', $password, PDO::PARAM_STR);

$stmt->execute();

// Ottieni l'ID appena inserito
$user_id = $connection->lastInsertId();
$_SESSION['user_id'] = $user_id;
$token = bin2hex(random_bytes(32));

// Salva il token nel database
$queryUpdateToken = "UPDATE utente SET token = :token WHERE id = :id";
$stmtUpdateToken = $connection->prepare($queryUpdateToken);
$stmtUpdateToken->bindParam(':token', $token, PDO::PARAM_STR);
$stmtUpdateToken->bindParam(':id', $user_id, PDO::PARAM_INT);
$stmtUpdateToken->execute();

// Invia il token al client (ad esempio, come cookie)
setcookie("auth_token", $token, time() + 3600, "/");
header('Location: dashboard.php');
exit();
?>
