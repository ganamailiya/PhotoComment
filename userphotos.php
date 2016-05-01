<?php
$resultText = "";
if(isset($_SESSION['username']))
{
    $name = $_SESSION["username"];

    $sql= $db->prepare('SELECT userID FROM users WHERE username= ?');
    $result->bind_param('s',$sql);
    if($result->execute()) {

        $row = $result->get_result();
        $row = $row->fetch_assoc();

        $searchID = $row['userID'];
        $searchSql=$db->prepare('SELECT title, photoID,url FROM photos WHERE userID=?');
        $searchresult->bind_param('s',$searchSql);

        if($searchresult->execute()){
            $searchresult->get_result();
            $searchRow = $searchresult->fetch_assoc();
            while($searchRow){
                $line = "<p><img src='".$searchRow['url']."' style='width:100px;height:100px;'><a href='photo.php?id=".$searchRow['photoID']."'>".$searchRow['title']."</a></p>";
                $resultText = $resultText.$line;
            }
        }
        else{
            $resultText = "no photos by you!";
        }
    }
    else
    {
        $resultText = "no user with that username";

    }
}
?>