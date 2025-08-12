<?php

/******************************************************************************
File Name    : details.php
Description  : shows a counter's owner, owners email addr, the date created,
               the web page the counter is on, the current count and style
Author       : mike@mfrank.net (Mike Frank)
Date Created : March 20, 2004
Last Change  : April 7, 2004
Licence      : Freeware (GPL)
******************************************************************************/

include("config.php"); include("incl/header.inc");

$siteid = $_GET["siteid"];
if (!isset($siteid)) {
        echo "<h1>Counter Details</h1>Enter site ID:<form action=\"details.php\" method=\"get\"><p><input type=\"text\" name=\"siteid\" size=\"32\"><input type=\"submit\" value=\"View\"></p></form>";
        echo '[<a href="index.php">Home</a>] [<a href="login.php">Login</a>] [<a href="index.php?p=help" target="_blank">Help!</a>]';
        include("incl/footer.inc");
        exit;
}

$counterfile = "countdb/".$siteid.".db";

if (!file_exists($counterfile)) {
        print "<h2>Error</h2><b>Not Found - The site ID you provided is not valid or no longer exists.</b><br><br><div align=\"center\">[<a href=\"javascript:history.go(-1)\">Back</a>]";
        include("incl/footer.inc");
        exit;
}

//open the count db
$fp=fopen("$counterfile","r");
$fdata=fgets($fp, filesize($counterfile)+1);
fclose($fp);

$fdata = split("{}", $fdata);
$owner = ereg_replace("owner:", "", $fdata[0]);         // owners name
$email = ereg_replace("email:", "", $fdata[1]);         // owners email
$created = ereg_replace("created:", "", $fdata[2]);     // counter creation date
$url = ereg_replace("url:", "", $fdata[3]);             // web site url
$count = ereg_replace("count:", "", $fdata[4]);         // current count
$style = ereg_replace("style:", "", $fdata[5]);         // counter style

// display counter details
print "<h1>Counter Details - $siteid</h1>";
print "<table border=1 width=389 cellpadding=3 cellspacing=0>\n";
print "<tr>\n<td align=\"right\" width=104 height=25>Name:</td>\n<td width=269 height=25><b>$owner</b></td>\n</tr>\n";
print "<tr>\n<td align=\"right\" width=104 height=25>Email:</td>\n<td width=269 height=25><b><a href=\"mailto:$email\">$email</a></b></td>\n</tr>\n";
print "<tr>\n<td align=\"right\" width=104 height=25>Date Created:</td>\n<td width=269 height=25><b>$created</b></td>\n</tr>\n";
print "<tr>\n<td align=\"right\" width=104 height=25>URL:</td>\n<td width=269 height=25><b><a href=\"$url\" target=\"_new\">$url</a></b></td>\n</tr>\n";
print "<tr>\n<td align=\"right\" width=104 height=25>Current Count:</td>\n<td width=269 height=25><b>$count</b></td>\n</tr>\n";
print "<tr>\n<td align=\"right\" width=104 height=25>Style:</td>\n<td width=269 height=25><b>$style</b></td>\n</tr>\n</table>";
print '<p>[<b><a href="setup.php">Get Your Own FREE Hit Counter!</a></b>] [<a href="login.php">Login</a>] [<a href="index.php">Home</a>]</p>';

include("incl/footer.inc");
?>

