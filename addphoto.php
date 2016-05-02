<?php
session_start();
include("connection.php"); //Establishing connection with our database

$ip=$_SESSION["ip"];

//Defence against session hijack.
if (!($ip==$_SERVER['REMOTE_ADDR'])){
    header("location: logout.php"); // Redirecting To Other Page
}

$msg = ""; //Variable for storing our errors.
if(isset($_POST["submit"]) ) {

    //Define & Sanitize photo title.
    $title = ($_POST["title"]);
    $title = htmlentities($_POST["title"]);
    $title = stripslashes($_POST["title"]);
    $title = htmlspecialchars($_POST["title"]);

    //Define & Sanitize photo description.
    $desc = $_POST["desc"];
    $desc = htmlentities($_POST["desc"]);
    $desc = stripslashes($_POST["desc"]);
    $desc = htmlspecialchars($_POST["desc"]);

    $url = "test";

    $name = $_SESSION["username"];


    //photo
    $dir = "uploads/";
    $uploadOk = 1;
    $file_name = $_FILES['fileToUpload']['name'];
    $file_loc = $_FILES['fileToUpload']['tmp_name'];
    $fileSize = $_FILES['fileToUpload']['size'];
    $fileType = $_FILES['fileToUpload']['type'];
    $target_file = $dir . basename($file_name);


    // Check if file already exists

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

    $expensions= array("jpeg","jpg","png","gif");

    if(in_array($file_ext,$expensions)=== false){
        $errors[]="Please choose a .JPEG or a .PNG file, Thank You!";
        $uploadOk = 0;
    }
// Check if $uploadOk is set to 0 by an error
    $fp = fopen($file_loc, 'r');
    $content = fread($fp, filesize($file_loc));
    $content = addslashes($content);
    fclose($fp);
    $data1 = $db->prepare('SELECT userID FROM users WHERE username= ?') or trigger_error($db->error, E_USER_ERROR);
    $data1->bind_param('s', $name);
    if ($data1->execute() && $uploadOk == 1) {
        $row1 = $data1->get_result();
        $row1 = $row1->fetch_assoc();
        $id = $row1['userID'];

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


}
else{
    $msg = "You need to login first";
}
?>