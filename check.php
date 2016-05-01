<?php
include('connection.php');
session_start();
session_regenerate_id();
$user_check=$_SESSION['username'];

$ses_sql = mysqli_query($db,"SELECT username, admin FROM users WHERE username='$user_check' ");

$row=mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);

$login_user = ($row['username']);
if($row['admin']==1){
    $adminuser = true;
}

if(!isset($user_check))
{
    header("Location: index.php");
}
//session time out
if (isset($_SESSION['timeout'])) {
    $logintime = $_SESSION['timeout'];
    $differenceintime = time() - $logintime;

    if ($differenceintime >= 5) { //session expiration
        session_unset();
        session_destroy();
        header("Location: index.php");
    }
}
else {
    $_SESSION['timeout'] = time();

}
?>