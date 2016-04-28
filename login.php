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
			// Sanitise password input
			$username=$_POST['username'];
			$username = stripslashes( $username );
			$username = mysqli_real_escape_string($db, $username );

			// Sanitise password input
			$password=$_POST['password'];
			$password = stripslashes( $password );
			$password = mysqli_real_escape_string($db, $password );
			$password = md5( $password );

			/** Check the database (if username matches the password)
			$data = $db->prepare( 'SELECT * FROM users WHERE username = (:username) AND password = (:password) LIMIT 1;' );
			$data->bind_Param( ':username', $username, PDO::PARAM_STR);
			$data->bind_Param( ':password', $password, PDO::PARAM_STR );
			$data->execute();
			$row = $data->fetch(); **/



			//Check username and password from database
			$sql="SELECT userID FROM users WHERE username='$username' and password='$password'";
			$result=mysqli_query($db,$sql);
			$row=mysqli_fetch_array($result,MYSQLI_ASSOC) ;
			
			//If username and password exist in our database then create a session.
			//Otherwise echo error.
			
			if(mysqli_num_rows($result) == 1)
			//if ( $data->rowCount() == 1 )
			{
				$_SESSION['username'] = $username; // Initializing Session
				header("location: photos.php"); // Redirecting To Other Page
			}
			else
			{
				$error = "Incorrect username or password.";
			}

		}
	}

?>