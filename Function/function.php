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
function create_session()
{
    $cookie_name = 'diarycms';
    $cookie_value = get_userhash(0);

    setcookie($cookie_name, $cookie_value, time() + (3600 * 30), "/"); // 3600 = 1 hour
}


function destroy_session()
{
    
}

/*
 * Userfunctions
 */

function get_userhash($id)
{
  return 123;
}

function get_idfromhash($hash)
{

}

function getLogin($username, $password)
 {
    return true;
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

