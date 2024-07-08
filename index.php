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

    if($_GET['login'] == 'login')
    {
        

        $user = $_POST['username'];
        $passwd = $_POST['password'];

        if(getLogin($user, $passwd))
        {
            create_session($user);
            header("Location: index.php");
        }else{
            echo "fail";
        }
    }else{
        destroy_session();
        sleep(2);
        header("Location: index.php");
    }

}

if(isset($_GET['journal'])) {
    include('function/journal.php');
}else{
    if(!check_installation())
    {
        include('function/install.php');
    }
    elseif(check_session())
    {
        header("Location: index.php?journal");
    }else{
        include('bin/login.php');
    }
}
    
    
?>