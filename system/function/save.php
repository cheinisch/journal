<?php

$headline = $_POST['headline'];
$text = $_POST['text'];
$date = $_POST['date'];
$time = $_POST['time'];
$modus = $_GET['modus'];

echo "Headline: " . $headline . " Text: " . $text;

if ($modus = 'new')
{
    # Neu Anlegen eines Beitrags
    require_once('function.php');

    $user = get_userfromhash();

    $timestamp = $date . ' ' . $time . ':00';
    
    #echo $timestamp;

    #echo $headline .', ' . $text .', ' .  $user['id'] .', ' . $date;
    createBlogPost($headline, $text, $user['id'], $timestamp, null, null);
}else if($modus = 'update')
{
    $id = $_GET['id'];
}

?>