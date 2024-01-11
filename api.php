<?php
function handleGetRequestRecensione($table, $key, $pdo) {
    $sql = "SELECT * FROM `$table`" . ($key ? " WHERE ID = " . $pdo->quote($key) : '');
    try {
        $statement = $pdo->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($result);
    } catch (PDOException $e) {
        http_response_code(404);
        $response = array('status' => 'error', 'message' => $e->getMessage());
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}

function handlePostRequestRecensione($table, $json_data, $pdo) {
    $set = json_decode($json_data, true);
    $id_utente = $set['id_utente'];
    $id_film = $set['id_film'];

    // Verifica se l'utente ha già recensito questo film
    if (hasUserReviewedFilm($id_utente, $id_film, $pdo)) {
        http_response_code(400);  // Bad Request
        $response = array('status' => 'error', 'message' => 'Hai già recensito questo film.');
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
    }

    // Procedi con l'inserimento della recensione
    $columns = preg_replace('/[^a-z0-9_]+/i', '', array_keys($set));
    $values = array_map(function ($value) use ($pdo) {
        return $pdo->quote($value);
    }, array_values($set));

    $columns_string = implode(', ', $columns);
    $values_string = implode(', ', $values);

    $sql = "INSERT INTO `$table` ($columns_string) VALUES ($values_string)";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $lastInsertId = $pdo->lastInsertId();
        $response = array('status' => 'success', 'message' => 'Recensione aggiunta con successo.', 'inserted_id' => $lastInsertId);
        header('Content-Type: application/json');
        echo json_encode($response);
    } catch (PDOException $e) {
        http_response_code(404);
        $response = array('status' => 'error', 'message' => $e->getMessage());
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}

// Funzione per verificare se l'utente ha già recensito il film
function hasUserReviewedFilm($id_utente, $id_film, $pdo) {
    $sql = "SELECT COUNT(*) AS count FROM recensioni WHERE id_utente = :id_utente AND id_film = :id_film";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_utente', $id_utente, PDO::PARAM_INT);
    $stmt->bindParam(':id_film', $id_film, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['count'] > 0;
}



function handlePutRequestImmagine($table, $input, $key, $pdo) {
  $sql = "UPDATE $table SET immagine = :campo WHERE id = :id";
  try {
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':campo', $input['immagine'], PDO::PARAM_STR);
      $stmt->bindParam(':id', $key, PDO::PARAM_INT);
      $stmt->execute();
      $response = array('status' => 'success', 'message' => 'PUT OK');
      header('Content-Type: application/json');
      echo json_encode($response);
  } catch (PDOException $e) {
      http_response_code(404);
      $response = array('status' => 'error', 'message' => $e->getMessage());
      header('Content-Type: application/json');
      echo json_encode($response);
  }
}

function handlePutRequestUsername($table, $json_data, $pdo) {
    $set = json_decode($json_data, true);
    $id = $set['id'];
    $newUsername = $set['username'];

    // Verifica se un altro utente ha già lo stesso username
    $queryCheckUsername = "SELECT id FROM `$table` WHERE username = :newUsername AND id != :id";
    $stmtCheckUsername = $pdo->prepare($queryCheckUsername);
    $stmtCheckUsername->bindParam(':newUsername', $newUsername, PDO::PARAM_STR);
    $stmtCheckUsername->bindParam(':id', $id, PDO::PARAM_INT);
    $stmtCheckUsername->execute();

    $existingUser = $stmtCheckUsername->fetch(PDO::FETCH_ASSOC);

    if ($existingUser) {
        http_response_code(400); // Bad Request
        $response = array('status' => 'error', 'message' => "Lo username è già in uso da un altro utente.");
    } else {
        // L'utente esiste, esegui l'aggiornamento dello username
        $updateUsernameQuery = "UPDATE `$table` SET username = :newUsername WHERE id = :id";
        try {
            $stmt = $pdo->prepare($updateUsernameQuery);
            $stmt->bindParam(':newUsername', $newUsername, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $response = array('status' => 'success', 'message' => 'Username modificato con successo');
        } catch (PDOException $e) {
            http_response_code(500);  // Internal Server Error
            $response = array('status' => 'error', 'message' => 'Errore durante la modifica dello username: ' . $e->getMessage());
        }
    }

    // Invia la risposta in formato JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}



function handleDeleteUser($table, $key, $pdo) {
    try {
        // Elimina manualmente le recensioni associate
        $sqlDeleteReviews = "DELETE FROM recensioni WHERE id_utente = " . $pdo->quote($key);
        $pdo->exec($sqlDeleteReviews);

        // Procedi con l'eliminazione dell'utente
        $sqlDeleteUser = "DELETE FROM `$table` WHERE id = " . $pdo->quote($key);
        $pdo->exec($sqlDeleteUser);

        $response = array('status' => 'success', 'message' => 'Utente eliminato con successo');
    } catch (PDOException $e) {
        http_response_code(500);  // Internal Server Error
        $response = array('status' => 'error', 'message' => 'Errore durante l\'eliminazione dell\'utente: ' . $e->getMessage());
    }

    // Invia la risposta in formato JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}


// get the HTTP method, path, and body of the request
$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));
$input = json_decode(file_get_contents('php://input'), true);



// connect to the mysql database
$connessione = require __DIR__ . '/connessioneDB.php';

// retrieve the table and key from the path
$table = preg_replace('/[^a-z0-9_]+/i', '', array_shift($request));
$_key = array_shift($request);
$key = $_key;
//$key = $_key + 0;


// escape the columns and values from the input object
if (isset($input)) {
  $columns = preg_replace('/[^a-z0-9_]+/i', '', array_keys($input));
  $values = array_map(function ($value) use ($connessione) {
      if ($value === null) return null;
      return $connessione->quote($value);
  }, array_values($input));
}



// build the SET part of the SQL command
if (isset($input)) {
  $set = '';
  for ($i = 0; $i < count($columns); $i++) {
      $set .= ($i > 0 ? ',' : '') . '`' . $columns[$i] . '`=';
      $set .= ($values[$i] === null ? 'NULL' : '"' . $values[$i] . '"');
  }
}

// create SQL based on HTTP method
switch ($method) {
  case 'GET':
      handleGetRequestRecensione($table, $key, $connessione);
      break;
  case 'PUT':
      /*handlePutRequestTitolo($table, $input, $key, $connessione);
      
      handlePutRequestImmagine($table, $input, $key, $connessione);
      handlePutRequestCategoria($table, $input, $key, $connessione);
      break;*/
      handlePutRequestUsername($table, file_get_contents('php://input'), $connessione);
      break;
  case 'POST':
      handlePostRequestRecensione($table, file_get_contents('php://input'), $connessione);
      break;
  case 'DELETE':
      handleDeleteUser($table, $key, $connessione);
      break;
}


?>