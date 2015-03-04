<?php
require_once("functions.php");
// huvudmallen på sidan
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="nl" lang="nl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="description" content="Ett intressant forum där man kan snacka skit!" />
    <meta name="keywords" content="forum, uppgift, reddit" />
    <title>PHP-MySQL forum</title>
    <link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
    <div id="menu">
        <a class="item" href="/forum/index.php">shreddit, get it?</a> 
        <div id="userbar">
        <?php loggedIn() ?>
        </div><!-- end userbar -->
    </div><!-- end menu -->
    <div id="wrapper">
        <div id="content">
            


