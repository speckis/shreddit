<?php
require_once ("header.php");
require_once ("functions.php");

// index sidan som visar alla posts
// där man kan rösta
// samt komma till kommentar-sidan

checkSession();

if(isset($_SESSION['userdata'])) {
	// kallar på funktionerna så att man kan rösta
	if(isset($_GET['id'], $_GET['vote'])) {
		addVote2($_GET['vote'], $_GET['id'], 
			$_SESSION['userdata']['id']);
	}
}

?>
<h1 style="color: #00728B;">Shreddit, get it?</h1>
<div class="post-content-section">
		<?php getPost(); ?>				
	</div>
<div class="post-content-right">
	<div class="postingicons">
			<a href="postlink.php">Posta en länk!</a><br><br>
			<!-- <a href="posttext.php">Posta en tråd!</a>	 -->
	</div>
	<div class="postbox">
		<h1 style="color: #00728B; font-size: 20px;">Users</h1>
		<br>
		<?php postbox() ?>
	</div>
</div>

<?php
require_once ("footer.php");




