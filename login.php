<?php
session_start();
include("connection.php"); //Establishing connection with our database
$error = ""; //Variable for storing our errors.
// Check Anti-CSRF token
if(isset($_POST["submit"]) && $_POST['my_token'] === $_SESSION['my_token']) {

	// Define & Sanitize username
	$username = $_POST['username'];
	$username = stripslashes($username);
	$username = mysqli_real_escape_string($db, $username);

	// Define & Sanitize password
	$password = $_POST['password'];
	$password = stripslashes($password);
	$password = mysqli_real_escape_string($db, $password);

	//defence against brute force
	$total_failed_login = 3;
	$lockout_time = 300;
	$account_locked = 0;

	// Check the database (Check user information)
	$data = $db->prepare('SELECT failed_login, first_failed_attempt FROM users WHERE username = ?;');

	$data->bind_param('s', $username);
	$data->execute();
	$row = $data->get_result();
	$row = $row->fetch_assoc();

	// Check db to find out if user is locked out
	if (($data) && ($row['failed_login'] >= $total_failed_login)) {
		// User is locked out.
		echo "<pre><br />This account has been locked, max login attempt exceeded</pre>";

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

	// Check the database (if username matches the password)
	$datam = $db->prepare('SELECT * FROM users WHERE username = ? AND password = ?');
	$datam->bind_param('ss', $username, $password);

	// If its a valid login...
	if ($datam->execute() &&	$account_locked == 0) {
		$rower = $datam->get_result();
		$rower = $rower->fetch_assoc();
		// Get users details
		$failed_login = $rower['first_failed_attempt'];
		$last_login = $rower['failed_login'];


		// Reset bad login count
		$data = $db->prepare('UPDATE users SET failed_login_count = "0" WHERE username = ? LIMIT 1;');
		$data->bind_param('s', $username);
		$data->execute();
		// Login successful

		$_SESSION['username'] = $username; // Initializing Session
		$ip = $_SERVER['REMOTE_ADDR'];
		$_SESSION['ip'] = $ip;
		header("Location: photos.php"); // Redirecting To Other Page

	} else {
		// Login failed
		sleep(rand(2, 4));

		// Give the user some feedback
		echo "<pre><br />Username and/or password incorrect.<br />";
		// Update bad login count
		$data = $db->prepare('UPDATE users SET failed_login_count = (failed_login_count + 1) WHERE username = ? LIMIT 1;');
		$data->bind_param('s', $user);
		$data->execute();
	}

}
?>