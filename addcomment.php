<?php
session_start();
include("connection.php"); //Establishing connection with our database
include ("login.php");

$msg = ""; //Variable for storing our errors.
if(isset($_POST["submit"]) && $_POST['my_token'] === $_SESSION['my_token']) {

    //get and clean user input
    $desc = $_POST["desc"];
    $desc =stripslashes($_POST["desc"]);
    $desc =htmlentities($_POST["desc"]);
    $desc =htmlspecialchars($_POST["desc"]);
    $desc = mysqli_real_escape_string($db, $desc);

    $photoID = $_POST["photoID"];
    $photoID = stripslashes($_POST["photoID"]);
    $photoID = htmlentities($_POST["photoID"]);
    $photoID = htmlspecialchars($_POST["photoID"]);
    $photoID = mysqli_real_escape_string($db, $photoID);

    $name = $_SESSION["username"];

    $sql="SELECT userID FROM users WHERE username='$name'";
    $result=mysqli_query($db,$sql);
    $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
    if(mysqli_num_rows($result) == 1) {
        //echo $name." ".$email." ".$password;
        $id = $row['userID'];
        $addsql = "INSERT INTO comments (description, postDate,photoID,userID) VALUES ('$desc',now(),'$photoID','$id')";
        $query = mysqli_query($db, $addsql) or die(mysqli_error($db));
        if ($query) {
            $msg = "Thank You! comment added. click <a href='photo.php?id=".$photoID."'>here</a> to go back";
        }
    }
    else{
        $msg = "You need to login first";
    }
}

?>