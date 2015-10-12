<?php
require_once('header.php');

session_start();
session_destroy();

session_start();

$array = array("Utloggad");
$_SESSION['messages'] = $array;

header("Location: login.php");

require_once('footer.php');