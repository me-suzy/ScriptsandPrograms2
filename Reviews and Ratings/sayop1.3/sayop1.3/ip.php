<?php
/*


	Copyright (C) 2004-2005 Alex B

	E-Mail: dirmass@devplant.com
	URL: http://www.devplant.com
	
    This file is part of SayOp.

    SayOp is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2.1 of the License, or
    (at your option) any later version.

    SayOp is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with SayOp; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA


*/
session_start();
include("inc/auth.php");
include("inc/redir.php");
if($user == $_SESSION["username"] && $pass == $_SESSION["password"]) {
    echo "
<!DOCTYPE html
PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN'
'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html>
   <head>
      <title>SayOp - IP Control</title>
      <link rel='stylesheet' type='text/css' href='style_1.css' />
      <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
   </head>
<body class='admin'>
<h2 class='index' align='left'>SayOp Admin Control Panel</h2>
<div class='we' align='left'>Welcome " . $_SESSION['username'] .  "<br /></div>
";

include("com/db.php");
$sql = mysql_query("SELECT * FROM ".$so_prefix."_obj") or die("Sql error:" . mysql_error());
$rsql = mysql_query("SELECT * FROM ".$so_prefix."_main") or die("Sql error:" . mysql_error());
$nrows = mysql_num_rows($sql);
$nr = mysql_num_rows($rsql);
$nodef;
$nocase1 = "There are <b>$nrows</b> objects";

$nocase2 = "There is <b>$nrows</b> object";
$comdef;
$comcase1 = "comments";
$comcase2 = "comment";
if($nrows=='1') { 
$nodef=$nocase2; 
} else { $nodef=$nocase1; 
}
if($nr=='1') { 
$comdef=$comcase2; 
} else { $comdef=$comcase1; 
}
echo "
<span style='float: left; background: #FFFFFF; margin-left: 30px;' align='left'>
$nodef and <b>$nr</b> posted $comdef in the database.</span>
<div style='float: right; margin-right: 50px; background: #CCFF99;'><a href='http://www.devplant.com/sayop' target='_blank'>SayOp 1.3</a> &copy 2004-2005 <a href='http://www.devplant.com' target='_blank'>Alex B</a> - Licensed under the <a href='http://www.gnu.org/copyleft/gpl.html'>GPL</a></div><br />
<br /><br />
<div align='left' class='obj_name'>
<div class='oselect'>Comments active in:</div><br /><br />
<p>
";


include("com/db.php");
$sql = mysql_query("SELECT * FROM ".$so_prefix."_obj") or die("Sql error:" . mysql_error());
$nrows = mysql_num_rows($sql);




if($nrows=='0') {
echo "No objects found";
}
else {
while ($irow = mysql_fetch_row($sql)) {
$countrows = mysql_query("SELECT * FROM ".$so_prefix."_main WHERE catid='$irow[0]'") or die("Sql error:" . mysql_error());

$countedrows = mysql_num_rows($countrows);

echo" <a href='index.php?obj_name=$irow[1]&catid=$irow[0]'>$irow[1]</a> ($countedrows)<br /> ";
                                      }
     }
echo "
</p>
</div>


<div class='ilay'>

<div class='rselect'><a href='index.php'>Add new object</a> / <a href='delobj.php'>Delete objects</a> / <a href='index.php'>Add new entries</a> / <a href='del.php'>Delete entries</a> / <b>IP Control</b> / <a href='smile.php'>Smilies</a> / <a href='com/logout.php'>Logout</a></div> 
";

////////////////////////////////////////////////////
////////////////////////////////////////////////////


echo "

<hr />

<div style='float: left;'>
<p>Here you can ban an IP address from posting comments in any articles.</p>
<br />

<form name='ipfrm' action='com/banip.php' method='post'>
IP: <input name='ip' type='text' size='40' /> 
<input name='ban' type='submit' value='Ban IP' />
</form>

</div>


<div style='width: 100%; background-color: ; border-top: 0px; border-bottom: 1px groove #333; padding: 5px;
margin: 0px; text-align: left; float: left;'>

<h4>Banned IPs</h4>

";


$ipquery = mysql_query("SELECT * FROM ".$so_prefix."_bannedip ORDER BY bandate ") or die("Sql error:" . mysql_error());
$iprows = mysql_num_rows($ipquery);


if($iprows=='0') {
echo "There are no banned IPs";
}
else {
while ($irow = mysql_fetch_row($ipquery)) {

echo " <a href='ip.php?ip=$irow[1]'>$irow[1]</a> - Banned: $irow[2] &nbsp;&nbsp;&nbsp; <a href='com/banip.php?ip=$irow[1]'>Unban IP</a><br /> ";
                                      }
     }


echo "
</div>
</body>
</html>


";

} else {
smsg("<b>Access denied</b> <br /> Please use <a href='admin.php'>admin.php</a> to login.");
}
?> 
