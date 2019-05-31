<?php

function sanitize($str)
	{
		$str = str_replace('&', '&amp', $str);
		$str = str_replace('<', '&lt', $str);
		$str = str_replace('>', '&gt', $str);
		$str = str_replace('"', '&quot', $str);
		$str = str_replace("'", '&apos', $str);
		$str = str_replace(';', '&semi', $str);
		$str = str_replace('/', '&bsol', $str);
        $str = str_replace('\\', '&sol', $str);
        $str = str_replace('(', '&lpar', $str);
		$str = str_replace(')', '&rpar', $str);
		$str = str_replace('{', '&lcub', $str);
		$str = str_replace('}', '&rcub', $str);
		$str = str_replace('[', '&lsqb', $str);
		$str = str_replace(']', '&rsqb', $str);
		return $str;
}

    function createDb()
	{
        //create database
        $db = new mysqli('localhost:3306','root','');
		$sql = "CREATE DATABASE project1";
		if ($db->query($sql) === TRUE) {
			echo "<div align='center'><h5>Database created successfully</h5></div>";
		} else {
			echo "<div align='center'><h5>Database already exists</h5></div>";
		}
}

    function createTable()
    {
        include_once("config.php");
        //create users table
        $sql = "CREATE TABLE users (
			id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			username VARCHAR(20) NOT NULL,
			password VARCHAR(64) NOT NULL,
			salt VARCHAR(64) NOT NULL,
            locked INT(1) NOT NULL,
            locktime DATETIME NOT NULL
			)";
		if (mysqli_query($db, $sql)) {
			echo "<div align='center'><h5>Table users created successfully</h5></div>";
		} else {
			echo "<div align='center'><h5>Table already exists</h5></div>";
		}
}

    function compareOldPasswords($oldPHash, $oldP, $oldSalt)
    {
        //create new hashed password from input and salt 
        $newHash = hash('sha256', ("{$oldP} {$oldSalt}"));
        // check user entered correct old password
        if(strcmp($oldPHash, $newHash) == 0)
        {
            return true;
        }
        return false;
};

    function generateSalt() 
        {
		$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charsLength = strlen($chars);
		$newSalt = '';
		for ($i = 0; $i < 63; $i++) 
		{
			$newSalt .= $chars[rand(0, $charsLength - 1)];
		}
		return $newSalt;  
};


    function getUser($user, $db)
    {
        if (!($stmt = $db->prepare("SELECT * FROM users WHERE username=?")))
			{
				echo "Prepare failed: (" . $db->errno . ") " . $db->error;
				exit();
			}
		if (!$stmt->bind_param("s", $user))
			{
		    	echo "Binding parameters failed: (" . $db->errno . ") " . $db->error;
	    		exit();
			}
		if (!$stmt->execute()) 
			{
				echo "Execute failed: (" . $db->errno . ") " . $db->error;
				exit();
			}
		//now we have the users database data
        $result = $stmt->get_result();
        return $result;
}

    function incrementLock($username, $locks, $db)
    {
        $sql = "UPDATE users SET locked='$locks' WHERE username='$username'";
		//increment lock
        if (!mysqli_query($db, $sql)) 
        {
			echo "Error updating lock: " . mysqli_error($db);
		} 
        echo "<div class='errorstyle' align='center'> <h5> Sorry - Incorrect login details for $username <br></h5></div>";
}

    function lockOut($locks, $username, $db)
    {
        $sql = "UPDATE users SET locked='$locks', locktime=((now() + INTERVAL 5 MINUTE)) WHERE username='$username'";
		//set lock back to 0 and set a lockout time of 5 mins
        if (!mysqli_query($db, $sql)) 
        {
			echo "Error resetting lock and adding lockout: " . mysqli_error($db);
		} 
		echo "<div class='errorstyle' align='center'> <h5> Sorry - Too many attempts - Locking account for $username </h5></div>";
}

    function resetLocks($locks, $username, $db)
    {
        $sql = "UPDATE users SET locked='$locks' WHERE username='$username'";
		//set lock back to 0 
        if (!mysqli_query($db, $sql)) 
        {
			echo "Error resetting lock and adding lockout: " . mysqli_error($db);
		} 
}

    function checkNameTaken($username, $db)
    {
        if (!($stmt = $db->prepare("SELECT * FROM users WHERE username=?")))
            {
                echo "Prepare failed: (" . $db->errno . ") " . $db->error;
                exit();
            }
        if (!$stmt->bind_param("s", $username))
            {
                echo "Binding parameters failed: (" . $db->errno . ") " . $db->error;
                exit();
            }	
        if (!$stmt->execute()) 
            {
                echo "Execute failed: (" . $db->errno . ") " . $db->error;
                exit();
            }
        $result = $stmt->get_Result();
        if($stmt->affected_rows == 0) 
			{
                return true;
			}
        return false;
}

    function createUser($username, $hashedPassword, $salt, $db)
    {
        if (!($stmt = $db->prepare("INSERT into users (username, password, salt) VALUES (?, ?, ?)")))
			{
				echo "Prepare failed: (" . $db->errno . ") " . $db->error;
				exit();
			}
		if (!$stmt->bind_param("sss", $username, $hashedPassword, $salt))
			{
				echo "Binding parameters failed: (" . $db->errno . ") " . $db->error;
				exit();
			}
		if (!$stmt->execute()) 
			{
				echo "Execute failed: (" . $db->errno . ") " . $db->error;
				exit();
            }
        else
            {
                echo "<div class='errorstyle' align='center'><h5> New user added
                <input type='button' class='button' value='Login' onclick= 'window.location = \"login.php\" '> </h5></div>";
                exit();
            }
}

    function updatePassword($newHashedPassword, $user, $db)
    {
        if (!($stmt = $db->prepare("UPDATE users SET password=? WHERE username=?")))
		    {
				echo "Prepare failed: (" . $db->errno . ") " . $db->error;
				exit();
			}
		if (!$stmt->bind_param("ss", $newHashedPassword, $user))
			{
				echo "Binding parameters failed: (" . $db->errno . ") " . $db->error;
				exit();
			}	
		if (!$stmt->execute()) 
			{
				echo "Execute failed: (" . $db->errno . ") " . $db->error;
			    exit();
            }
        $result = $stmt->get_result();
        return $stmt->affected_rows;
}
?>