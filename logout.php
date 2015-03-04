<?php

session_start();
session_destroy();

session_start();

$array = ["Utloggad"];
$_SESSION['messages'] = $array;

header("Location: login.php");