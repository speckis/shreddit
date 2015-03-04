<?php
require_once("config.php");
session_start();

function checkSession() {
	if(!isset($_SESSION)) {
		session_start();
	}
}

// kolla om du är inloggad
function loggedIn() {
	$db = connectToDB();
	$query = "SELECT * FROM user";
	$result = mysqli_query($db, $query);
	if(mysqli_errno($db)) {
	 	print mysqli_error($result);
	} 
	$get = mysqli_fetch_assoc($result);
	// $userid = $get['id']; 

	@$userid = $_SESSION['userdata']['id'];
	
	if(isset($userid)) {
		print '<a class="item" href="profile.php?user=' . $userid . '">' . $_SESSION['userdata']['name'] . '</a>';
		print "\n"; 
		print '<a class="item" href="logout.php">Logout</a>';
	} else {
		print '<a class="item" href="registrering.php">Register</a>';
        print '<a class="item" href="login.php">Login</a>';
	}
}

// hämta info för varje användare
function userInfo($userid) {
	$db = connectToDB();
	$query  = "SELECT 
				`id`,
				`name`,
				`email` 
				FROM user 
				WHERE id = {$userid}";
	$result = mysqli_query($db, $query);
	if(mysqli_errno($db)) {
	 	print mysqli_error($result);
	} 
	$info = mysqli_fetch_assoc($result);

	$info['avatar'] = "avatar/{$info['id']}.jpg";

	return $info;
}

// user settings, där användare kan uppdatera sin profil
// just nu mest för att ladda upp en bild
function updateProfile($avatar) {
	$db = connectToDB();

	$userid = (int)$_SESSION['userdata']['id'];

	if (file_exists($avatar)) {
		$src_size = getimagesize($avatar);

		if ($src_size['mime'] === 'image/jpeg'){
			$src_img = imagecreatefromjpeg($avatar);
		}else if ($src_size['mime'] ==='image/png'){
			$src_img = imagecreatefrompng($avatar);
		}else if ($src_size['mime'] ==='image/gif'){
			$src_img = imagecreatefromgif($avatar);
		}else{
			$src_img = false;
		}

		if ($src_img !== false){
			$thumb_size = 200;

			if ($src_size[0] <= $thumb_size){
				$thumb = $src_img;
			}else{
				$new_size[0] = $thumb_size;
				$new_size[1] = $thumb_size;

				$thumb = imagecreatetruecolor($new_size[0], $new_size[1]);
				imagecopyresampled($thumb, $src_img, 0, 0, 0, 0, 
						$new_size[0], $new_size[1], $src_size[0], $src_size[1]);
			}
		}
	}
}

// hämta all post/inlägg information
function postData() {
	$db = connectToDB();
	$query = "SELECT * FROM posts";
	$result = mysqli_query($db, $query);
	if(mysqli_errno($db)) {
		print mysqli_error($result);
	} 
	$row = mysqli_fetch_assoc($result);
}

// funktions-jätten, hämtar och lägger upp posts,
// samt även hanterar designen för post-boxen, kallad post-content
function getPost() {
		
		// hämtar post informationen som behövs
		$db = connectToDB();
		$query = "SELECT 
					posts.id, 
					rubrik, 
					link, 
					name, 
					userid, 
					posttime
				  FROM `posts` 
				  INNER JOIN `user` 
				  ON posts.userid = user.id
				  ORDER BY vote / DATEDIFF(NOW(), posttime) DESC";
		$result = mysqli_query($db, $query);
		if(mysqli_errno($db)) {
	 		print mysqli_error($result);
	 	}
		while($row = mysqli_fetch_assoc($result)) {
	
			$postid = (int)$row['id'];
			$name 	= mysqli_real_escape_string($db, $row['name']);
			$time 	= $row['posttime'];
			$link 	= mysqli_real_escape_string($db, $row['link']);
			$title 	= mysqli_real_escape_string($db, $row['rubrik']);
			$userid = (int)$row['userid'];

			// startar post-content diven där posts visas
			print '<div class="post-content">';
			// visar rubrik, och gör den klickbar	
			print "<h1><a href=" . $link . ">". $title . "</a></h1><br>";
			print '<h2>Skickat av <a href="profile.php?user=' . $userid . '">' . $name . '</a> ' . $time . '</h2>';

			// hämtar och lägger ut information angående postens kommentarer
			$comment  = "SELECT 
							COUNT(id) 
						 FROM comments 
						 WHERE postid = {$postid}";
			$rcomment = mysqli_query($db, $comment);
			if(mysqli_errno($db)) {
	 			print mysqli_error($rcomment);
	 		} 
			$row = mysqli_fetch_array($rcomment);
			print '<h3>' . $row[0] . ' <a href="comments.php?id=' . $postid . '">comments</a></h3>';
			
			// startar votearea diven, där man kommer kunna rösta och se antalet röster
			print '<div class="votearea">';
	
			if(isset($_SESSION['userdata'])) {
				$sessID = $_SESSION['userdata']['id'];
				$vote = "SELECT 
							vote, 
							postid, 
							userid 
						 FROM vote_id 
						 WHERE postid = {$postid} 
						 AND userid = {$sessID}";
				$rvote = mysqli_query($db, $vote);
				if(mysqli_errno($db)) {
	 				print mysqli_error($rvote);
	 			} 
				$vrow = mysqli_fetch_assoc($rvote);
		
				if($vrow > 1) {	
					print '<img src="img/plusvoted.png" 
						alt="voteup" />';
					print '<img src="img/minusvoted.png" 
						alt="votedown" />';
				} else {	
				print '<a href="?vote=up&amp;id=' . $postid . '"><img src="img/plus2.png" 
					alt="voteup"/></a>';
				print '<a href="?vote=down&amp;id=' . $postid . '"><img src="img/minus2.png" 
					alt="votedown" /></a>';
				}
			}
			$votecount = "SELECT 
							vote 
						  FROM posts 
						  WHERE id = {$postid}";
			$votes = mysqli_query($db, $votecount);

			if(mysqli_errno($db)) {
	 			print mysqli_error($votes);
	 		} 
			$count = mysqli_fetch_assoc($votes);

			print '<p>'. $count['vote'] . '</p>';
			print '</div>';
			print "</div><br>";
		}		 
}
// lägger in kommentarer i databasen
function addComment() {
	$db = connectToDB();

	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		$comment = mysqli_real_escape_string($db, $_POST['comment']);
		$user = (int)$_SESSION['userdata']['id'];
		$postid = (int)$_POST["postid"];

		$query = "INSERT INTO 
						comments (comments, userid, postid) 
				  VALUES ('$comment', '$user', '$postid')";
		$result = mysqli_query($db, $query); 

		if(mysqli_errno($db)) {
	 	print mysqli_error($result);
	 	} 
	}
}

// hämtar kommentarer från databasen och lägger upp dem
function getComment() {
	$db = connectToDB();

	$query  = "SELECT 
					comments.id, 
					comments, name, 
					`comment-time`, 
					postid, 
					userid 
			   	FROM comments
				INNER JOIN user 
				ON comments.userid = user.id
				WHERE postid = '{$_GET["id"]}'";
	$result = mysqli_query($db, $query);
	 
	if(mysqli_errno($db)) {
	 	print mysqli_error($result);
	} 

	while($row = mysqli_fetch_assoc($result)) {
		
		$userid = (int)$row['userid'];	
		$name 	= mysqli_real_escape_string($db, $row['name']);
		$time 	= $row['comment-time'];	
		print '<div class="comment-box">';
		print '<h2><a href="profile.php?user=' . $userid . '">' . $name . '</a> ' . $time . '</h2>';
		print "<h1>" . $row['comments'] . "<br></h1>";
		print "</div><br>";
	}
}

function postbox() 
{
	 $db = connectToDB();
	 $query = "SELECT 
	 				*
	 			FROM user  
	 			ORDER BY id DESC"; 
	 $result = mysqli_query($db, $query);

	 if(mysqli_errno($db)) {
	 	print mysqli_error($result);
	 } 

	 while($row = mysqli_fetch_assoc($result)) 
	 { 
	 
		print '<h1><a href="profile.php?user=' . $row['id'] . '">' . $row['name'] . '</a><br><br></h1>'; 
	 }
}

function addVote2($vote, $postid, $userid){
	$db = connectToDB();
	$vote 	= $_GET['vote'];
	$postid = $_GET['id'];
	$userid = $_SESSION['userdata']['id'];

	$query 	= "INSERT IGNORE INTO vote_id (vote, postid, userid) 
			VALUES ('1', '{$postid}', '{$userid}')";	
	$result = mysqli_query($db, $query);

	$vote = ($vote === 'up') ? '+' : '-';

	$votecount = "UPDATE posts SET vote = vote {$vote} 1 WHERE id = {$postid}";
	$vres = mysqli_query($db, $votecount);
	header('Location: index.php');		
}

// validerar inputs i registreringen
function validateInput($input) {
	$input = trim($input);
	$input = stripslashes($input);
	$input = htmlspecialchars($input);

	return $input;		
}


