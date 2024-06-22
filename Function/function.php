<?php

function check_session()
{
    echo 'Hello ' . htmlspecialchars($_COOKIE["diary-cms"]) . '!';
}
function create_session()
{
    $value = 'nothing';

    setcookie("Diary-CMS", $value);
    setcookie("Diary-CMS", $value, time()+3600);  /* expire in 1 hour */
}


function destroy_session()
{
    
}


function getLogin($username, $password)
 {
    return true;
 }

?>

