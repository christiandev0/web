<?php

$hostname = "localhost:3306";
$username = "root";
$password = "";
$database = "progetto";

// Funzione per verificare e ottenere l'ID dell'utente dal token di accesso
function getUserIdFromToken($token, $conn) {
    $stmt = $conn->prepare("SELECT id FROM utenti WHERE token = :token");
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user ? $user['id'] : null;
}

try {
    $conn = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verifica l'autenticazione con il token
    $auth_token = isset($_SERVER['HTTP_AUTHORIZATION']) ? trim($_SERVER['HTTP_AUTHORIZATION']) : null;

    if (!$auth_token) {
        header('HTTP/1.1 401 Unauthorized');
        echo json_encode(["error" => "Token di accesso mancante"]);
        exit();
    }

    // Ottieni l'ID dell'utente dal token
    $user_id = getUserIdFromToken($auth_token, $conn);

    if (!$user_id) {
        header('HTTP/1.1 401 Unauthorized');
        echo json_encode(["error" => "Token di accesso non valido"]);
        exit();
    }

    // Gestione delle richieste
    $method = $_SERVER['REQUEST_METHOD'];
    $request_uri = $_SERVER['REQUEST_URI'];
    $uri_segments = explode('/', trim(parse_url($request_uri, PHP_URL_PATH), '/'));

    switch ($method) {
        case 'GET':
            // Ottieni i dettagli di un utente specifico
            if ($uri_segments[0] == 'users' && count($uri_segments) == 2) {
                $id = $uri_segments[1];
                $stmt = $conn->prepare("SELECT id, name, email FROM utenti WHERE id = :id");
                $stmt->bindParam(':id', $id);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$user) {
                    header('HTTP/1.1 404 Not Found');
                    echo json_encode(["error" => "Utente non trovato"]);
                } else {
                    echo json_encode($user);
                }
            } else {
                // Ottieni tutti gli utenti
                $stmt = $conn->prepare("SELECT id, name, email FROM utenti");
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($result);
            }
            break;

        case 'PUT':
            // Aggiorna i dettagli di un utente specifico
            if ($uri_segments[0] == 'users' && count($uri_segments) == 2 && $uri_segments[1] == $user_id) {
                $data = json_decode(file_get_contents("php://input"), true);
                $stmt = $conn->prepare("UPDATE utenti SET name = :name, email = :email WHERE id = :id");
                $stmt->bindParam(':name', $data['name']);
                $stmt->bindParam(':email', $data['email']);
                $stmt->bindParam(':id', $user_id);
                $stmt->execute();
                echo json_encode(["success" => "Utente aggiornato con successo"]);
            } else {
                header('HTTP/1.1 403 Forbidden');
                echo json_encode(["error" => "Operazione non consentita"]);
            }
            break;

        case 'DELETE':
            // Elimina un utente
            if ($uri_segments[0] == 'users' && count($uri_segments) == 2 && $uri_segments[1] == $user_id) {
                $stmt = $conn->prepare("DELETE FROM utenti WHERE id = :id");
                $stmt->bindParam(':id', $user_id);
                $stmt->execute();
                echo json_encode(["success" => "Utente eliminato con successo"]);
            } else {
                header('HTTP/1.1 403 Forbidden');
                echo json_encode(["error" => "Operazione non consentita"]);
            }
            break;

        default:
            header('HTTP/1.1 405 Method Not Allowed');
            header('Allow: GET, PUT, DELETE');
            break;
    }
} catch (PDOException $e) {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(["error" => "Errore di connessione al database: " . $e->getMessage()]);
}

$conn = null; // Chiudi la connessione quando hai finito
?>
