<?php

require_once('function.php');

$headline = $_POST['headline'];
$text = $_POST['text'];
$date = $_POST['date'];
$time = $_POST['time'];
$modus = $_GET['modus'];

echo "Headline: " . $headline . " Text: " . $text;

$user = get_userfromhash();

if (isset($_GET['new']))
{
    # Neu Anlegen eines Beitrags
    

    

    $timestamp = $date . ' ' . $time . ':00';
    
    #echo $timestamp;

    #echo $headline .', ' . $text .', ' .  $user['id'] .', ' . $date;
    createBlogPost($headline, $text, $user['id'], $timestamp, null, null);

    $lastID = getLastBlogPostId();

    header('Location: ../../index.php?edit='.$lastID);

}else if(isset($_GET['update']))
{

    $id = $_GET['update'];

    $timestamp = $date . ' ' . $time . ':00';

    editBlogPost($id, $headline, $text, $user['id'], $timestamp, null, null);
    echo "Update";

    header('Location: ../../index.php?edit='.$id);
}

?>