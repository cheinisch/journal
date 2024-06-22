<?php

/**
 * Include required Files
 */

 require_once("function/function.php");

    if(check_session())
    {
        header("Location: journal.php");
    }else{
        include('bin/login.php');
    }
?>