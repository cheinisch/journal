<?php

$cookie_name = 'diarycms';
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

 }

 function update()
 {

 }

/*
 * Cookie
 */

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
function create_session($username)
{
    $cookie_name = 'diarycms';
    $cookie_value = get_userhash($username);

    setcookie($cookie_name, $cookie_value, time() + (3600 * 30), "/"); // 3600 = 1 hour
}

function get_userfromsession()
{
  $cookie_name = 'diarycms';

  if(!isset($_COOKIE[$cookie_name])) {
    return "";
  } else {
    return $_COOKIE[$cookie_name];
  }

  
}


function destroy_session()
{
  $cookie_name = 'diarycms';
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
      echo "Installierte Version: " . htmlspecialchars($version);
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
    echo "Aktuelle GitHub-Version: " . htmlspecialchars($latestVersion);
  } catch (Exception $e) {
      echo "Fehler: " . htmlspecialchars($e->getMessage());
  }


}


?>

