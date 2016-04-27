<?php
ini_set('display_errors', 1);
error_reporting(~0);
	session_start();
	include("connection.php"); //Establishing connection with our database

	// Create connection
	$conn = new mysqli('eu-cdbr-azure-west-d.cloudapp.net', 'b2b2eb5a9bed89', '965d05ef', 'Uni1510537');
	$error = ""; //Variable for storing our errors.

if(isset($_POST["submit"]))
	{
		if(empty($_POST["username"]) || empty($_POST["password"]))
		{
			$error = "Both fields are required.";
		}else
		{
			// Define $username and $password
			$username=$_POST['username'];
			$password=$_POST['password'];

			// Check connection
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			// prepare and bind
			$stmt = $conn->prepare("SELECT userID FROM users WHERE username=? and password=?");
			$stmt -> bind_param('ss', $username, $password);

			//execute prepared query
			$stmt->execute();

			//Check username and password from database
			//$sql="SELECT userID FROM users WHERE username='$username' and password='$password'";
			//$result=mysqli_query($db,$stmt);
			//$row=mysqli_fetch_array($result,MYSQLI_ASSOC) ;
			$row = $stmt->fetch();

			echo '$row';
			//If username and password exist in our database then create a session.
			//Otherwise echo error.

			//if(mysqli_num_rows($result) == 1)
				//if( $stmt->rowCount() == true )
			//if($stmt->rowCount())
			if (1==1)
			{
				$_SESSION['username'] = $username; // Initializing Session
				header("location: photos.php"); // Redirecting To Other Page
			}
			else
			//elseif(!$stmt->rowCount())
			{
				$error = "Incorrect username or password.";
			}
			$stmt->close();
			$conn->close();
		}
	}
?>