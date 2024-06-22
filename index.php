<?php

/**
 * Include required Files
 */

 require_once("function/function.php");

    if(check_session())
    {
        header("Location: bin/journal.php");
    }else{
        include('bin/login.php');
    }
?>