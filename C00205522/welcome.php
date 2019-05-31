<?php 
	include("config.php");
	session_set_cookie_params(0, '/', 'localhost', true, true);
	session_start();

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
		<meta charset="UTF-8">
		<title> Welcome </title>
		<link rel="stylesheet" href="app.css" />
	</head>
	<body>
		<div id="body">
			<div id="main" align="center">
				<h1> Welcome <?php print($_SESSION['username']) ?> </h1>
				<h2> only you can see these secret pages... <h2>
					<p><button class="button" onclick="location.href = 'secret1.php';"> Secret 1 </button></p>
					<p><button class="button" onclick="location.href = 'secret2.php';"> Secret 2 </button></p>
			</div>	 
		</div>
	</body>
</html>
