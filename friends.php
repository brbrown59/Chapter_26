<?php
	//file for showing a user's friends and followers
	require_once 'header.php';

	if (!$loggedin) die();

	//get the friends list to view;
	//determine if it's the user's friends or someone else's
	if (isset($_GET['view']))
		$view = sanitizeString($_GET['view']);
	else
		$view = $user;

	if ($view == $user)
	{
		$name1 = $name2 = "Your";
		$name3 =          "You are";
	}
	else
	{
		$name1 = "<a href='members.php?view=$view'>$view</a>'s";
		$name2 = "$view's";
		$name3 = "$view is";
	}

	echo "<div class='main'>";

	$followers = array();
	$following = array();

	//query database entry of the given user for his/her friends
	$result = queryMysql("SELECT * FROM friends WHERE user='$view'");
	$num    = $result->num_rows;

	//assign said followers to an array
	for ($j = 0 ; $j < $num ; ++$j)
	{
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$followers[$j] = $row['friend'];
	}

	//query the database for people with the user listed as a friend
	$result = queryMysql("SELECT * FROM friends WHERE friend='$view'");
	$num    = $result->num_rows;

	//assign those people to an array
	for ($j = 0 ; $j < $num ; ++$j)
	{
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$following[$j] = $row['user'];
	}

	//array combines both to get mutual followers
	$mutual    = array_intersect($followers, $following);
	//array for those who are only followers
	$followers = array_diff($followers, $mutual);
	//array for those who are only being followed
	$following = array_diff($following, $mutual);
	$friends   = FALSE;

	//code for displaying the arrays; if statements make sure they're not empty
	if (sizeof($mutual))
	{
		echo "<span class='subhead'>$name2 mutual friends</span><ul>";
		foreach($mutual as $friend)
			echo "<li><a href='members.php?view=$friend'>$friend</a>";
		echo "</ul>";
		$friends = TRUE;
	}

	if (sizeof($followers))
	{
		echo "<span class='subhead'>$name2 followers</span><ul>";
		foreach($followers as $friend)
			echo "<li><a href='members.php?view=$friend'>$friend</a>";
		echo "</ul>";
		$friends = TRUE;
	}

	if (sizeof($following))
	{
		echo "<span class='subhead'>$name3 following</span><ul>";
		foreach($following as $friend)
			echo "<li><a href='members.php?view=$friend'>$friend</a>";
		echo "</ul>";
		$friends = TRUE;
	}

	if (!$friends) echo "<br>You don't have any friends yet.<br><br>";

	echo "<a class='button' href='messages.php?view=$view'>" .
		"View $name2 messages</a>";
?>

</div><br>
</body>
</html>
