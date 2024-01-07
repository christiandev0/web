<?php
// Verifica se l'utente è autenticato
session_start();
$connection = require __DIR__ . '/connessioneDB.php';

if (!isset($_COOKIE['auth_token'])) {
    // Se il cookie del token non è presente, reindirizza alla pagina di login
    header("Location: login.html");
    exit();
}

$token = $_COOKIE['auth_token'];

// Verifica il token nel database
$queryCheckToken = "SELECT id, username FROM utenti WHERE token = :token";
$stmtCheckToken = $connection->prepare($queryCheckToken);
$stmtCheckToken->bindParam(':token', $token, PDO::PARAM_STR);
$stmtCheckToken->execute();

$user = $stmtCheckToken->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    // Se il token non è valido, reindirizza alla pagina di login
    header("Location: login.html");
    exit();
}

// Se l'utente è autenticato, visualizza il contenuto della dashboard
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="login_style.css">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
</head>

<body>
    <header>
        <div class="user-info">
            <h1>Benvenuto nella Dashboard, <?php echo htmlspecialchars($user['username']); ?>!</h1>
        </div>
    </header>

    <!-- Aggiungi il contenuto della tua dashboard qui -->
    <div class="logout">
        <a href="logout.php?action=logout">Logout</a>
    </div>

    <!-- Sezione Film Valutati -->
    <section class="film-valutati-section">
        <h2>Film Valutati</h2>
        <a href="film_valutati.php">Vedi Film Valutati</a>

        <!-- Qui puoi mostrare l'elenco dei film valutati dall'utente -->
        <!-- Ad esempio, puoi fare riferimento al tuo database per recuperare le recensioni degli utenti -->
    </section>
</body>

</html>
