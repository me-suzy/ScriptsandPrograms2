<?php
//////////////////////////////////////////////////////////////////////////////
// DJ Status v1.8.2															//
// ©2005 Nathan Bolender www.nathanbolender.com								//
// Free to use on any website												//
//////////////////////////////////////////////////////////////////////////////

include ("config.php");

if ($mode == "1") {

if (isset($dj)) {

	$query="SELECT * FROM currentdj WHERE dj = '$dj'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		  $pass = "$row[password]";
}

$password1 = $_POST['password1'];

if (("$password1" == "$pass") or ("$password1" == "$adminpass")) {


	$resultID = mysql_query("UPDATE currentdj SET active = '0' WHERE dj = '$dj'") or die(mysql_error());
	if ($resultID == TRUE) {
		print "DJ $name has been set to inactive. (SUCCESSFUL)";
	} else {
		print "DJ $name is still set to active, please try again. (NOT SUCCESSFUL)";
	}
} else {
echo "<b>INCORRECT PASSWORD</b>";
}	
} else {
///////////////////////////////////////////////////
	$query="SELECT * FROM currentdj WHERE dj = '$newdj'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		  $pass = "$row[password]";
}		  

$password = $_POST['password'];

if (("$password" == "$pass") or ("$password" == "$adminpass"))  {



	$resultID = mysql_query("UPDATE currentdj SET active = '1' WHERE dj = '$newdj'") or die(mysql_error());
	if ($resultID == TRUE) {
		print "You (DJ #$newdj) have been set to active. (SUCCESSFUL)";
	} else {
		print "You (DJ #$newdj) have NOT been set to active, please try again. (NOT SUCCESSFUL)";
	}
	
} else {
echo "<b>INCORRECT PASSWORD</b>";
}
}
 } else {
 echo "<b>DJ Status is currently in Automatic mode. To switch to Manual mode, have $adminname change the setting in the administration panel.</b>";
 }
 echo "<br><br><font size=\"-1\"><strong>Powered by DJ Status v$version - &copy;2005 Nathan Bolender - <a href=\"http://www.nathanbolender.com\" target=\"_blank\">www.nathanbolender.com</a></strong></font>";
	?>