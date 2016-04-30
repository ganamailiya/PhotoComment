<?php
session_start();
include("connection.php"); //Establishing connection with our database

$error = ""; //Variable for storing our errors.
if(isset($_POST["submit"]) && $_POST['my_token'] === $_SESSION['my_token']) {
	// Default values
	$total_failed_login = 3;
	$lockout_time = 6000;
	$account_locked = 0;
	if(empty($_POST["username"]) || empty($_POST["password"]))
	{
		$error = "Both fields are required.";
	}else
	{
		// Define, Sanitize Username & Password
		$username = $_POST['username'];
		$username = stripslashes($username);
		$username = mysqli_real_escape_string($db, $username);

		$password = $_POST['password'];
		$password = stripslashes($password);
		$password = mysqli_real_escape_string($db, $password);
		//$password = md5($password);

		$data = "SELECT failed_login, first_failed_attempt FROM users WHERE username='$username'";
		$run_data = mysqli_query($db, $data);
		$result = mysqli_fetch_array($run_data);
		if ((mysqli_num_rows($run_data) == 1) && ($result['failed_login'] >= $total_failed_login)) {

			// User is now locked out.
			echo "<pre><br />You have exceeded the max login attempt.</pre>";

			// Calculate when the user would be allowed to login again
			$last_login = $row['first_failed_attempt'];
			$last_login = strtotime($last_login);
			echo $last_login . "<br>";

			$timeout = strtotime("{$last_login} +{$lockout_time} minutes");
			$timenow = strtotime("now");
			echo $timeout . "<br>";
			// Check to see if enough time has passed, if it hasn't locked the account
			if ($timenow > $timeout)
				$account_locked = 1;

		}

		//Check username and password from database
		$sql="SELECT userID FROM users WHERE username='$username' and password='$password'";
		$result=mysqli_query($db,$sql);
		$row=mysqli_fetch_array($result,MYSQLI_ASSOC) ;

		//If username and password exist in our database then create a session, else echo an error msg.


		if(mysqli_num_rows($result) == 1) {
			$sql = "UPDATE users SET failed_login = 0 WHERE username = '$username' ";
			$run_sql = mysqli_query($db, $sql);
			$_SESSION['username'] = $username; // Initializing Session
			header("location: photos.php"); // Redirecting To Other Page
		}else {
			$sql1 = "UPDATE users SET failed_login = (failed_login + 1), first_failed_attempt = Now() WHERE username = '$username' ";
			mysqli_query($db, $sql1);
			$error = "Incorrect username or password.";
		}

	}
}

?>