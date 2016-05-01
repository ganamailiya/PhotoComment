<?php
include ("connection.php");
$resultText = "";
if(isset($_POST["submit"]) && $_POST['my_token'] === $_SESSION['my_token']) {
    $name = htmlentities($_POST["username"]);
    $name = htmlspecialchars($name);
    $name = mysqli_real_escape_string($db, $name);

    $datam = $db->prepare('SELECT userID FROM users WHERE username= ?');
    $datam->bind_param('s', $name);


    if($datam->execute()){

        $rower = $datam->get_result();
        $rower = $rower->fetch_assoc();
        $searchID = $rower['userID'];

        $datam = $db->prepare('SELECT title, photoID FROM photos WHERE userID=?');
        $datam->bind_param('s', $searchID);
        $datam->execute();
        $rower = $datam->get_result();
        $rower = $rower->fetch_assoc();

        if(mysqli_num_rows($searchresult)>0){
            while($rower){
                $line = "<p><a href='photo.php?id=".$rower['photoID']."'>".$rower['title']."</a></p>";
                $resultText = $resultText.$line;
            }
        }
        else{
            $resultText = "no photos by user";
        }
    }
    else
    {
        $resultText = "no user with that username";

    }
}
?>