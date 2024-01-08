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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thor Love and Thunder</title>
    <link rel="stylesheet" href="dashboard_style.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css">
    <link rel="stylesheet" href="film_style.css">
</head>
<body>
    <header>
        <div class="nav container">
            <a href="index.html" class="logo">
                Mover
            </a>
            <div class="search-box">
                <input type="search" name="" id="search-input" placeholder="Cerca film">
                <i class="bx bx-search"></i>
            </div>
            <div class="user" id="userDropdown">
                <img src="uploads/userImage.jpg" alt="" class="user-img">
                <div class="user-dropdown-content">
                    <a href="profilo.php">Profilo</a>
                    <a href="#">Preferiti</a>
                    <a href="logout.php?action=logout">Logout</a>
                </div>
            </div>
            <div class="navbar">
                <a href="dashboard.php#home" class="nav-link" nav-active> <!-- Aggiunto "#home" -->
                    <i class="bx bx-home"></i>
                    <span class="nav-link-title">Home</span>
                </a>
                <a href="dashboard.php#popular" class="nav-link"> <!-- Aggiunto "#popular" -->
                    <i class="bx bxs-hot"></i>
                    <span class="nav-link-title">Popolari</span>
                </a>
                <a href="dashboard.php#Esplora" class="nav-link"> <!-- Aggiunto "#Esplora" -->
                    <i class="bx bx-compass"></i>
                    <span class="nav-link-title">Esplora</span>
                </a>
                <a href="dashboard.php#series" class="nav-link"> <!-- Aggiunto "#series" -->
                    <i class='bx bxs-movie'></i>
                    <span class="nav-link-title">Serie TV</span>
                </a>
                <a href="dashboard.php#Preferiti" class="nav-link"> <!-- Aggiunto "#Preferiti" -->
                    <i class='bx bxs-heart'></i>
                    <span class="nav-link-title">Preferiti</span>
                </a>
            </div>
        </div>
    </header>
    <div class="container">
        <div class="neutral-page">
            <div class="neutral-left">
                <img src="immagini/thor4.jpeg" alt="Movie Image">
                <button id="addToFavoritesBtn">
        <i class='bx bxs-heart'></i> Aggiungi ai Preferiti
    </button>
            </div>
            <div class="neutral-right">
                <iframe class="neutral-trailer" src="https://www.youtube.com/embed/5mKjfZHDn_M?si=5uixr7TAhqT5lq77" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                <p class="neutral-description">In "Thor: Love and Thunder" dei Marvel Studios, il Dio del Tuono si allea con Re Valchiria, Korg e la sua ex fidanzata Jane Foster, trasformata in Possente Thor, per affrontare un assassino galattico noto come Gorr il Macellatore di Dei..</p>
                <div class="line-separator"></div>
                <p class="big-description">"Thor: Love and Thunder", prodotto dai Marvel Studios,
                 presenta il Dio del Tuono in un viaggio alla scoperta di sé stesso. Ma i suoi sforzi vengono ostacolati da un assassino galattico noto come Gorr il Macellatore di Dei,
                  che persegue l'estinzione degli Dei. Per combattere la minaccia, Thor ottiene l'aiuto di Re Valchiria, di Korg e della sua ex fidanzata Jane Foster che, con grande sorpresa di Thor,
                   impugna inspiegabilmente il suo martello magico, Mjolnir, come il Possente Thor. Insieme, intraprendono una tormentata avventura cosmica per risalire al mistero della vendetta del Macellatore di Dei e fermarlo prima che sia troppo tardi.
                    Alcune scene con luci lampeggianti o motivi grafici potrebbero danneggiare gli spettatori fotosensibili.</p>
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/boxicons@2.1.1/js/boxicons.min.js"></script>
</body>
</html>
