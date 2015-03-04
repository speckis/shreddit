<?php
require_once("header.php");
require_once("functions.php");

checkSession();
addComment();
getComment();
$postid = $_GET['id']; 
?>


<?php
	if(isset($_SESSION['userdata']))
	{ ?>
Skicka en kommentar:
<form action="" method="POST">
	<input type="hidden" name="postid" value="<?php print $postid ?>">
	<textarea name="comment"></textarea>
	<input type="submit">
</form>
<?php
}
else
{
	print 'Du måste vara <a href="login.php">inloggad</a> för att kommentera!';
}

require_once("footer.php");

