<?php
require_once("header.php");
require_once("functions.php");

// sidan där man skapa en post som visas på index-sidan
if(!isset($_SESSION['userdata'])) {
        header("Location: login.php");
    }

checkSession();

$db = connectToDB();
	if($_SERVER['REQUEST_METHOD'] == "POST") { 
		$rubrik = mysqli_real_escape_string($db, $_POST["rubrik"]);
		$link 	= mysqli_real_escape_string($db, $_POST["link"]);
		$rubrikOK = false;
		$linkOK = false;

		$error 	= [];
		if(empty($_POST['rubrik'])) {
			$error['rubrik'] = "You have to call your post something.";
		} else {
			$user = validateInput($_POST['rubrik']);
			$rubrikOK = true;
		}

		if(empty($_POST['link'])) {
			$error['link'] = "Well, you should post something, right?.";
		} else {
			$link = validateInput($_POST['link']);
		}
			if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$link)) {
      			$error['link'] = "Invalid URL"; 
    	} else {
    		$linkOK = true;
    	}

    	if($rubrikOK && $linkOK) {
    		$user = $_SESSION['userdata']['id'];
			$query = "INSERT INTO `posts`(`rubrik`, `link`, `userid`) 
			VALUES ('$rubrik', '$link', '$user')";
			$result = mysqli_query($db, $query);
			
			if ($result) {
				header("Location: index.php");
			}
		}
	}


?>
<form class="form-style-9" action="postlink.php" method="POST">
<ul>
<li><h1>Title</h1>
<input type="text" name="rubrik" class="field-style field-full align-none" />
<?php print @$error['rubrik']; ?></li><br>
<li><h1>Url</h1>
<input type="url" name="link" class="field-style field-full align-none" />
<?php print @$error['link']; ?></li>
<li>
<input type="submit" value="Post">
</li>
</ul>
</form>



<?php

require_once("footer.php");