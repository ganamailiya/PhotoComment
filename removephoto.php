<?php
session_start();
include("connection.php"); //Establishing connection with our database


if(isset($_GET['id']))
{
    $photoID = $_GET['id'];

    $remsql = $db->prepare("DELETE FROM photos WHERE photoID=?");
    $query ->bind_param('s', $photoID);
    if ($query->execute()) {
        header("Location: photos.php");
    }
    else {
        echo "Sorry, there was an error deleting the file.";
    }
    //echo $name." ".$email." ".$password

}
?>