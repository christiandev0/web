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
$imagePath = isset($_SESSION['image_path']) ? $_SESSION['image_path'] : "uploads/default.png";
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
        <script src="https://kit.fontawesome.com/186eb98a62.js" crossorigin="anonymous"></script>
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
                <?php echo '<img  src="' . $imagePath . '" alt="" class="user-img">' ?>
                    <div class="user-dropdown-content">
                        <a href="profilo.php">Profilo</a>
                        <a href="#">Preferiti</a>
                        <a href="logout.php?action=logout">Logout</a>
                    </div>
                </div>
                <div class="navbar">
                    <a href="#Home" class="nav-link" nav-active>
                        <i class="bx bx-home"></i>
                        <span class="nav-link-title">Home</span>
                    </a>
                    <a href="#popular" class="nav-link">
                        <i class="bx bxs-hot"></i>
                        <span class="nav-link-title">Popolari</span>
                    </a>
                    <a href="#Esplora" class="nav-link">
                        <i class="bx bx-compass"></i>
                        <span class="nav-link-title">Esplora</span>
                    </a>
                    <a href="#series" class="nav-link">
                        <i class='bx bxs-movie'></i>
                        <span class="nav-link-title">Serie TV</span>
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
                    <a href="film1.php">
                        <img src="immagini/wk4.jpeg" alt="Black Panther wakanda forever">
                    </a>
                </div>
                <div class="carousel__item">
                <a href="film2.php">
                    <img src="immagini/thor4.jpeg" alt="Thor4">
                    </a>
                </div>
                <div class="carousel__item">
                <a href="film4.php">
                    <img src="immagini/dc_strange.jpeg" alt="Doctor strange">
                    </a>
                </div>
                <div class="carousel__item">
                <a href="film3.php">
                    <img src="immagini/ggvol3.jpeg" alt="Guardiani della galassia">
                    </a>
                </div>
                <!-- Altri elementi del carousel -->
            </div>
            <div class="home-text">
                <h1 class="home-title">Popolari <br></h1>
            </div>
        </section>
        <section class="popular container" id="popular">
            <div class="heading">
                <h2 class="heading-title">Film Popolari</h2>
            </div>
            <!-- Movie cards per i Film Popolari -->
            <div class="popular-movie-cards">
                <div class="movie-card">
                    <a href="film1.php">
                        <img src="immagini/wk4.jpeg" alt="Black Panther Wakanda Forever">
                        <h3 class="movie-card__title">Black Panther Wakanda Forever</h3>
                    </a>
                </div>
                <div class="movie-card">
                    <a href="film2.php">
                        <img src="immagini/thor4.jpeg" alt="Thor 4">
                        <h3 class="movie-card__title">Thor Love and Thunder</h3>
                    </a>
                </div>
                <div class="movie-card">
                    <a href="film3.php">
                        <img src="immagini/ggvol3.jpeg" alt="Guardiani della Galassia vol3">
                        <h3 class="movie-card__title">Guardiani della Galassia Vol.3</h3>
                    </a>
                </div>
                <div class="movie-card">
                    <a href="film4.php">
                        <img src="immagini/dc_strange.jpeg" alt="doc strange">
                        <h3 class="movie-card__title">Doctor Strange in the Multiverse of Madness</h3>
                    </a>
                </div>
                <div class="movie-card">
                    <a href="film5.php">
                        <img src="immagini/oppenheimer.jpg" alt="Oppenheimer">
                        <h3 class="movie-card__title">Oppenheimer</h3>
                    </a>
                </div>
                <div class="movie-card">
                    <a href="film6.php">
                        <img src="immagini/john-wick-4.jpg" alt="John-wick-4">
                        <h3 class="movie-card__title">John Wick 4</h3>
                    </a>
                </div>
            </div>
        </section>
        <section class="series container" id="series">
            <div class="heading">
                <h2 class="heading-title">Serie TV</h2>
            </div>
            <!-- Serie TV cards -->
            <div class="series-cards">
                <div class="series-card">
                    <a href="serie1.php">
                        <img src="immagini/serietv/loki.jpg" alt="Loki">
                        <h3 class="series-card__title">Loki</h3>
                    </a>
                </div>
                <div class="series-card">
                    <a href="serie2.php">
                        <img src="immagini/serietv/tlofus.jpg" alt="The last of us">
                        <h3 class="series-card__title">The last of us</h3>
                    </a>
                </div>
                <div class="series-card">
                    <a href="serie3.php">
                        <img src="immagini/serietv/moonknight.jpg" alt="Moonknight">
                        <h3 class="series-card__title">Moonknight</h3>
                    </a>
                </div>
                <div class="series-card">
                    <a href="serie4.php">
                        <img src="immagini/serietv/got.jpg" alt="Games Of Thrones">
                        <h3 class="series-card__title">Games Of Thrones</h3>
                    </a>
                </div>
                <div class="series-card">
                    <a href="serie5.php">
                        <img src="immagini/serietv/st-things.jpg" alt="Stranger Things">
                        <h3 class="series-card__title">Stranger Things</h3>
                    </a>
                </div>
                <!-- Aggiungi altre cards per le Serie TV come necessario -->
            </div>
        </section>
        <script src="carousel.js"></script>
    </body>
</html>
