<?php
include ("connection.php");
$resultText = "";
if(isset($_POST["submit"]) && $_POST['my_token'] === $_SESSION['my_token']) {
    $name = htmlentities($_POST["username"]);
    $name = htmlspecialchars($_POST['username']);
    $name = mysqli_real_escape_string($db, $name);

    $data = $db->prepare('SELECT userID FROM users WHERE username= ?');
    $data->bind_param('s', $name);


    if($data->execute()){

        $rower = $data->get_result();
        $rower = $rower->fetch_assoc();
        $searchID = $rower['userID'];

        $data = $db->prepare('SELECT title, photoID FROM photos WHERE userID=?');
        $data->bind_param('s', $searchID);
        $data->execute();
        $rower = $data->get_result();
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