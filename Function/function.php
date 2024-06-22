<?php

$cookie_name = 'diarycms';
$cookie_value = 'username';

function check_session()
{
    if(!isset($_COOKIE[$cookie_name])) {
        return false;
      } else {
        return true;
      }
}
function create_session()
{
    setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
}


function destroy_session()
{
    
}


function getLogin($username, $password)
 {
    return true;
 }

?>

