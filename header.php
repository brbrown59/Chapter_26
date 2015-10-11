<?php

	//set up session; will remember values to be shared across files
	session_start();

	//these tags are closed by each of the other files in this project as needed
	echo "<!DOCTYPE html>\n<html><head>";

	//makes sure all files have access to the functions
	require_once 'functions.php';

	$userstr = ' (Guest)';

	//check if a user is logged in by checking if the session ID variable has a value
	if (isset($_SESSION['user']))
	{
		$user = $_SESSION['user'];
		$loggedin = TRUE;
		$userstr = " ($user)";
	}
	else $loggedin = FALSE;

//loads style sheet, creates canvas element for logo and loads javascript file
	echo "<title>$appname$userstr</title><link rel='stylesheet' " .
		"href='styles.css' type='text/css'>"                     .
		"</head><body><center><canvas id='logo' width='624' "    .
		"height='96'>$appname</canvas></center>"             .
		"<div class='appname'>$appname$userstr</div>"            .
		"<script src='javascript.js'></script>";

	//present different options depending if user is logged in or not
	if ($loggedin)
	{
		echo "<br ><ul class='menu'>" .
			"<li><a href='members.php?view=$user'>Home</a></li>" .
			"<li><a href='members.php'>Members</a></li>"         .
			"<li><a href='friends.php'>Friends</a></li>"         .
			"<li><a href='messages.php'>Messages</a></li>"       .
			"<li><a href='profile.php'>Edit Profile</a></li>"    .
			"<li><a href='logout.php'>Log out</a></li></ul><br>";
	}
	else
	{
		echo ("<br><ul class='menu'>" .
			"<li><a href='index.php'>Home</a></li>"                .
			"<li><a href='signup.php'>Sign up</a></li>"            .
			"<li><a href='login.php'>Log in</a></li></ul><br>"     .
			"<span class='info'>&#8658; You must be logged in to " .
			"view this page.</span><br><br>");
	}
?>