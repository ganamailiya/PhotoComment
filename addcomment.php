<?php
session_start();
//Establishing connection with our database
include("connection.php");
$mysqli = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
if(!$mysqli) die('Could not connect$: ' . mysqli_error());

//get the session variables

$name = $_SESSION["username"];
$userID=$_SESSION["userid"];
echo '$userID';
?>
<?php
$msg = ""; //Variable for storing our errors.

if(isset($_POST["submit"]))
{
    // Check Anti-CSRF token
    // checkToken( $_REQUEST[ 'user_token' ], $_SESSION[ 'session_token' ], 'index.php' );





    //Define & Sanitize description.
    $desc = $_POST["desc"];
    $desc = stripslashes( $desc );
    $desc=mysqli_real_escape_string($db,$desc);
    $desc = htmlspecialchars( $desc );
    $desc=xssafe($desc);

    //Define & Sanitize username.
    $name = $_SESSION["username"];
    $name = stripslashes( $name );
    $name=mysqli_real_escape_string($db,$name);
    $name = htmlspecialchars($name);
    $name=xssafe($name);

    //Define & Sanitize photoID.
    $photoID = $_POST["photoID"];
    $photoID = stripslashes( $photoID );
    $photoID=mysqli_real_escape_string($db,$photoID);
    $photoID = htmlspecialchars($photoID);
    $photoID=xssafe($photoID);

    if($userID >0) {
        //test connection
        if ($mysqli->connect_errno) {
            echo "Connetion Failed:check network connection";// to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }

        //call procedure
        if ( !( $stmt=$mysqli->prepare("INSERT INTO comments (description, photoID, userID) VALUES (?, ?, ?)")))  {
            echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }


        else{
            //bind parameter
            $stmt->bind_param('sii', $desc, $photoID, $userID);
            $stmt->execute();
            $result=1;

            if($result==1)

            $msg = "Thank You! comment added. click <a href='photo.php?id=".$photoID."'>here</a> to go back";
        }
    }
    else{
        $msg = "You need to login first";
    }
}

?>
