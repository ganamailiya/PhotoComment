<?php
session_start();

include("connection.php"); //Establishing connection with our database

$error = ""; //Variable for storing our errors.
if(isset($_POST["submit"]))
{
	if(empty($_POST["username"]) || empty($_POST["password"]))
	{
		$error = "Both fields are required.";
	}else
	{
		// Define & Sanitize username
		$username = $_POST['username'];
		$username = stripslashes($username);
		$username = mysqli_real_escape_string($db, $username);
		$username = htmlspecialchars($username);

		// Define & Sanitize password
		$password = $_POST['password'];
		$password = stripslashes($password);
		$password = mysqli_real_escape_string($db, $password);
		$password=md5($password);



		//Defence against SQLi
		$conn=new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
		if (!($conn->connect_errno)){
			//echo"connection Failed";
		}

		//prepare & bind statement.
		if($stmt=$conn->prepare("SELECT userID FROM users WHERE username=? and password=?")) {
			$stmt->bind_param('ss', $username, $password);
			$stmt->execute();

			$result = $stmt->get_result();

		}
		if (($row = $result->fetch_row())) {
			$_SESSION['username'] = $username; // Initializing Session
			$_SESSION["userid"] = $row[0];//user id assigned to session global variable
			$_SESSION["timeout"] = time();//get current session time
			$_SESSION["ip"] = $_SERVER['REMOTE_ADDR'];//get machine ip

			header("location: photos.php"); // Redirecting To Other Page
		}

		else{

			$error = "Incorrect username or password.";
		}
	}
}

?>