<?php
//Check for update script made by Coleman Hamilton
//This script is subject to Copyright.

//Config Area
$download_link = "http://www.yourdomain.com/"; // This is where users click when a new update is available
$version = "version.php"; // Where ever this file is make sure your client has this file on their server. Displays the version your client has.
$program = "Program Name"; // This is the where your program name goes
$link = "http://www.yourdomain.com/ck_version.php"; // This is where you put your file on your server, and the page displays if there are new version out.

//Do not edit below here, for ADVANCED USERS ONLY
error_reporting( E_ALL ^ E_NOTICE ); 

print "<center>".
      "Checked updates for the ".$program." <br><br>".
      "The version you have is ".$version." <br><br>".
      "The latest version out is ";
	  
$version_update = include_once("".$link."");

print "<br><br>";

if ($version = $version_update)
{
     print "No Update Needed";
} else {
     print "A new verison is now out! You can upgrade by going <a href='".$download_link."'>here</a>!";
}

print "</center>";

?>