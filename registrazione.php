<?php
// Registrazione lato server

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
$checkExistingUserQuery = "SELECT * FROM utente WHERE username = :username OR email = :email LIMIT 1";
$checkStmt = $connection->prepare($checkExistingUserQuery);

$checkStmt->bindParam(':username', $username, PDO::PARAM_STR);
$checkStmt->bindParam(':email', $email, PDO::PARAM_STR);

$checkStmt->execute();
$existingUser = $checkStmt->fetch(PDO::FETCH_ASSOC);

if ($existingUser) {
    // L'utente con lo stesso username o email è già presente
    die("Username o Email già in uso. Scegliere un altro.");
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
$stmt->closeCursor();

echo "Registrazione avvenuta con successo!";
?>
