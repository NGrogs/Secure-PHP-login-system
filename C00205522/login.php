<?php 
	include("config.php");
	session_start();
	
	include("LoggedOutMenu.php");
	include("functions.php");

	if(ISSET($_SESSION['username']))
	{
		//redirect to welcome page
		header("location: /welcome.php");
		exit();
	}
	
	if($_SERVER["REQUEST_METHOD"] == "POST") 
	{ 
		$username = sanitize($_POST['username']);
		$password = sanitize($_POST['password']); 
		
		//get the user
		$result = getUser($username, $db);
		//no user in db
		if($result->num_rows == 0)
		{
			echo "<div class='errorstyle' align='center'> <h5> Sorry - Incorrect login details for $username <br></h5></div>";
		}
		else
		{
			while ($row = $result->fetch_assoc())
			{
				//generate the users hash based of password
				$thisSalt = $row['salt'];
				$hashedPassword = hash("sha256", ("{$password} {$thisSalt}"));
				$thisPass = $row['password'];
				$locks = $row['locked'];
				$lockTime = $row['locktime'];
				$now = date("H:i:s",strtotime("now"));
				
				if($lockTime > $now)
					{
						echo "<div class='errorstyle' align='center'> <h5> Account locked for $username - try again later 
						<input type='button' class='button' value='Back' onclick= 'window.location = \"login.php\" '></h5></div>";
						exit();
					}
				if(strcmp($thisPass,$hashedPassword) == 0)
					{
						//successful login
						$_SESSION['username'] = $username;
						//reset locks
						$locks = 0;
						resetLocks($locks, $username, $db);
						header("location: /C00205522/welcome.php");	
						exit();
					}
				if($locks < 2)
					{
						$locks = $locks + 1;
						incrementLock($username, $locks, $db);
					}
				else
					{
						$locks = 0;
						lockOut($locks, $username, $db);
					}
			}
		}
	}
?>

<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title> Login </title>
	</head>
	<body>
		<div id="body" align="center">
		<div id="form">
		<h1> Login </h1>
			<form action = "" method = "post">
                <label>Username  </label><input type = "text" name = "username"  autocomplete='off' autofocus required /><br /><br />
                <label>Password  </label><input type = "password" name = "password" title="No special characters must be between 8 and 20 characters long and contain an uppercase, lowercase and a number" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,20}$" autocomplete='off' required /><br/><br />
                <input type = "submit" value = " Submit "/><br />
            </form>
		</div>
		</div>
	</body>
</html>