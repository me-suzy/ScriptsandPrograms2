<?php

/******************************************************************************
File Name    : sitelist.php
Description  : print signup.log between headers and footers
Author       : mike@mfrank.net (Mike Frank)
Date Created : March 24, 2004
Last Change  : April 14, 2004
Licence      : Freeware (GPL)
******************************************************************************/

include("config.php");
include("incl/header.inc");
print "<h1>Web Site Listings</h1>";
echo '[<a href="index.php">Home</a>] [<a href="login.php">Login</a>] [<a href="index.php?p=help" target="_blank">Help!</a>]<br><br>';
$fd = fopen("signup.log", "r");
fpassthru($fd);
echo '<br><br>[<a href="index.php">Home</a>] [<a href="login.php">Login</a>] [<a href="index.php?p=help" target="_blank">Help!</a>]';
include("incl/footer.inc");
?>