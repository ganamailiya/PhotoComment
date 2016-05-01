<?php
session_start();
include("connection.php"); //Establishing connection with our database
include ("login.php");

$msg = ""; //Variable for storing our errors.
if(isset($_POST["submit"])) {

    //get and clean user input
    $desc = $_POST["desc"];
    $desc =stripslashes($desc);
    $desc =htmlentities($desc);
    $desc =htmlspecialchars($desc);
    $desc = mysqli_real_escape_string($db, $desc);

    $photoID = $_POST["photoID"];
    $photoID = stripslashes($photoID);
    $photoID = htmlentities($photoID);
    $photoID = htmlspecialchars($photoID);
    $photoID = mysqli_real_escape_string($db, $photoID);

    $name = $_SESSION["username"];
    $data = $db->prepare("SELECT userID FROM users WHERE username = ? ");
    $data->bind_param('s', $name);
    if ($data->execute()) {
        $rower = $data->get_result();
        $rower = $rower->fetch_assoc();

        $id = $rower['userID'];
        $timenow = strtotime(time, now);
        echo $timenow;
        $query = $db->prepare("INSERT INTO comments (description, postDate, userID, photoID)
        VALUES (?, ?, ?, ?)");
        $query->bind_param('siii', $desc, $timenow, $id, $photoID);

        if ($query->execute()) {
            $msg = "Thank You! comment added. click <a href='photo.php?id=".$photoID."'>here</a> to go back";
        }
        else{
            echo "<br> what is";
        }
    }
    else{
        $msg = "You need to login first";
    }
}

?>