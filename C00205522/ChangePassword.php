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
		include("functions.php");
		
		if($_SERVER["REQUEST_METHOD"] == "POST") 
		{
			$user = $_SESSION['username'];
			$old = sanitize($_POST['oldpassword']);
			$new = sanitize($_POST['newpassword1']);
			$confirm = sanitize($_POST['newpassword2']);			
			
			$result = getUser($user, $db);
			$row = $result->fetch_assoc();		
			$oldSalt = $row['salt'];
			$oldHashedPassword = $row['password'];
			$newHashedPassword = hash('sha256', ("{$new} {$oldSalt}"));
				
			//check new password and confirm match
			if(strcmp($new, $confirm) != 0)
				{
					echo "<div class='errorstyle' align='center'> <h5> New Passwords Do Not Match! - Please Try Again 
					<input type='button' class='button' value='Back' onclick= 'window.location = \"changePassword.php\" '></h5> </div>";
					exit();
				}				
			//check the old password is correct
			else if(!compareOldPasswords($oldHashedPassword, $old, $oldSalt))
				{
					echo "<div class='errorstyle' align='center' > <h5> Old Password Incorrect! 
					<input type='button' class='button' value='Back' onclick= 'window.location = \"changePassword.php\" '></h5> </div>";
					exit();
				}
			//check new password is not old password
			else if(strcmp($oldHashedPassword,$newHashedPassword) == 0) 
				{
					echo "<div class='errorstyle' align='center'> <h5> New Password Cannot Be Old Password - Please Try Again 
					<input type='button' class='button' value='Back' onclick= 'window.location = \"changePassword.php\" '></h5> </div>";
					exit();
				}
			else 
				{
					//update users password 
					if(updatePassword($newHashedPassword, $user, $db) == 0)
						{
							echo "<div class='errorstyle' align='center'> <h5> Sorry - Update not successful! 
							<input type='button' class='button' value='Back' onclick= 'window.location = \"changePassword.php\" '></h5></div>";
							exit();
						}
					else
						{
							// destroy session and send to login page
							$_SESSION['username'] = null ;
							session_unset();
							session_destroy();
							echo "<div class='errorstyle' align='center'> <h5> Password update successful! 
							<input type='button' class='button' value='Login' onclick= 'window.location = \"login.php\" '></h5></div>";
							exit();
						}
				}
		}	
	}
?>

<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title> Change Password </title>
		<link rel="stylesheet" href="app.css" />
	</head>
	<body>
		<div id="body" align="center">
		<div id="form">
		<h1> Enter Password Details </h1>
			<form action = "" method = "post">
                <label>Old Password  </label><input type = "password" name = "oldpassword" title="No special characters must be between 8 and 20 characters long and contain an uppercase, lowercase and a number " pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,20}$" autocomplete='off' autofocus required  /><br/><br />
				<label>New Password  </label><input type = "password" name = "newpassword1" title="No special characters must be between 8 and 20 characters long and contain an uppercase, lowercase and a number " pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,20}$" autocomplete='off' required  /><br/><br />
				<label>Repeat New Password  </label><input type = "password" name = "newpassword2" title="No special characters must be between 8 and 20 characters long and contain an uppercase, lowercase and a number " pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,20}$" autocomplete='off' required  /><br/><br />
                <input type = "submit" value = " Submit "/><br />
        	</form>
		</div>
		</div>	
	</body>
</html>