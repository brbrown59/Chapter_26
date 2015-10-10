<?php
//program for creating a profile
	require_once 'header.php';

	if (!$loggedin) die();

	echo "<div class='main'><h3>Your Profile</h3>";

	//fetch profile from database
	$result = queryMysql("SELECT * FROM profiles WHERE user='$user'");

	//if text was entered, sanitize, strip slashes, and insert into db
	if (isset($_POST['text']))
	{
		$text = sanitizeString($_POST['text']);
		$text = preg_replace('/\s\s+/', ' ', $text);

		if ($result->num_rows)
			queryMysql("UPDATE profiles SET text='$text' where user='$user'");
		else queryMysql("INSERT INTO profiles VALUES('$user', '$text')");
	}
	else
	{
		if ($result->num_rows)
		{
			$row  = $result->fetch_array(MYSQLI_ASSOC);
			$text = stripslashes($row['text']);
		}
		else $text = "";
	}

	$text = stripslashes(preg_replace('/\s\s+/', ' ', $text));

	//if an image is uploaded, save it
	if (isset($_FILES['image']['name']))
	{
		$saveto = "$user.jpg";
		move_uploaded_file($_FILES['image']['tmp_name'], $saveto);
		$typeok = TRUE;

		//only save image if in a proper format
		switch($_FILES['image']['type'])
		{
			case "image/gif":   $src = imagecreatefromgif($saveto); break;
			case "image/jpeg":  // Both regular and progressive jpegs
			case "image/pjpeg": $src = imagecreatefromjpeg($saveto); break;
			case "image/png":   $src = imagecreatefrompng($saveto); break;
			default:            $typeok = FALSE; break;
		}

		//accessed only if type is okay
		if ($typeok)
		{
			list($w, $h) = getimagesize($saveto);

			$max = 100;
			$tw  = $w;
			$th  = $h;

			if ($w > $h && $max < $w)
			{
				$th = $max / $w * $h;
				$tw = $max;
			}
			elseif ($h > $w && $max < $h)
			{
				$tw = $max / $h * $w;
				$th = $max;
			}
			elseif ($max < $w)
			{
				$tw = $th = $max;
			}

			$tmp = imagecreatetruecolor($tw, $th);
			imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
			imageconvolution($tmp, array(array(-1, -1, -1),
				array(-1, 16, -1), array(-1, -1, -1)), 8, 0);

			//save the file
			imagejpeg($tmp, $saveto);

			//return memory used for image processing, after image file saved
			imagedestroy($tmp);
			imagedestroy($src);
		}
	}
	//shows the current profile
	showProfile($user);


//multipart allows more than one form of data to be sent at once
echo <<<_END


  	  <form method='post' action='profile.php' enctype='multipart/form-data'>
  	  <h3>Enter or edit your details and/or upload an image</h3>
  	  <textarea name='text' cols='50' rows='3'>$text</textarea><br>
_END;
?>

Image: <input type='file' name='image' size='14'>
<input type='submit' value='Save Profile'>
</form></div><br>
</body>
</html>
