<?php
	session_set_cookie_params(0, '/', 'localhost', true, true);
	session_start(); 
	//check user is logged in
	if(!ISSET($_SESSION['username']))
	{
		echo "You must be logged in to view this page <br>
		<input type='button' class='button' value='Login' onclick= 'window.location = \"login.php\" '>" ;
		exit();
	}
	else
	{
		include("LoggedInMenu.php");
		$_SESSION['username'] = null ;
		session_unset() ;
		session_destroy();
		header("Location: login.php");
		exit();
	}
?>

<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title> Logout </title>
		<link rel="stylesheet" href="app.css" />
	</head>
	<body>
		<div id="body">
			<h1> Logging out... </h1>
			<p> Please wait to be redirected </p>
		</div>
	</body>
</html>