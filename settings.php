<?php

require_once("header.php");
require_once("functions.php");

// user settings sidan där användare kan uppdatera sin profil
if(isset($_SESSION['userdata'])) {
	$userid = $_SESSION['userdata']['id'];
	$info = userInfo($userid);
}
?>

<div class="facearea">
	<div class="avatar">
		<img src="<?php print $info['avatar']; ?>" alt="avatar" />
	</div> <!-- end avatar -->
	
	<h1><?php print $_SESSION['userdata']['name']; ?></h1>
	<br>
	<a href="settings.php">Ladda upp en bild</a><br><br>
</div> <!-- end facearea -->

<div class="profile-page">
	<?php if($userid == $_GET['user']) {
			
			// validera registrering
			if(empty($_FILES['avatar']['name']) == false) {
				
				if(!$_FILES['avatar']['error']) {
					$new_file_name = strtolower($_FILES['avatar']['tmp_name']); 
					if($_FILES['avatar']['size'] > (1024000)) {
						$valid_file = false;
						$message = 'Oops!  Your file\'s size is to large.';
					}
					
					if($valid_file = true) {
						$currentdir = getcwd();
						$dest = getcwd() . "/avatar/" . $userid . ".jpg";
						move_uploaded_file($_FILES['avatar']['tmp_name'], $dest);
						updateProfile(empty($_FILES['avatar']['tmp']) ? false : $_FILES['avatar']['tmp']);
					}
				} else {
					$message = 'Ooops!  Your upload triggered the following error:  '.$_FILES['photo']['error'];
				}
			}

			// printa form till registrering
			print '<form method="POST" action="settings.php?user=' . $_GET['user'] . '" enctype="multipart/form-data">';
			print '<input type="file" name="avatar">';
			print '<input type="submit" value="Uppdatera">';
			print '</form>';
		} ?>
</div> <!-- end profile-page -->

<?php
require_once("footer.php");