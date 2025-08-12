<?php

include "config.php";

$sql = "CREATE TABLE CJ_UsersOnline (
  timestamp int(15) NOT NULL default '0',
  ip varchar(40) NOT NULL,
  FILE varchar(100) NOT NULL,
  PRIMARY KEY  (timestamp),
  KEY ip (ip),
  KEY FILE (FILE)
) TYPE=MyISAM";

mysql_connect($server, $db_user, $db_pass) or die ("Database CONNECT Error");  
echo "<font face=\"verdana\" size=\"4\" color=\"darkblue\"><b>CJ Users Online V1.0 Setup</b></font><p>";
echo "<font face=\"verdana\" size=\"3\"><b>Creating table:</b> Users Online!</font><p>";
mysql_db_query($db, $sql);
echo "<font face=\"verdana\" size=\"2\" color=\"red\">....<b>Successful!</b></font><p>";
echo "<font face=\"verdana\" size=\"3\"><b>Deleting File:</b> \"setup.php\"</font><p>";
echo "<font face=\"verdana\" size=\"2\" color=\"red\">";
$delete = unlink("setup.php");
if($delete){
	echo "....<b>Setup file deleted!</b></font>";
}
else{
	echo "<b>Unable to delete Setup file (setup.php) go and delete the set up file manually!</b></font>";
}

echo "<p><font face=\"verdana\" size=\"3\" color=\"darkblue\"><b>Redirecting you in 3 seconds...</b></font>";
echo "<META HTTP-EQUIV=Refresh CONTENT=\"3; URL=users.php\">";
echo "<hr>";
echo "<font face=\"verdana\" size=\"2\"><div align=\"right\">CJ Users Online V1.0 Automatic Setup</div></font>";




?>