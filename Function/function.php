<?php

$cookie_name = 'diarycms';
$cookie_value = 'username';

require 'lib/parsedown-1.7.4/Parsedown.php';

function check_session()
{
    $cookie_name = 'diarycms';
    $cookie_value = 'username';

    if(!isset($_COOKIE[$cookie_name])) {
        return false;
      } else {
        return true;
      }
}
function create_session()
{
    $cookie_name = 'diarycms';
    $cookie_value = 'username';

    setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
}


function destroy_session()
{
    
}

function findMarkdownFiles($directory) {
    $markdownFiles = [];
    
    $dirIterator = new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS);
    $iterator = new RecursiveIteratorIterator($dirIterator, RecursiveIteratorIterator::SELF_FIRST);
    
    foreach ($iterator as $fileInfo) {
        if ($fileInfo->isFile() && $fileInfo->getExtension() === 'md') {
            $markdownFiles[] = $fileInfo->getPathname();
        }
    }
    
    return $markdownFiles;
}

function getLogin($username, $password)
 {
    return true;
 }


function loadMarkdownPosts($directory = './../storage/2024-06-22/') {
  $posts = [];
  $files = findMarkdownFiles($directory);

  $parsedown = new Parsedown();


  foreach ($files as $file) {

      

      // Beispiel: /storage/2024-06-21/post-name.md
      $pathParts = explode('/', $file);
      $fileName = array_pop($pathParts);
      $datePart = array_slice($pathParts, -1)[0];

      if (preg_match('/(\d{4})-(\d{2})-(\d{2})/', $datePart, $matches)) {
          $date = $matches[0];
      } else {
          continue; // Dateiformat nicht erkannt
      }

      $title = basename($file, '.md');
      $content = file_get_contents($file);
      $htmlContent = $parsedown->text($content);

      $posts[] = [
          'title' => ucwords(str_replace('-', ' ', $title)),
          'date' => $date,
          'content' => $htmlContent
      ];
  }

  // Sortiere die Posts nach Datum (neuste zuerst)
  usort($posts, function($a, $b) {
      return strtotime($b['date']) - strtotime($a['date']);
  });

  return $posts;
}

?>

