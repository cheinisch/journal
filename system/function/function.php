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


function get_version()
{
  return 0;
}

function get_versionfromgit()
{
  $owner = 'cheinisch';
  $repo = 'journal';

  $version = getLatestReleaseVersion($owner, $repo);
  echo "Die neueste Version ist: " . ($version ?: 'Nicht gefunden');

  return $version;

}


?>

