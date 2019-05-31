<?php 

	include("config.php");
	session_set_cookie_params(0, '/', 'localhost', true, true);
	session_start();
	
	//check user is logged in
	if(!ISSET($_SESSION['username']))
	{
		echo "You must be logged in to view this page <br>
		<input type='button' class='button' value='Login' onclick= 'window.location = \"login.php\" '>" ;
		die();
	}
	else
	{
		include("LoggedInMenu.php");
	}
?>

<!doctype html>
<html>
	<head>
		<title> This is secret page 1 </title>
		<link rel="stylesheet" href="app.css" />
	</head>
	<body>
		<div id="body">
			<div id="main" align="center">
				<h1> This is secret page 1 </h1>
                <img src="Images/secret1.jpg" width='400px' height='400px'> 
			</div>	 
		</div>
	</body>
</html>
