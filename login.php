<?php
	//file for logging in a pre-existing user
	require_once 'header.php';
	echo "<div class='main'></div><h3>Please enter your details to log in</h3>";
	$error = $user = $pass = "";

	//taking in info from the form and sanitizing
	if(isset($_POST['user']))
	{
		$user = sanitizeString($_POST['user']);
		$pass = sanitizeString($_POST['pass']);
		if ($user == "" || $pass == "")
		$error = "Not all fields were entered<br>";


		else
		{
			//if data succesefully entered, query database
			$result = queryMysql("SELECT user, pass FROM members WHERE user ='$user' and pass = '$pass'");

			//if not in DB, return error
			if ($result->num_rows == 0)
				$error = "<span class='error'>Username/Password invalid</span><br><br>";

			//else, assign session variables with the uname and pword
			else
			{
				$_SESSION['user'] = $user;
				$_SESSION['pass'] = $pass;
				//sets view to "user", which will display user profile upon call of members.php
				die("You are now logged in.  Please <a href='members.php?view=$user'>" . "click here</a> to continue.<br><br>");

			}
		}
	}
//code for the form
echo <<<_END
	<form method='post' action='login.php'>$error
	<span class='fieldname'>Username</span><input type='text'
	maxlength='16' name='user' value='$user'><br>
	<span class='fieldname'>Password</span><input type='password'
	maxlength='16' name='pass' value='$pass'><br>
_END;
?>
<br>
<span class ='fieldname'>&nbsp;</span>
<input type='submit' value='Login'>
</form><br></div>
</body>
</html>


