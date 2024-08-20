<?php

require 'dbconnect.php';

/**
 * Erstellt einen neuen Blogpost in der Datenbank.
 *
 * @param string $title Titel des Blogposts.
 * @param string $content Inhalt des Blogposts.
 * @param string $author Name des Autors.
 * @param string $date Veröffentlichungsdatum im Format `Y-m-d`.
 * @param string $location Ortsangaben des Blogposts.
 * @param array $tags Tags des Blogposts.
 * @return int Die ID des neu erstellten Blogposts.
 */
function createBlogPost($title, $content, $authorId, $date, $location = null, $tags = []) {
    $db = getDatabaseConnection();
    $sql = "INSERT INTO posts (title, content, author_id, date, location, tags) VALUES (:title, :content, :author_id, :date, :location, :tags)";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':title' => $title,
        ':content' => $content,
        ':author_id' => $authorId,
        ':date' => $date,
        ':location' => $location,
        ':tags' => json_encode($tags) // Tags als JSON speichern
    ]);
    return $db->lastInsertId();
}
  
  /**
  * Bearbeitet einen bestehenden Blogpost in der Datenbank.
  *
  * @param int $postId Die ID des Blogposts.
  * @param string|null $newTitle Neuer Titel (optional).
  * @param string|null $newContent Neuer Inhalt (optional).
  * @param string|null $newAuthor Neuer Autor (optional).
  * @param string|null $newDate Neues Datum (optional).
  * @param string|null $newLocation Neuer Ort (optional).
  * @param array|null $newTags Neue Tags (optional).
  * @return bool True, wenn der Blogpost aktualisiert wurde, ansonsten false.
  */
  function editBlogPost($postId, $newTitle = null, $newContent = null, $newAuthorId = null, $newDate = null, $newLocation = null, $newTags = null) {
    $db = getDatabaseConnection();
    $fields = [];
    $params = [':id' => $postId];

    if ($newTitle !== null) {
        $fields[] = 'title = :title';
        $params[':title'] = $newTitle;
    }
    if ($newContent !== null) {
        $fields[] = 'content = :content';
        $params[':content'] = $newContent;
    }
    if ($newAuthorId !== null) {
        $fields[] = 'author_id = :author_id';
        $params[':author_id'] = $newAuthorId;
    }
    if ($newDate !== null) {
        $fields[] = 'date = :date';
        $params[':date'] = $newDate;
    }
    if ($newLocation !== null) {
        $fields[] = 'location = :location';
        $params[':location'] = $newLocation;
    }
    if ($newTags !== null) {
        $fields[] = 'tags = :tags';
        $params[':tags'] = json_encode($newTags); // Tags als JSON speichern
    }

    $sql = "UPDATE posts SET " . implode(', ', $fields) . " WHERE id = :id";
    $stmt = $db->prepare($sql);
    return $stmt->execute($params);
}
  
  /**
  * Liest einen Blogpost aus der Datenbank aus.
  *
  * @param int $postId Die ID des Blogposts.
  * @return array|null Array mit `id`, `title`, `content`, `author`, `date`, `location`, `tags` oder null, wenn nicht gefunden.
  */
  function readBlogPost($postId) {
    $id = $postId;
    $db = getDatabaseConnection();
   # $sql = "SELECT * FROM posts WHERE id = :id";
    $sql = "SELECT bp.*, u.username AS author FROM posts bp
    JOIN users u ON bp.author_id = u.id
    WHERE bp.id = :id";
    $stmt = $db->prepare($sql);
    $stmt->execute([':id' => $postId]);
    $post = $stmt->fetch();
    if ($post) {
        $post['tags'] = json_decode($post['tags'], true); // Tags von JSON in ein Array umwandeln
    }
    return $post;
}

/**
 * Liest alle Blogposts aus der Datenbank aus und gibt sie zusammen mit dem Autorennamen zurück.
 *
 * @param int $limit Die Anzahl der Blogposts, die abgerufen werden sollen.
 * @param int $offset Der Startpunkt für den Abruf.
 * @return array Array von Blogposts.
 */

 #function getAllBlogPosts($limit = 10, $offset = 0) {
 function getAllBlogPosts($userId, $limit = 10, $offset = 0) {
    $db = getDatabaseConnection();
    
    $sql = "SELECT bp.*, u.username AS author FROM posts bp
            JOIN users u ON bp.author_id = u.id
            WHERE bp.author_id = :userId
            ORDER BY bp.date DESC
            LIMIT :limit OFFSET :offset";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $posts = $stmt->fetchAll();
    
    // Tags von JSON in ein Array umwandeln
    foreach ($posts as &$post) {
        $post['tags'] = json_decode($post['tags'], true);
    }
    
    return $posts;
}
  

  
  
  /**
   * Löscht einen Blogpost aus der Datenbank.
   *
   * @param int $postId Die ID des Blogposts.
   * @return bool True, wenn der Blogpost gelöscht wurde, ansonsten false.
   */
  function deleteBlogPost($postId) {
    $db = getDatabaseConnection();
    $sql = "DELETE FROM posts WHERE id = :id";
    $stmt = $db->prepare($sql);
    return $stmt->execute([':id' => $postId]);
  }

  function getLastBlogPostId() {
    $db = getDatabaseConnection();

    $sql = "SELECT MAX(id) AS last_id FROM posts";
    $stmt = $db->prepare($sql);
    $stmt->execute();

    $result = $stmt->fetch();
    return $result['last_id'];
}

  function createUser($username, $email, $password, $userrole = 'reader') {
    $db = getDatabaseConnection();
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (username, email, password, userrole) VALUES (:username, :email, :password, :userrole)";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':username' => $username,
        ':email' => $email,
        ':password' => $hashedPassword,
        ':userrole' => $userrole
    ]);
    return $db->lastInsertId();
}

/**
 * Liest die Benutzerdetails aus der Datenbank aus.
 *
 * @param int $userId Die ID des Benutzers.
 * @return array|null Die Benutzerdetails oder null, wenn nicht gefunden.
 */
function getUserById($userId) {
    $db = getDatabaseConnection();
    $sql = "SELECT * FROM users WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->execute([':id' => $userId]);
    return $stmt->fetch();
}

function getAllUsers() {
    $db = getDatabaseConnection();
    $sql = "SELECT id, username, email, userrole FROM users";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function updateUser($userId, $username, $email, $userrole) {
    $db = getDatabaseConnection();
    $sql = "UPDATE users SET username = :username, email = :email, userrole = :userrole WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':userrole', $userrole);
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    return $stmt->execute();
}



/**
 * Prüft, ob ein Benutzer Adminrechte hat.
 *
 * @param int $userID Die Benutzer-ID.
 * @return bool True, wenn der Benutzer Adminrechte hat, andernfalls false.
 */
function isUserAdmin($userID) {
    $db = getDatabaseConnection();
  
    $sql = "SELECT userrole FROM users WHERE id = :userID";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
    $stmt->execute();
  
    $user = $stmt->fetch();
  
    if ($user && $user['userrole'] === 'admin') {
        return true;
    }
  
    return false;
  }
  
  /**
   * Holt die Benutzer-ID basierend auf dem Benutzernamen.
   *
   * @param string $username Der Benutzername.
   * @return int|null Die Benutzer-ID oder null, wenn der Benutzer nicht existiert.
   */
  function getIDFromUsername($username) {
    $db = getDatabaseConnection();
  
    $sql = "SELECT id FROM users WHERE username = :username";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
  
    $user = $stmt->fetch();
  
    if ($user) {
        return $user['id'];
    }
  
    return null;
  }


function updateSettings($siteName, $language, $template, $dbversion, $release) {
    $db = getDatabaseConnection();
    $sql = "UPDATE settings SET 
                site_name = :site_name, 
                language = :language, 
                template = :template,
                dbversion = :dbversion,
                `release` = :release
            WHERE id = 1";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':site_name', $siteName);
    $stmt->bindParam(':language', $language);
    $stmt->bindParam(':template', $template);
    $stmt->bindParam(':dbversion', $dbversion, PDO::PARAM_INT);
    $stmt->bindParam(':release', $release);
    return $stmt->execute();
}



  function getSettings() {
    $db = getDatabaseConnection();

    $sql = "SELECT * FROM settings WHERE id = 1";
    $stmt = $db->prepare($sql);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function isDevRelease()
{
    $db = getDatabaseConnection(); // Hier wird die Funktion zum Herstellen der Datenbankverbindung verwendet

     $sql = "SELECT `release` FROM settings WHERE id =1";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $releaseType = $stmt->fetchColumn();

    return ($releaseType === 'dev');
}

/**
 * Gibt die Gesamtanzahl der Blogposts eines bestimmten Benutzers zurück.
 *
 * @param int $userId ID des Benutzers
 * @return int Anzahl der Blogposts des Benutzers
 */
function getTotalBlogPostsByUser($userId) {
    $db = getDatabaseConnection();
    $sql = "SELECT COUNT(*) FROM posts WHERE author_id = :userId";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
    return (int)$stmt->fetchColumn();
}

/**
 * Gibt die Daten aller Blogposts eines bestimmten Benutzers zurück.
 *
 * @param int $userId ID des Benutzers
 * @return array Array der Blogpost-Daten
 */
function getBlogPostDatesByUser($userId) {
    $db = getDatabaseConnection();
    $sql = "SELECT DATE(date) as date FROM posts WHERE author_id = :userId";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// Funktion, um die Benutzerdaten zu aktualisieren
function updateUserSettings($userId, $name, $email, $password, $oldPassword) {
    $db = getDatabaseConnection();
    
    // Überprüfen, ob das alte Passwort korrekt ist
    $stmt = $db->prepare("SELECT password FROM users WHERE id = :userId");
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $hashedPassword = $stmt->fetchColumn();

    if (!password_verify($oldPassword, $hashedPassword)) {
        return "Das alte Passwort ist falsch.";
    }

    // Wenn das Passwort aktualisiert werden soll, neu hashen
    if (!empty($password)) {
        $newHashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->prepare("UPDATE users SET name = :name, email = :email, password = :password WHERE id = :userId");
        $stmt->bindParam(':password', $newHashedPassword, PDO::PARAM_STR);
    } else {
        $stmt = $db->prepare("UPDATE users SET name = :name, email = :email WHERE id = :userId");
    }

    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        return "Einstellungen erfolgreich aktualisiert.";
    } else {
        return "Fehler beim Aktualisieren der Einstellungen.";
    }
}

function getUserData($userId)
{
    $db = getDatabaseConnection();
    $stmt = $db->prepare("SELECT name, email FROM users WHERE id = :userId");
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC); 

    return $user;
    
}

?>
