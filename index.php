<?php

/**
 * Include required Files
 */

 require_once("function/function.php");

    check_installation();
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