<?php

require_once ("config.php");
require_once ("header.php");
require_once ("functions.php");
checkSession();
?>
	<form class="form-style-9" style="width: 200px" action="login.php" method="POST">
		<ul>
		<li>
		<input type="text" name="user" placeholder="Username" class="field-style field-full align-none" />
		</li><br>
		<li>
		<input type="password" name="pwd" placeholder="Password" class="field-style field-full align-none" /><br><br>
		<input type="submit" value="Login" />
		</li>
		</ul>
	</form>


<?php

if($_SERVER["REQUEST_METHOD"] == "POST")
{
	$db = connectToDB();
	$user  = @$_POST["user"];
	$pwd   = @$_POST["pwd"];

	

	$query = "SELECT * FROM user WHERE `name` = '$user' AND `pwd` = '$pwd'";
	$result = mysqli_query($db, $query);

	$user = mysqli_fetch_assoc($result);


	if($user)
	{
		
		$_SESSION['userdata'] = $user;

		header('Location: index.php');
	}
	else
	{
		print "Invalid username or password";
	}

}

	require_once("footer.php");
