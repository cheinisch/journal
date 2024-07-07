<?php

/**
 * Include required Files
 */

require_once("function/function.php");

/*
 * Include Login Features
 */

if(isset($_GET['login'])) {

    require_once('function/function.php');

    $user = $_POST['username'];
    $passwd = $_POST['password'];

    if(getLogin($user, $passwd))
    {
        create_session($user);
        header("Location: index.php");
    }else{
        echo "fail";
    }

}
    
    if(!check_installation())
    {
        include('function/install.php');
    }
    elseif(check_session())
    {
        header("Location: journal.php");
    }else{
        include('bin/login.php');
    }
?>