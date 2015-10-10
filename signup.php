<?php
//program for signing up a new user
require_once 'header.php';

echo <<<_END
	<script>
		//first check if user already exists
		//accomplised by using ajax to call the check user program in the background
		function checkUser(user)
		{
			//clears the field
			if (user.value == '')
			{
				//function defined in javascript.js
				O('info').innerHTML = '';
				return;
			}

			//set up ajax request
			params = "user=" + user.value;
			request = new ajaxRequest();
			request.open("POST", "checkuser.php", true);
			request.setRequestHeader("Content-type", params.length);
			request.setRequestHeader("Connection", "close");

			request.onreadystatechange = function();
			{
				if (this.readyState == 4) //completed call
					if (this.status == 200) //successful call
						if (this.responseText != null)
							O('info').innerHTML = this.responseText; //result of ajax calls
			}
			request.send(params)
		}

		function ajaxRequest()
		{
			try
			{
				var request = new XMLHttpRequest()
			}
			catch(e1)
			{
				try {request = new ActiveXObject("Msxml2.XMLHTTP")}
				catch(e2)
				{
					try {request = new ActiveXObject("Microsoft.XMLHTTP")}
					catch(e3)
					{
						request = false;
					}
				}
			}
			return request;
		}
		</script>
		<div class = 'main'><h3>Please enter your details to sign up</h3>

_END;

$error = $user = $pass = "";
if (isset($_SESSION['user'])) destroySession();

//sanitizes input
if (isset($_POST['user']))
{
	$user = santitizeString($_POST['user']);
	$pass = santitizeString($_POST['pass']);
	if ($user == "" || $pass == "")
		$error = "Not all fields were entered<br><br>";
	else
	{
		//query the database to see if the username already exists
		$result = queryMysql("SELECT * FROM members WHERE user ='$user'");
		if ($result->num_rows)//should evaluate to 0 (false) if not in DB
			$error = "That username already exists<br><br>";
		else
		{
			//if passed above, insert info into DB
			queryMysql("INSERT INTO members VALUES('$user', '$pass')");
			die("<h4>Account created</h4>Please Log In.<br><br>");
		}
	}
}
echo <<<_END
<!--sets up the actual form -->
	<form method='post' action='signup.php'>$error
	<span class = 'fieldname'>Username</span>
	<input type='text' maxlength='16' name='user' value='$user'
	onBlur='checkUser(this)'><span id ='info'></span><br>
	<span class ='fieldname'>Password</span>
	<input type = 'password' maxlength='16'name ='pass'
	value='$pass'><br>

_END;
?>

<span class='fieldname'>&nbsp;</span>
<input type='submit' value='Sign up'>
</form></div><br>
</body>
</html>
