<?php
// Connessione al database
session_start();
$connection = require __DIR__ . '/connessioneDB.php';

// Autenticazione lato server
if (empty($_POST["username"]) || empty($_POST["password"])) {
    die("Username e password richiesti.");
}

$username = $_POST["username"];
$password = $_POST["password"];

$query = "SELECT id, username, password FROM utente WHERE username = :username";
$stmt = $connection->prepare($query);
$stmt->bindParam(':username', $username, PDO::PARAM_STR);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($password, $user['password'])) {
    die("Credenziali non valide.");
}
$_SESSION['user_id'] = $user['id'];

// Genera un token di autenticazione
$token = bin2hex(random_bytes(32));

// Salva il token nel database (puoi creare una colonna 'token' nella tabella utente)
$queryUpdateToken = "UPDATE utente SET token = :token WHERE id = :id";
$stmtUpdateToken = $connection->prepare($queryUpdateToken);
$stmtUpdateToken->bindParam(':token', $token, PDO::PARAM_STR);
$stmtUpdateToken->bindParam(':id', $user['id'], PDO::PARAM_INT);
$stmtUpdateToken->execute();

// Invia il token al client (ad esempio, come cookie)
setcookie("auth_token", $token, time() + 3600, "/");

// Reindirizza alla pagina di successo o dashboard
header("Location: dashboard.php");
exit();
?>
