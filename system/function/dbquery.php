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


  function updateSettings($siteName, $language, $template) {
    $db = getDatabaseConnection();

    $sql = "UPDATE settings SET site_name = :site_name, language = :language, template = :template WHERE id = 1";
    $stmt = $db->prepare($sql);

    $stmt->bindParam(':site_name', $siteName, PDO::PARAM_STR);
    $stmt->bindParam(':language', $language, PDO::PARAM_STR);
    $stmt->bindParam(':template', $template, PDO::PARAM_STR);

    return $stmt->execute();
}

  function getSettings() {
    $db = getDatabaseConnection();

    $sql = "SELECT site_name, language, template FROM settings WHERE id = 1";
    $stmt = $db->prepare($sql);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}


?>