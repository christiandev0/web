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
        <title>Mover</title>
        <link rel="stylesheet" href="dashboard_style.css">
        <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css">
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
                <img src="immagini/dc_strange.jpeg" alt="" class="user-img">
                <div class="user-dropdown-content">
                    <a href="#">Profilo</a>
                    <a href="#">Preferiti</a>
                    <a href="logout.php?action=logout">Logout</a>
                </div>
            </div>
                <div class="navbar">
                    <a href="#Home" class="nav-link" nav-active>
                        <i class="bx bx-home"></i>
                        <span class="nav-link-title">Home</span>
                    </a>
                    <a href="#Di_tendenza" class="nav-link">
                        <i class="bx bxs-hot"></i>
                        <span class="nav-link-title">Di tendenza</span>
                    </a>
                    <a href="#Esplora" class="nav-link">
                        <i class="bx bx-compass"></i>
                        <span class="nav-link-title">Esplora</span>
                    </a>
                    <a href="#Film" class="nav-link">
                        <i class='bx bxs-movie'></i>
                        <span class="nav-link-title">Film</span>
                    </a>
                    <a href="#Preferiti" class="nav-link">
                        <i class='bx bxs-heart'></i>
                        <span class="nav-link-title">Preferiti</span>
                    </a>
                </div>
            </div>
        </header>
        <section class="home container" id="home">
    <div class="carousel">
        <div class="carousel__item">
            <img src="immagini/wk4.jpeg" alt="Guardiani della galassia">
        </div>
        <div class="carousel__item">
            <img src="immagini/thor4.jpeg" alt="Oppenheimer">
        </div>
        <div class="carousel__item">
            <img src="immagini/dc_strange.jpeg" alt="User">
        </div>
        <div class="carousel__item">
            <img src="immagini/ggvol3.jpeg" alt="User">
        </div>
        <!-- Altri elementi del carousel -->
    </div>
    <div class="home-text">
        <h1 class="home-title">Titolo film <br></h1>
        <p>Data d'uscita x</p>
        <a href="" class="watch-btn">
            <i class="bx bx-right-arrow"></i>
            <span>Guarda il trailer!</span>
        </a>
    </div>
        </section>
        <section class="popular container" id="popular">
            <div class="heading">
                <h2 class="heading-title">In tendenza </h2>
            </div>
        </section>
        <script src="carousel.js"></script>
    </body>
</html>
