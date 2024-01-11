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
$userId= $user['id'];
if (!$user) {
    // Se il token non è valido, reindirizza alla pagina di login
    header("Location: login.html");
    exit();
}
require_once 'get_preferred_movies.php';

// Chiamare la funzione per ottenere i film preferiti
$preferredMovies = getPreferredMovies($user['id']);


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
    <title>Profilo Utente</title>
    <link rel="stylesheet" href="dashboard_style.css">
    <link rel="stylesheet" href="profilo.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css">
 
</head>
<body>
    <header>
        <div class="nav container">
            <a href="dashboard.php" class="logo">
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
                <a href="dashboard.php#home" class="nav-link" nav-active>
                    <i class="bx bx-home"></i>
                    <span class="nav-link-title">Home</span>
                </a>
                <a href="dashboard.php#popular" class="nav-link">
                    <i class="bx bxs-hot"></i>
                    <span class="nav-link-title">Popolari</span>
                </a>
                <a href="dashboard.php#Esplora" class="nav-link">
                    <i class="bx bx-compass"></i>
                    <span class="nav-link-title">Esplora</span>
                </a>
                <a href="dashboard.php#series" class="nav-link">
                    <i class='bx bxs-movie'></i>
                    <span class="nav-link-title">Serie TV</span>
                </a>
                <a href="#dashboard.phpPreferiti" class="nav-link">
                    <i class='bx bxs-heart'></i>
                    <span class="nav-link-title">Preferiti</span>
                </a>
            </div>
        </div>
    </header>
    <div class="container">
        <div class="profile-container">
        <?php echo '<img  src="' . $imagePath . '" alt="User Image" class="big-user-img">' ?>
            <h2><?php echo $user['username']; ?></h2>
            <div class="favorites-container">
                <!-- Mostra i film preferiti come cards -->
                <?php foreach ($preferredMovies as $movie): ?>
                    <div class="favorites-card">
                        <a href="<?php echo 'film' . $movie['id'] . '.php'; ?>">
                        <img src="<?php echo $movie['immagine']; ?>" alt="<?php echo $movie['titolo']; ?>">
                        </a>
                        <h3><?php echo $movie['titolo']; ?></h3>
                        <form class="remove-favorite-form" action="rimozione_preferiti.php" method="post">
                            <input type="hidden" name="movieId" value="<?php echo $movie['id']; ?>">
                            <button type="submit">Rimuovi dai preferiti</button>
                        </form>
                    </div>
<?php endforeach; ?>
            </div>
            <div class="profile-actions">
    <button id="editProfileButton">Modifica Profilo</button>
    <div class="edit-profile-menu">
        <a href="#" id="modifyUsernameLink"data-user-idUS="<?php echo $user['id']; ?>">Modifica Username</a>
        <a href="#" id="modifyImageLink" >Modifica Immagine</a>
        <a href="#" id="deleteAccountLink" data-user-id="<?php echo $user['id']; ?>">Elimina Account</a>
    </div>
</div>
<div id="modifyUsernameSection" style="display: none;">
    <input type="text" id="newUsername" placeholder="Nuovo Username">
</div>

    <script src="https://unpkg.com/boxicons@2.1.1/js/boxicons.min.js"></script>
    <script src="modify_profile.js"></script>
    <script src="modify_username.js" defer></script>
    <script src="delete_profile.js"></script>
    <script></script>
   
</body>
</html>
