<?php

$msg = ""; //variable for storing errors.

include("connection.php"); //Establishing connection with our database
$mysqli = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

//Defence against XSS
function xss_cleaner($input_str) {
    $return_str = str_replace( array('<','>',"'",'"',')','('), array('&lt;','&gt;','&apos;','&#x22;','&#x29;','&#x28;'), $input_str );
    $return_str = str_ireplace( '%3Cscript', '', $return_str );
    return $return_str;
}
if(!$mysqli) die('Could not connect$: ' . mysqli_error());

if(isset($_POST["submit"]))
{
    //Define & Sanitize username
    $name = $_POST["username"];
    $name=mysqli_real_escape_string($db,$name);
    $name = stripslashes( $name );
    $name = htmlspecialchars($name);
    $name=xss_cleaner($name);


    //Define & Sanitize password
    $password = $_POST["password"];
    $password=md5($password);

    //Define & Sanitize email
    $email = $_POST["email"];
    $email = stripslashes( $email );
    $email=mysqli_real_escape_string($db,$email);
    $email = htmlspecialchars($email);
    $email=xss_cleaner($email);

    //Confirm user's choice of email doesn't already exist.
    $sql="SELECT email FROM users WHERE email='$email'";
    $result=mysqli_query($db,$sql);
    $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
    if(mysqli_num_rows($result) == 1)
    {
        $msg = "Sorry...This email already exists...";
    }
    else
    {
        if ($mysqli->connect_errno) {
            echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }

        //Prepare Statement for binding.
        if ( !( $stmt=$mysqli->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)")))  {
            echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        //bind parameter
        $stmt->bind_param('sss', $name, $email, $password);
        $stmt->execute();
        $result=1;

        if($result==1)
        {
            $msg = "Thank You! you are now registered. click <a href='../../index.php'>here</a> to login";
        }

    }
}
?>