<?php
include ("connection.php");
$msg = "";
if(isset($_POST["submit"])) {
    //define & sanitize name, email & password.
    $name = $_POST["username"];
    $name = htmlentities($name);
    $name = stripslashes($name);
    $name = htmlspecialchars($name);
    $name = mysqli_real_escape_string($db, $name);

    //define & sanitize email
    $email = $_POST["email"];
    $email = htmlentities($email);
    $email = stripslashes($email);
    $email = htmlspecialchars($email);
    $email = mysqli_real_escape_string($db, $email);

    //define & sanitize password
    $password = $_POST["password"];
    $password = mysqli_real_escape_string($db, $password);
    $password = password_hash($password, PASSWORD_BCRYPT);

    //check if e-mail exists
    $data = $db->prepare('SELECT email FROM users WHERE email = ? ');
    $data->bind_param('s', $email);

    if($data->execute()) {
        $msg = "Sorry...This email already exists...";
    }
    else
    {
        //prepare and bind user input
        $query = $db->prepare("INSERT INTO users (username, email, password) VALUES ('?', '?', '?')")
        or die(mysqli_error($db));
        $query->bind_param('sss', $name, $password,$email);

        if($query->execute()) {
            $msg = "Thank You! you are now registered. click <a href='index.php'>here</a> to login";
        }

    }
}
?>