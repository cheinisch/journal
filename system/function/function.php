<?php

$cookie_name = 'jrnl';
$cookie_value = 'username';

#require 'lib/parsedown-1.7.4/Parsedown.php';
require 'dbquery.php';



 /*
  * Installation
 */

 function check_installation()
 {
  $config_file = 'storage/config.php';
  if(file_exists($config_file))
  {
    return true;
  }else{
    return false;
  }
 }

/*
 * Update Logistik
*/

function check_update()
{
  $version_git = get_versionfromgit();
  $version_local = get_version();

  return version_compare($version_git, $version_local, '>');
}

 function update()
 {
    // Kopiere Update Datei in Root Verzeichnis

    copy('system/function/update.php', 'update.php');

    // öffne Updatedatei

    header("Location: update.php");

 }

/*
 * Cookie
 */

function check_session()
{
    $cookie_name = 'jrnl';
    $cookie_value = 'username';

    if(!isset($_COOKIE[$cookie_name])) {
        return false;
      } else {
        return true;
      }
}
function create_session($username)
{
    $cookie_name = 'jrnl';
    $cookie_value = get_userhash($username);

    setcookie($cookie_name, $cookie_value, time() + (3600 * 30), "/"); // 3600 = 1 hour
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


function destroy_session()
{
  $cookie_name = 'jrnl';
  setcookie($cookie_name, "", time() - (3600 * 30), "/"); // 3600 = 1 hour
}

/*
 * Userfunctions
 */

function get_userhash($username)
{
  $userhash = hash('sha256', $username);
  return $userhash;
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

function get_currentusername()
{
  $username = get_userfromhash();

  return $username['username'];
}

function enable_admin()
{

  $admin = false;

  $username = get_currentusername();

  $userid = getIDFromUsername($username);

  $admin = isUserAdmin($userid);


  return $admin;
}

function getLogin($username, $password)
{
  $db = getDatabaseConnection();

  $sql = "SELECT id, password FROM users WHERE username = :username";
  $stmt = $db->prepare($sql);
  $stmt->bindParam(':username', $username, PDO::PARAM_STR);
  $stmt->execute();
  
  $user = $stmt->fetch();

  // Benutzername existiert nicht
  if (!$user) {
      return false;
  }

  // Passwort überprüfen
  if (password_verify($password, $user['password'])) {
      return true;
  }

  return false;
}



/*
 * Functions
 */


function get_date_fromTimestamp($timestamp)
{

}

function get_time_fromTimestamp($timestamp)
{

}

function get_version()
{

  try {
      $versionFilePath = 'VERSION';
      $version = getVersionFromFile($versionFilePath);
      return htmlspecialchars($version);
  } catch (Exception $e) {
      echo "Fehler: " . htmlspecialchars($e->getMessage());
  }

}

function getVersionFromFile($filePath) {
  if (file_exists($filePath) && is_readable($filePath)) {
      $version = file_get_contents($filePath);
      return trim($version);
  } else {
      throw new Exception("Datei nicht gefunden oder nicht lesbar.");
  }
}

function getLatestGitHubRelease($repoOwner, $repoName) {
  $url = "https://api.github.com/repos/$repoOwner/$repoName/releases/latest";
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0'); // GitHub API erfordert einen User-Agent Header

  $response = curl_exec($ch);
  curl_close($ch);

  if ($response === false) {
      throw new Exception("Fehler beim Abrufen der GitHub-Version.");
  }

  $data = json_decode($response, true);
  if (isset($data['tag_name'])) {
      return $data['tag_name'];
  } else {
      throw new Exception("Version konnte nicht abgerufen werden.");
  }
}

function get_versionfromgit()
{
  $owner = 'cheinisch';
  $repo = 'journal';

  try {
    $repoOwner = 'cheinisch';
    $repoName = 'journal';  // Ersetze 'repository' durch den Namen des Repositories
    $latestVersion = getLatestGitHubRelease($repoOwner, $repoName);
    return htmlspecialchars($latestVersion);
  } catch (Exception $e) {
      echo "Fehler: " . htmlspecialchars($e->getMessage());
  }


}

/**
 * Liest alle Dateien aus dem Verzeichnis 'Lang' und gibt sie als Array zurück.
 *
 * @return array Ein Array mit den Namen der Dateien im Verzeichnis 'Lang'.
 */
function getLanguageFiles($directory) {
  #$directory = __DIR__ . '/../Lang'; // Pfad zum 'Lang'-Verzeichnis
  $files = [];

  if (is_dir($directory)) {
      $dirContents = scandir($directory);

      foreach ($dirContents as $item) {
          if ($item !== '.' && $item !== '..' && is_file($directory . '/' . $item)) {
              $files[] = $item;
          }
      }
  }

  return $files;
}

/**
 * Funktion zur Berechnung und Anzeige der Paginierung
 *
 * @param int $totalPosts Gesamtanzahl der Posts
 * @param int $currentPage Aktuelle Seite
 * @param int $postsPerPage Anzahl der Posts pro Seite
 * @return string HTML-Markup für die Paginierung
 */
function getPagination($totalPosts, $currentPage, $postsPerPage) {
  $totalPages = ceil($totalPosts / $postsPerPage);
  $paginationHtml = '<ul class="uk-pagination uk-flex-center">';

  // Vorherige Seite
  if ($currentPage > 1) {
      $paginationHtml .= '<li><a href="?journal=' . ($currentPage - 1) . '"><span uk-pagination-previous></span></a></li>';
  }

  // Seitenlinks
  for ($i = 1; $i <= $totalPages; $i++) {
      if ($i == $currentPage) {
          $paginationHtml .= '<li class="uk-active"><span>' . $i . '</span></li>';
      } else {
          $paginationHtml .= '<li><a href="?journal=' . $i . '">' . $i . '</a></li>';
      }
  }

  // Nächste Seite
  if ($currentPage < $totalPages) {
      $paginationHtml .= '<li><a href="?journal=' . ($currentPage + 1) . '"><span uk-pagination-next></span></a></li>';
  }

  $paginationHtml .= '</ul>';

  return $paginationHtml;
}

/**
 * Funktion zum Abrufen der Blogposts für die aktuelle Seite
 *
 * @param int $page Aktuelle Seite
 * @param int $postsPerPage Anzahl der Posts pro Seite
 * @param int $userId ID des Benutzers, dessen Posts geladen werden sollen
 * @return array Array der Blogposts
 */
function getBlogPostsByPage($page, $postsPerPage, $userId) {
  $offset = ($page - 1) * $postsPerPage;
  return getAllBlogPosts($userId, $postsPerPage, $offset);
}

/**
 * Erstellt ein Kalender-Widget mit hervorgehobenen Tagen, an denen es Blogposts gibt.
 *
 * @param array $highlightDates Array der hervorgehobenen Daten
 * @param int $year Jahr des Kalenders
 * @param int $month Monat des Kalenders
 * @return string HTML-Markup des Kalenders
 */
function createCalendarWidget($highlightDates, $year, $month) {

  // Tage des Monats
  $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

  // Erster Tag des Monats
  $firstDayOfMonth = date('N', strtotime("$year-$month-01"));

  // HTML für den Kalender
  $calendarHtml = '<div class="uk-flex uk-flex-between">';
  $calendarHtml .= '<h3 id="currentMonth">' . date('F Y', strtotime("$year-$month-01")) . '</h3>';
  $calendarHtml .= '</div>';

  $calendarHtml .= '<table class="uk-table uk-table-divider">';
  $calendarHtml .= '<thead><tr>';
  $calendarHtml .= '<th>Mo</th><th>Di</th><th>Mi</th><th>Do</th><th>Fr</th><th>Sa</th><th>So</th>';
  $calendarHtml .= '</tr></thead>';
  $calendarHtml .= '<tbody><tr>';
  

  // Leere Zellen vor dem ersten Tag des Monats
  for ($i = 1; $i < $firstDayOfMonth; $i++) {
      $calendarHtml .= '<td></td>';
  }

  // Zellen für jeden Tag des Monats
  for ($day = 1; $day <= $daysInMonth; $day++) {
      $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
      $highlightClass = in_array($date, $highlightDates) ? 'uk-background-primary uk-light' : '';
      $calendarHtml .= "<td class='$highlightClass'>$day</td>";

      // Neue Woche beginnen
      if (($firstDayOfMonth + $day - 1) % 7 == 0) {
          $calendarHtml .= '</tr><tr>';
      }
  }

  // Leere Zellen nach dem letzten Tag des Monats
  $remainingDays = (7 - (($firstDayOfMonth + $daysInMonth - 1) % 7)) % 7;
  for ($i = 0; $i < $remainingDays; $i++) {
      $calendarHtml .= '<td></td>';
  }

  $calendarHtml .= '</tr></tbody></table>';
  $calendarHtml .= '<button id="prevMonth" class="uk-button uk-button-default nav-button">&lt;&lt; prev</button>';
  $calendarHtml .= '<button id="nextMonth" class="uk-button uk-button-default nav-button">next &gt;&gt;</button>';

  return $calendarHtml;
}




?>

