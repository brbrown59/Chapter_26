<?php
	//all the functions used by other parts of the project

	//DO NOT COMMIT THESE WITH VALUES ENTERED!!!!
	//log-in details for database
	$dbhost = 'localhost';
	$dbname = 'bbrown52';
	$dbuser = 'bbrown52';
	$dbpass = '';
	$appname = "The Project";

	//create connection to database
	$connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
	if ($connection ->connect_error) die($connection->connect_error);

	//called to create new tables in the database
	function createTable ($name, $query)
	{
		queryMysql("CREATE TABLE IF NOT EXISTS $name($query)");
		echo "Table $name created or already exists.<br>";
	}

	//called to make queries to the database
	function queryMysql($query)
	{
		global $connection;
		$result = $connection->query($query);
		if(!$result) die ($connection->error);
		return $result;

	}

	//destroy created sessions and cookies
	function destroySession()
	{
		$_SESSION = array();
		if (session_id() != "" || isset($_COOKIE[session_name()]))
			setcookie(session_name(), '', time()-2592000, '/');
		session_destroy();
	}

	//sanitize any inputs
	function santitizeString($var)
	{
		global $connection;
		$var = strip_tags($var);
		$var = htmlentities($var);
		$var = stripslashes($var);
		return $connection->real_escape_string($var);
	}

	//fetches profile of given user from database
	function showProfile ($user)
	{
		if (file_exists("user.jpg"))
			echo "<img src='user.jpg' style='float:left;>";
		$result = queryMysql("SELECT * FROM profiles WHERE user='$user'");
		if ($result->num_rows)
		{
			$row = $result->fetch_array(MYSQLI_ASSOC);
			echo stripslashes($row['text']) . "<br style='clear:left;'><br>";
		}
	}
?>