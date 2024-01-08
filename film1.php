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
    <title>Black Panther Wakanda Forever</title>
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
                <img src="immagini/dc_strange.jpeg" alt="" class="user-img">
                <div class="user-dropdown-content">
                    <a href="#">Profilo</a>
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
                <img src="immagini/wk4.jpeg" alt="Movie Image">
                <button id="addToFavoritesBtn">
        <i class='bx bxs-heart'></i> Aggiungi ai Preferiti
    </button>
            </div>
            <div class="neutral-right">
                <iframe class="neutral-trailer" src="https://www.youtube.com/embed/Tr0jSdADqjw?si=eDhuE_EfsdVCbcQc"  frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                <p class="neutral-description">La regina Ramonda, Shuri, M'Baku, Okoye e Dora Milaje combattono per proteggere la loro nazione dall'intervento delle potenze mondiali in seguito alla morte di re T'Challa. Mentre i Wakandan si sforzano di abbracciare il loro prossimo capitolo,
                     gli eroi devono unirsi con l'aiuto di War Dog Nakia e Everett Ross e creare un nuovo percorso per il regno di Wakanda.</p>
                <div class="line-separator"></div>
                <p class="big-description">In "Black Panther: Wakanda Forever" dei Marvel Studios, la regina Ramonda (Angela Bassett), Shuri (Letitia Wright),
                     M'Baku (Winston Duke), Okoye (Danai Gurira) e Dora Milaje (inclusa Florence Kasumba) combattono per proteggere la loro nazione dalle potenze mondiali che intervengono in seguito alla morte di re T'Challa.
                      Mentre i Wakandan si sforzano di abbracciare il loro prossimo capitolo, gli eroi devono unirsi con l’aiuto di War Dog Nakia (Lupita Nyong’o) ed Everett Ross (Martin Freeman) e creare un nuovo percorso per il regno di Wakanda.
                     Presentando Tenoch Huerta nei panni di Namor, re di una nazione sottomarina nascosta, il film vede protagonisti anche Dominique Thorne, Michaela Coel, Mabel Cadena e Alex Livinalli.</p>
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/boxicons@2.1.1/js/boxicons.min.js"></script>
    <script src="aggiungi_ai_preferiti.js"></script>
</body>
</html>
