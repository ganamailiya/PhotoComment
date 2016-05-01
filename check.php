<?php
include ("connection.php");
include ("login.php");
session_start();
if (isset($_SESSION['username'])) {
    $user_check=$_SESSION['username'];
    $ip_add = $_SESSION['ip'];
    $ses_sql = mysqli_query($db,"SELECT username, admin FROM users WHERE username='$user_check' LIMIT 1;");

    $row=mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);

    $login_user=$row['username'];
    if($row['admin']==1){
        $adminuser = true;
    }

    if(!isset($user_check))
    {
        header("location: index.php");
    }

    if ($_SESSION['timeout'] + 60 < time()) {
        session_destroy();
        header("location: index.php");
    }
    else {
        $_SESSION['timeout'] = time();
    }
    if ($ip_add!== $_SERVER['REMOTE_ADDR']){
        header("location: index.php");

    }
}

?>