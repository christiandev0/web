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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["fileToUpload"])) {
    $target_dir = "uploads/";
    $originalFileName = basename($_FILES["fileToUpload"]["name"]);
    $target_file = $target_dir . $originalFileName;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Verifica se il file è un'immagine effettiva o un falso positivo
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Verifica se il file esiste già
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Verifica la dimensione massima del file
    if ($_FILES["fileToUpload"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Accetta solo determinati formati di file
    $allowedFormats = array("jpg", "jpeg", "png", "gif");
    if (!in_array($imageFileType, $allowedFormats)) {
        // Se il formato non è consentito, converte l'immagine in formato JPG
        $newFileName = "userImage.jpg";
        $newFilePath = $target_dir . $newFileName;

        $originalImage = imagecreatefrompng($_FILES["fileToUpload"]["tmp_name"]);
        $newImage = imagecreatetruecolor(imagesx($originalImage), imagesy($originalImage));
        $white = imagecolorallocate($newImage, 255, 255, 255);
        imagefill($newImage, 0, 0, $white);
        imagecopy($newImage, $originalImage, 0, 0, 0, 0, imagesx($originalImage), imagesy($originalImage));
        imagejpeg($newImage, $newFilePath, 100);
        imagedestroy($originalImage);
        imagedestroy($newImage);

        echo "The file " . $originalFileName . " has been uploaded and converted to JPG.";

        // Aggiorna il percorso dell'immagine del profilo dell'utente nel database
        $updateImageQuery = "UPDATE utenti SET image_path = :imagePath WHERE id = :userId";
        $stmtUpdateImage = $connection->prepare($updateImageQuery);
        $stmtUpdateImage->bindParam(':imagePath', $newFilePath, PDO::PARAM_STR);
        $stmtUpdateImage->bindParam(':userId', $user['id'], PDO::PARAM_INT);
        $stmtUpdateImage->execute();
    } else {
        // Controlla se $uploadOk è impostato su 0 a causa di un errore
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            // Rinomina il file appena caricato in "userImage"
            $newFileName = "userImage." . $imageFileType;
            $newFilePath = $target_dir . $newFileName;
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $newFilePath)) {
                echo "The file " . $originalFileName . " has been uploaded.";
                // Aggiorna il percorso dell'immagine del profilo dell'utente nel database
                $updateImageQuery = "UPDATE utenti SET image_path = :imagePath WHERE id = :userId";
                $stmtUpdateImage = $connection->prepare($updateImageQuery);
                $stmtUpdateImage->bindParam(':imagePath', $newFilePath, PDO::PARAM_STR);
                $stmtUpdateImage->bindParam(':userId', $user['id'], PDO::PARAM_INT);
                $stmtUpdateImage->execute();
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }
}
?>
