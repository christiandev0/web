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

$queryGetImagePath = "SELECT image_path FROM utenti WHERE id = :userId";
$stmtGetImagePath = $connection->prepare($queryGetImagePath);
$stmtGetImagePath->bindParam(':userId', $user['id'], PDO::PARAM_INT);
$stmtGetImagePath->execute();

$imagePathResult = $stmtGetImagePath->fetch(PDO::FETCH_ASSOC);

                // Verifica se la query ha restituito un risultato
if ($imagePathResult && $imagePathResult['image_path'] !== "uploads/default.png") {
        $imagePath = $imagePathResult['image_path'];
        
} else {
                    // Se la query non ha restituito un risultato, assegna un valore di default o gestisci l'errore in modo appropriato
    $imagePath = "uploads/default.png"; // Sostituisci con il percorso dell'immagine di default
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
            <?php echo '<img  src="' . $imagePath . '" alt="" class="user-img">' ?>
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
                <img src="immagini/film/film3.jpeg" alt="Movie Image">
                <form class="add-favorite-form" id="addFavoriteForm">
    <input type="hidden" name="movieId" value="3">
    <button id="addToFavoritesBtn" type="button" onclick="addToFavorites()">
        Aggiungi ai preferiti <i class='bx bxs-heart'></i>
    </button>
    <div id="notification"></div>
</form>

            </div>
            <div class="neutral-right">
                <iframe class="neutral-trailer" src="https://www.youtube.com/embed/5mKjfZHDn_M?si=6VpDZPUR6h4IXtwo"  frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                <p class="neutral-description">In “Guardiani della Galassia Vol. 3", i nostri amati disadattati si stanno stabilendo nella vita su Knowhere. Ma non passa molto tempo prima che le loro vite vengano sconvolte dagli echi del turbolento passato di Rocket.
                    Peter Quill deve radunare la sua squadra attorno a sé in una missione pericolosa per salvare la vita di Rocket, una missione che, se non completata con successo, potrebbe portare alla fine dei Guardiani come li conosciamo.</p>
                <div class="line-separator"></div>
                <p class="big-description">In “Guardiani della Galassia Vol. 3”, la nostra amata banda di disadattati ha un aspetto un po’ diverso in questi giorni. Dopo aver acquisito Knowhere dal Collezionista,
                     troviamo i Guardiani che lavorano per riparare il danno estremo causato da Thanos, determinati a rendere Knowhere un rifugio sicuro, non solo per se stessi, ma per tutti i rifugiati sfollati dal duro universo.
                      Ma non passa molto tempo prima che le loro vite vengano sconvolte dagli echi del turbolento passato di Rocket. Peter Quill, ancora scosso dalla perdita di Gamora, deve radunare la sua squadra attorno a sé in una pericolosa missione per salvare la vita di Rocket,
                     una missione che, se non completata con successo, potrebbe portare alla fine dei Guardiani come li conosciamo. Alcune sequenze o schemi di luci lampeggianti potrebbero influenzare gli spettatori fotosensibili..</p>
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/boxicons@2.1.1/js/boxicons.min.js"></script>
    <script src="favourites.js"></script>
</body>
</html>
