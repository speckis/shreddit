<?php

require_once("header.php");
require_once("functions.php");

@$user = $_SESSION['userdata'];
$userid = $_GET['user'];
$info = userInfo($userid);
?>

<div class="facearea">
	<div class="avatar">
		<img src="<?php print $info['avatar']; ?>" alt="avatar" />
	</div> <!-- end avatar -->

	<h1><?php print $info['name']; ?></h1>
	<br>
	<a href="settings.php?user=<?php print $userid ?>">Ladda upp en bild</a><br><br>
</div> <!-- end facearea -->

<div class="profile-page">
	<h3><?php print $info['name'] . " har inte skrivit något på sin profil." ?></h3>
	</div> <!-- end profile-page -->

<?php
require_once("footer.php");