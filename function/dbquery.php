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
function createBlogPost($title, $content, $author, $date, $location = null, $tags = []) {
    $db = getDatabaseConnection();
    $sql = "INSERT INTO blog_posts (title, content, author, date, location, tags) VALUES (:title, :content, :author, :date, :location, :tags)";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':title' => $title,
        ':content' => $content,
        ':author' => $author,
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
  function editBlogPost($postId, $newTitle = null, $newContent = null, $newAuthor = null, $newDate = null, $newLocation = null, $newTags = null) {
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
    if ($newAuthor !== null) {
        $fields[] = 'author = :author';
        $params[':author'] = $newAuthor;
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
  
    $sql = "UPDATE blog_posts SET " . implode(', ', $fields) . " WHERE id = :id";
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
    $db = getDatabaseConnection();
    $sql = "SELECT * FROM blog_posts WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->execute([':id' => $postId]);
    $post = $stmt->fetch();
    if ($post) {
        $post['tags'] = json_decode($post['tags'], true); // Tags von JSON in ein Array umwandeln
    }
    return $post;
  }
  
  
  
  /**
   * Löscht einen Blogpost aus der Datenbank.
   *
   * @param int $postId Die ID des Blogposts.
   * @return bool True, wenn der Blogpost gelöscht wurde, ansonsten false.
   */
  function deleteBlogPost($postId) {
    $db = getDatabaseConnection();
    $sql = "DELETE FROM blog_posts WHERE id = :id";
    $stmt = $db->prepare($sql);
    return $stmt->execute([':id' => $postId]);
  }

?>