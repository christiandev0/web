<?php
session_start();
$connection = require __DIR__ . '/connessioneDB.php';

if (!isset($_COOKIE['auth_token'])) {
    header("Location: login.html");
    exit();
}

$token = $_COOKIE['auth_token'];

$queryCheckToken = "SELECT id, username FROM utenti WHERE token = :token";
$stmtCheckToken = $connection->prepare($queryCheckToken);
$stmtCheckToken->bindParam(':token', $token, PDO::PARAM_STR);
$stmtCheckToken->execute();

$user = $stmtCheckToken->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header("Location: login.html");
    exit();
}

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["newUsername"])) {
    $newUsername = $_POST["newUsername"];

    // Verifica se un altro utente ha già lo stesso username
    $queryCheckUsername = "SELECT id FROM utenti WHERE username = :newUsername AND id != :userId";
    $stmtCheckUsername = $connection->prepare($queryCheckUsername);
    $stmtCheckUsername->bindParam(':newUsername', $newUsername, PDO::PARAM_STR);
    $stmtCheckUsername->bindParam(':userId', $user['id'], PDO::PARAM_INT);
    $stmtCheckUsername->execute();

    $existingUser = $stmtCheckUsername->fetch(PDO::FETCH_ASSOC);

    if ($existingUser) {
        $response['success'] = false;
        $response['message'] = "Lo username è già in uso da un altro utente.";
    } else {
        // Effettua la validazione dell'username se necessario

        $updateUsernameQuery = "UPDATE utenti SET username = :newUsername WHERE id = :userId";
        $stmtUpdateUsername = $connection->prepare($updateUsernameQuery);
        $stmtUpdateUsername->bindParam(':newUsername', $newUsername, PDO::PARAM_STR);
        $stmtUpdateUsername->bindParam(':userId', $user['id'], PDO::PARAM_INT);

        try {
            $stmtUpdateUsername->execute();
            $response['success'] = true;
            $response['message'] = "Username modificato con successo!";
        } catch (PDOException $e) {
            $response['success'] = false;
            $response['message'] = "Errore durante la modifica dell'username: " . $e->getMessage();
        }
    }
} else {
    $response['success'] = false;
    $response['message'] = "Parametri mancanti nella richiesta";
}

header('Content-Type: application/json');
echo json_encode($response);
?>
