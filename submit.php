<?php
include ("connection.php");
$msg = "";
if(isset($_POST["submit"])) {
    $name = $_POST["username"];
    $name = htmlentities($name);
    $name = stripslashes($name);
    $name = htmlspecialchars($name);
    $name = mysqli_real_escape_string($db, $name);
    $timenow = strtotime(time, now);
    echo  $timenow . "<br>";


    $email = $_POST["email"];
    $email = htmlentities($email);
    $email = stripslashes($email);
    $email = htmlspecialchars($email);
    $email = mysqli_real_escape_string($db, $email);

    $password = $_POST["password"];
    $password = mysqli_real_escape_string($db, $password);
    $password = password_hash($password, PASSWORD_BCRYPT);

    $datam = $db->prepare('SELECT email FROM users WHERE email = ? ');
    $datam->bind_param('s', $email);
    $datam->execute();
    $rower = $datam->get_result();
    $rower = $rower->fetch_assoc();

    if($rower) {
        $msg = "Sorry...This email already exists...";
    }
    else {
        //echo $name." ".$email." ".$password;
        $query = $db->prepare('INSERT INTO users (username, email, password, first_failed_attempt) VALUES (?, ?, ?, ?)');
        /* if (false === $query) {
             die ('prepare() failed: ' . $db->error);
         }*/
        $query->bind_param('sssi', $name, $email, $password, $timenow);
        /*if (false === $result) {
            die('bind_param() failed');
        }*/

        if($query->execute()) {
            $msg = "Thank You! you are now registered. click <a href='index.php'>here</a> to login";
        }
        else {
            $msg = "Try again";
        }

        /*$result = $query->execute();
        if (false === $result) {
            die('execute() failed: ' . $db->error);
        }*/
    }

}
?>