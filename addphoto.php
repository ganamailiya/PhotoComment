<?php
session_start();
include("connection.php"); //Establishing connection with our database
$msg = ""; //Variable for storing our errors.
if(isset($_POST["submit"]) ) {
    $title = ($_POST["title"]);
    $title = htmlentities($_POST["title"]);
    $title = stripslashes($_POST["title"]);
    $title = htmlspecialchars($_POST["title"]);
    $desc = $_POST["desc"];
    $desc = htmlentities($_POST["desc"]);
    $desc = stripslashes($_POST["desc"]);
    $desc = htmlspecialchars($_POST["desc"]);
    $url = "test";
    $name = $_SESSION["username"];
    $dir = "uploads/";
    $uploadOk = 1;
    $file_name = $_FILES['fileToUpload']['name'];
    $file_loc = $_FILES['fileToUpload']['tmp_name'];
    $fileSize = $_FILES['fileToUpload']['size'];
    $fileType = $_FILES['fileToUpload']['type'];
    $target_file = $dir . basename($file_name);


    // Check if file already exists
    /*if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }*/

    $check = getimagesize($file_loc);
    if ($check ===false) {
        echo "file is not an image";
        $uploadOk = 0;
    }

    // Check file size
    if ($fileSize > 100000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
// Allow certain file formats
    $file_ext=strtolower(end(explode('.',$file_name)));

    $expensions= array("jpeg","jpg","png");

    if(in_array($file_ext,$expensions)=== false){
        $errors[]="extension not allowed, please choose a JPEG or PNG file.";
        $uploadOk = 0;
    }
// Check if $uploadOk is set to 0 by an error
    $fp = fopen($file_loc, 'r');
    $content = fread($fp, filesize($file_loc));
    $content = addslashes($content);
    fclose($fp);
    $datam = $db->prepare('SELECT userID FROM users WHERE username= ?') or trigger_error($db->error, E_USER_ERROR);
    $datam->bind_param('s', $name);
    if ($datam->execute() && $uploadOk == 1) {
        $rower = $datam->get_result();
        $rower = $rower->fetch_assoc();
        $id = $rower['userID'];
        //$timestamp = time();
        //$target_file = $target_file.$timestamp;
        move_uploaded_file($file_loc, $target_file);
        $timenow = strtotime(time, now);
        $query = $db->prepare("INSERT INTO photos (title, description, postDate, url, userID)
        VALUES (?, ?, ?, ?, ?)");
        $query->bind_param('ssisi', $title, $desc, $timenow, $target_file, $id);

        if ($query->execute()) {
            $msg = "Thank You! The file ". basename( $file_name). " has been uploaded. click <a href='photos.php'>here</a> to go back";
        }

    } else {
        $msg = "Sorry, there was an error uploading your file.";
    }
    //echo $name." ".$email." ".$password;


}
else{
    $msg = "You need to login first";
}
?>