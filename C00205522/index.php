<?php 
	session_set_cookie_params(0, '/', 'localhost', true, true);
	session_start();

	include("functions.php");
	include("LoggedOutMenu.php");

	createDb();
	createTable();

?>

<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title> Secure Application </title>
		<link rel="stylesheet" href="app.css" />
	</head>
	<body>	
		<div id="body">
            <div id="main" align="center">
                <h1> Welcome to my secure application <br><br> Please log in or sign up...
                </h1>
            </div>
		</div>
	</body>
</html>

