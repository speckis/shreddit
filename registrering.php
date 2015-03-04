<?php

require_once ("config.php");
require_once ("header.php");
require_once ("functions.php");

$db = connectToDB();

$error = [];
$user = $pwd = $email = ""; 		

if($_SERVER['REQUEST_METHOD'] == "POST")
{
	if(empty($_POST['user'])) {
		$error['user'] = "Username is required.";
	} else {
		$user = validateInput(mysqli_real_escape_string($db, $_POST['user']));
	}
		if (!preg_match("/^[a-zA-Z ]*$/",$user)) {
      $error['user'] = "Only letters and white space allowed";
  		}

	if(empty($_POST['pwd'])) {
		$error['pwd'] = "Password is required.";
	} else {
		$pwd = validateInput(mysqli_real_escape_string($db, $_POST['pwd']));
	}

	if(empty($_POST['email'])) {
		$error['email'] = "Email is required.";
	} else {
		$email = validateInput(mysqli_real_escape_string($db, $_POST['email']));
	}
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error['email'] = "Invalid email format."; 
    	}

    if(!$error) {
	$query = "INSERT INTO user (name, pwd, email) VALUES ('$user', '$pwd', '$email')";
	$result = mysqli_query($db, $query);

	if(mysqli_errno($db)) {
		print mysqli_error($result);
	}
		if($query) {
			header('Location: login.php');
		}
	}
}

?>

<h3>Register and start your Shredding!</h3>

	<form class="form-style-9" action="<?php echo $_SERVER["PHP_SELF"];?>	" method="POST">
		Användarnamn:<br><input type="text" name="user"> <?php print @$error['user']; ?><br>
		Lösenord:<br><input type="password" name="pwd"> <?php print @$error['pwd']; ?><br>
		Email: <br><input type="email" name="email"> <?php print @$error['email']; ?><br>
		<input type="submit" value="Registrera!"><br>
	</form>

<?php

require_once ("footer.php");