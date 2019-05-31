<?php 
	
	include("config.php");
	session_set_cookie_params(0, '/', 'localhost', true, true);
	session_start();

	include("LoggedOutMenu.php");
	include("functions.php");
	if(ISSET($_SESSION['username']))
	{
		header("location: /C00205522/welcome.php");
		exit();
	}
	
	if($_SERVER["REQUEST_METHOD"] == "POST") { 
		$username = sanitize($_POST['username']);
		$password = sanitize($_POST['password']); 
		$salt = generateSalt();
		$hashedPassword = hash("sha256", ("{$password} {$salt}"));
		// check if username is taken
			if(checkNameTaken($username, $db) == false)
			{
				echo "<div class='errorstyle' align='center'> <h5> Sorry - Please choose a different username or password
				<input type='button' class='button' value='Back' onclick= 'window.location = \"signup.php\" '> </h5></div>";
				exit();
			}
		else
			{
				createUser($username, $hashedPassword, $salt, $db);
			}	
	}
?>

<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title> Sign Up </title>
		<link rel="stylesheet" href="app.css" />
	</head>
	<body>
		<div id="body" align="center">
		<div id="form">
			<h1> Please fill out the below form to sign up </h1>
			<form action = "" method = "post">
				<label>UserName  </label><input type = "text" name = "username" title="characters must be between 4 and 20 characters long " pattern="{4,20}" autocomplete='off' required autofocus/> <br /><br />
                <label>Password  </label><input type = "password" name = "password" title="No special characters must be between 8 and 20 characters long and contain an uppercase, lowercase and a number " pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,20}$" autocomplete='off' required/> <br/><br /> 
                <input type = "submit" value = " Submit "/><br />
			</form>
		</div>
		</div>
	</body>
</html>

