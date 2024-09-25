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
        $created_at = (string)$post->created_at;
        $updated_at = (string)$post->updated_at;

        // Überprüfen, ob der Post bereits existiert
        $stmt = $db->prepare("SELECT COUNT(*) FROM posts WHERE id = :id AND author_id = :userId");
        $stmt->bindParam(':id', $postId, PDO::PARAM_INT);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $postExists = $stmt->fetchColumn();

        if ($postExists) {
            // Falls der Post existiert, diesen aktualisieren
            $stmt = $db->prepare("UPDATE posts SET title = :title, content = :content, updated_at = :updated_at WHERE id = :id AND author_id = :userId");
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':content', $content, PDO::PARAM_STR);
            $stmt->bindParam(':updated_at', $updated_at, PDO::PARAM_STR);
            $stmt->bindParam(':id', $postId, PDO::PARAM_INT);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        } else {
            // Falls der Post nicht existiert, einen neuen Eintrag erstellen
            $stmt = $db->prepare("INSERT INTO posts (id, author_id, title, content, created_at, updated_at) VALUES (:id, :userId, :title, :content, :created_at, :updated_at)");
            $stmt->bindParam(':id', $postId, PDO::PARAM_INT);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':content', $content, PDO::PARAM_STR);
            $stmt->bindParam(':created_at', $created_at, PDO::PARAM_STR);
            $stmt->bindParam(':updated_at', $updated_at, PDO::PARAM_STR);
        }

        // Query ausführen
        $stmt->execute();
    }

    // Erfolgreicher Import
    echo "Posts wurden erfolgreich importiert.";
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['xmlFile'])) {

    require_once('../system/function/function.php');

    $fileTmpPath = $_FILES['xmlFile']['tmp_name'];
    $userId = get_userfromhash();

    // Importiere die hochgeladene XML-Datei
    importPostsFromXML($fileTmpPath, $userId['id']);
}


?>
