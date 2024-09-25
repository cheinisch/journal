<?php

// Funktion zum Importieren von XML-Daten in die Datenbank
function importPostsFromXML($xmlFilePath, $userId) {

    // Prüfen, ob die Datei existiert
    if (!file_exists($xmlFilePath)) {
        die("Die XML-Datei existiert nicht.");
    }

    // Lade und parse die XML-Datei
    $xml = simplexml_load_file($xmlFilePath);
    if ($xml === false) {
        die("Fehler beim Laden der XML-Datei.");
    }

    // Datenbankverbindung herstellen
    $db = getDatabaseConnection();

    // Durchlaufe alle Posts im XML
    foreach ($xml->post as $post) {
        $postId = (int)$post->id;
        $title = (string)$post->title;
        $content = (string)$post->content;

        // Überprüfen, ob der Post bereits existiert
        $stmt = $db->prepare("SELECT COUNT(*) FROM posts WHERE id = :id AND author_id = :userId");
        $stmt->bindParam(':id', $postId, PDO::PARAM_INT);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $postExists = $stmt->fetchColumn();

        if ($postExists) {
            // Falls der Post existiert, diesen aktualisieren
            $stmt = $db->prepare("UPDATE posts SET title = :title, content = :content WHERE id = :id AND author_id = :userId");
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':content', $content, PDO::PARAM_STR);
            $stmt->bindParam(':id', $postId, PDO::PARAM_INT);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        } else {
            // Falls der Post nicht existiert, einen neuen Eintrag erstellen
            $stmt = $db->prepare("INSERT INTO posts (id, author_id, title, content) VALUES (:id, :userId, :title, :content)");
            $stmt->bindParam(':id', $postId, PDO::PARAM_INT);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':content', $content, PDO::PARAM_STR);
        }

        // Query ausführen
        $stmt->execute();
    }

    // Erfolgreicher Import
    echo "Posts wurden erfolgreich importiert.";
}

function getDatabaseConnection() {

    $config = require('../storage/config.php');

    $dbHost = $config['db']['host'];
    $dbName = $config['db']['name'];
    $dbUser = $config['db']['user'];
    $dbPass = $config['db']['pass'];

    $host = $dbHost; // Hostname oder IP-Adresse
    $dbname = $dbName;    // Datenbankname
    $username = $dbUser;  // Benutzername
    $password = $dbPass;      // Passwort

    try {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];
        return new PDO($dsn, $username, $password, $options);
    } catch (PDOException $e) {
        die("Datenbankverbindung fehlgeschlagen: " . $e->getMessage());
    }
}

function get_userfromhash()
{
  $db = getDatabaseConnection();

  $sql = "SELECT id, username, userrole FROM users";
  $stmt = $db->prepare($sql);
  $stmt->execute();

  $users = $stmt->fetchAll();

  foreach ($users as $user) {
    if(get_userhash($user['username']) == get_userfromsession())
    {
    return $user;
    }
  }
    return "";


}

function get_userhash($username)
{
  $userhash = hash('sha256', $username);
  return $userhash;
}

function get_userfromsession()
{
  $cookie_name = 'jrnl';

  if(!isset($_COOKIE[$cookie_name])) {
    return "";
  } else {
    return $_COOKIE[$cookie_name];
  }

  
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['xmlFile'])) {


    $fileTmpPath = $_FILES['xmlFile']['tmp_name'];
    $userId = get_userfromhash();

    // Importiere die hochgeladene XML-Datei
    importPostsFromXML($fileTmpPath, $userId['id']);
}


?>
