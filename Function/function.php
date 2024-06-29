<?php

$cookie_name = 'diarycms';
$cookie_value = 'username';

require 'lib/parsedown-1.7.4/Parsedown.php';
require 'dbquery.php';



 /*
  * Installation
 */

 function check_installation()
 {
  
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
 * Security  
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
    $cookie_value = 'username';

    setcookie($cookie_name, $cookie_value, time() + (3600 * 30), "/"); // 3600 = 1 hour
}


function destroy_session()
{
    
}

function getLogin($username, $password)
 {
    return true;
 }

/*
 * Functions
 */


?>

