<?php

/**
 * Include required Files
 */

require_once("system/function/function.php");

/*
 * Include Login Features
 */

if(isset($_GET['login'])) {

    #require_once('system/function/function.php');

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
    include('system/gui/journal.php');
}else if(isset($_GET['new-entry'])){
    include('system/gui/edit.php');
}else if(isset($_GET['post'])){
    include('system/gui/post.php');
}else if(isset($_GET['edit'])){
    include('system/gui/edit.php');
}else{
    if(!check_installation())
    {
        include('system/install.php');
    }
    elseif(check_session())
    {
        header("Location: index.php?journal");
    }else{
        include('system/gui/login.php');
    }
}
    
    
?>