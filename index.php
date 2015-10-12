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
		addVote($_GET['vote'], $_GET['id'], 
			$_SESSION['userdata']['id']);
	}
}

?>

<div class="post-content-section">
	<div class="showlist">
	<a class="item" href="index.php">Hot</a>
	<a class="item" href="Popular">Popular</a>
	<a class="item" href="Popular">Newest</a>
	<a class="item" style="float: right;" href="postlink.php">Shred</a>
	</div>
		<?php getPost(); ?>				
	</div>
<div class="post-content-right">
	<div class="searchbar">
		<form action="index.php" method="POST">
			<input type="text" name="search">
			<input type="submit">
		</form>
	</div>
	<div class="postbox">
		<?php searchBar();	 ?>
		<!-- <h1 style="color: #00728B; font-size: 20px;">Users</h1>
		<br>
		<?php userbox() ?> -->
	</div>
</div>

<?php
require_once ("footer.php");




