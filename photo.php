<?php
include("check.php");
include("connection.php");
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Home</title>
    <link rel="stylesheet" href="style.css" type="text/css" />
</head>

<body>
<h4>Welcome <?php echo $login_user;?> <a href="photos.php" style="font-size:18px">Photos</a>||<a href="searchphotos.php" style="font-size:18px">Search</a>||<a href="logout.php" style="font-size:18px">Logout</a></h4>
<div id="photo">
    <?php
    if(isset($_GET['id'])){
        $photoID = $_GET['id'];
        $data = $db->prepare("SELECT * FROM photos WHERE photoID= ? LIMIT 1;");
        $data->bind_param('s', $photoID);

        if($data->execute()){
            $rower = $data->get_result();
            $rower = $rower->fetch_assoc();
            echo "<h1>".$rower['title']."</h1>";
            echo "<h3>".$rower['postDate']."</h3>";
            echo "<img src='".$rower['url']."'/>";
            echo " <p>".$rower['description']."</p>";


            $commentSql=$db->prepare("SELECT * FROM comments WHERE photoID= ? LIMIT 1;");
            $commentSql->bind_param('s', $photoID);

            if($commentSql->execute()){
                $rower = $commentSql->get_result();
                $rower = $commentSql->fetch_assoc();

                echo "<h2> Comments </h2>";
                while($rower){
                    echo "<div class = 'comments'>";
                    echo "<h3>".$rower['postDate']."</h3>";
                    echo "<p>".$rower['description']."</p>";
                    echo "</div>";
                }

            }
            echo "<a href='addcommentform.php?id=".$photoID."'> Add Comment</a><br>";

            if($adminuser){
                echo "<div class='error'><a href='removephoto.php?id=".$photoID."'> Delete Photo</a></div>";
            }

        }
        else{
            echo "<h1>No Photos Found</h1>";
        }

    }
    else{

        echo "<h1>No User Selected</h1>";
    }

    ?>
</div>

</body>
</html>