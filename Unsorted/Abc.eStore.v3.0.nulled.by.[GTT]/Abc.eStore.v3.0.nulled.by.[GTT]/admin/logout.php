<?php

//------------------------------------------------------------------------
// This script is a part of ABC eStore
//------------------------------------------------------------------------

session_start();
include ("config.php");
include ("settings.inc.php");
include_once ("loginheader.inc.php");

// print header

echo "<a name=\"toppage\"></a>
<table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\">
<tr bgcolor=\"#cccccc\">
<td width=\"100%\"><a href=\"$site_url\" target=\"_blank\"><img src=\"images/admin_logo_250.gif\" width=\"250\" height=\"50\" border=\"0\" hspace=\"0\" vspace=\"0\"></a></td>
</tr>
</table>
<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
<tr><td height=\"2\"><img src=\"images/z.gif\" width=\"1\" height=\"1\" border=\"0\" hspace=\"0\" vspace=\"0\"></td></tr>
<tr><td height=\"2\" bgcolor=\"#000000\"><img src=\"images/z.gif\" width=\"1\" height=\"1\" border=\"0\" hspace=\"0\" vspace=\"0\"></td></tr>
</table>
\n";


echo "<br><br><br><center><h2>".$lng[663]."</h2></center>";

// store to test if they *were* logged in
$old_user = $_SESSION["admin"];
unset( $_SESSION["admin"] );

session_destroy();
if( !empty( $old_user ) ) {
// If they were logged in and are now successfully logged out 
	echo "<p align=\"center\"><b>".$lng[664]."</b><br>
	<a href='login.php'><b>".$lng[665]."</b></a></p>";
}
else {
// If they weren't logged in but came to this page somehow by mistake
	echo "<b><p align=\"center\">".$lng[666]."</b><br>".$lng[667]."<br><a href='login.php'><b>".$lng[668]."</b></a></p>";
}

include_once ("loginfooter.inc.php");

?>