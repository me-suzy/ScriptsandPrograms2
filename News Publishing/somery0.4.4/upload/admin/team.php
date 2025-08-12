<?php
// Somery, a weblogging script by Robin de Graaf, copyright 2001-2005
// Somery is distributed under the Artistic License (see LICENSE.txt)
//
// ADMIN/TEAM.PHP > 03-11-2005

$start = TRUE; 
include("system/include.php"); 
if ($checkauth) {
 

if (!$action) { 
	$result = mysql_query("SELECT * FROM ".$prefix."users ORDER BY uid"); 
	echo "<strong>Team moderation</strong><br /><br />
		<table>
		<tr><td width=175><b>Name</b></td><td width=50><b>level</b></td><td width=100><b>title</b></td><td width=40><b>edit</b></td></tr></table>"; 
	echo "<table>"; 
	while($row=mysql_fetch_object($result)) { 
		echo "<tr><td width=175><a href='profile.php?action=view&username=$row->username'>$row->username</a></td><td width=50>$row->level</td><td width=100>".$levelname[$row->level]. "</td>"; 
		if ($row->level <= $userdata['level'] && $row->level != 4 && $user != $row->username && $userdata['level'] > 1) { 
			if ($row->level != 0) { 
				echo "<td width=10><a href='$PHP_SELF?action=adjust&type=demote&id=$row->uid'>-</a> </td>"; 
			} elseif ($row->level == 0) { 
				echo "<td width=10><a href='$PHP_SELF?action=adjust&type=remove&id=$row->uid'>x</a> </td>"; 
			} else { 
				echo "<td width=10> &nbsp; </td>"; 
			} 
			if ($row->level < 3 && $userdata['level'] > $row->level) { 
				echo "<td width=10><a href='$PHP_SELF?action=adjust&type=promote&id=$row->uid'>+</a> </td>"; 
			} else { 
				echo "<td width=10> &nbsp; </td>"; 
			} 
		} else { 
			echo "<td width=10>&nbsp;</td><td width=20>&nbsp;</td>"; 
		} 
		echo "</tr>"; 
	} 
	echo "</table>"; 
} elseif ($action == "adjust") { 
	$result = mysql_query("SELECT * FROM ".$prefix."users WHERE uid = '$id'"); 
	while($row=mysql_fetch_object($result)) {	$t_level = $row->level; } 
 
	if ($userdata['level'] >= $t_level && $t_level < 3) { 
		if ($type == "demote") { 
			$result = mysql_query("SELECT * FROM ".$prefix."users WHERE uid = '$id'"); 
			while($row=mysql_fetch_object($result)) {	$t_level = $row->level; } 
			$t_level--; 
			$result = mysql_query("UPDATE ".$prefix."users SET level = '$t_level' WHERE uid = '$id'"); 
			echo "<meta http-equiv=Refresh content=0;URL='team.php'>"; 
		} elseif ($type == "promote") { 
			$result = mysql_query("SELECT * FROM ".$prefix."users WHERE uid = '$id'"); 
			while($row=mysql_fetch_object($result)) {	$t_level = $row->level; } 
			$t_level++; 
			$result = mysql_query("UPDATE ".$prefix."users SET level = '$t_level' WHERE uid = '$id'"); 
			echo "<meta http-equiv=Refresh content=0;URL='team.php'>"; 
		} elseif ($type == "remove") { 
			$result = mysql_query("SELECT * FROM ".$prefix."users WHERE uid = '$id'"); 
			while($row=mysql_fetch_object($result)) {	$t_user= $row->username; } 
 
			$result = mysql_query("DELETE FROM ".$prefix."users WHERE uid = '$id'"); 
			$result = mysql_query("DELETE FROM ".$prefix."profile WHERE username = '$t_user'"); 
			echo "<meta http-equiv=Refresh content=0;URL='team.php'>"; 
		} 
	} else { 
		echo "invalid permissions, meaning you cant do that - <a href='$PHP_SELF'>go back</a>"; 
	} 
} 
?> 
 
<?php }; $start = FALSE; include("system/include.php"); ?> 
