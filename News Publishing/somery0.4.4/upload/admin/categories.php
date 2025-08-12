<?php
// Somery, a weblogging script by Robin de Graaf, copyright 2001-2005
// Somery is distributed under the Artistic License (see LICENSE.txt)
//
// ADMIN/CATEGORIES.PHP > 03-11-2005

$start = TRUE; 
include("system/include.php"); 
if ($checkauth) {


if ($userdata['level'] >= 2) { 
 
if (!$action) { 
	echo "<strong>Categories</strong><br /><br /><table>"; 
	$result = mysql_query("SELECT * FROM ".$prefix."categories ORDER BY cid"); 
	while($row=mysql_fetch_object($result)) { 
		echo "<tr><td width=175>&nbsp;&nbsp;&nbsp;$row->cid - $row->category</td><td>"; 
		if ($row->category != "general") { 
			echo "<a href='categories.php?action=edit&cid=$row->cid'>Edit</a>"; 
		} else { 
			echo "Cannot be edited"; 
		} 
		echo "</td></tr>"; 
	} 
	echo "</table><br>"; 
	echo "<table><tr><td><form method='post' action='categories.php'><input type='hidden' name='action' value='new'></td></tr></table>"; 
	echo "<b>Add a category</b><br><table>"; 
	echo "<tr><td width=175>Category name</td><td><input size=50 name='newcat' type='text'></td></tr>"; 
	echo "<tr><td width=175>Save changes</td><td><input type='submit' value='proceed'></a></td></tr>"; 
	echo "</table>"; 
 
} elseif ($action == "new") { 
	if (!$newcat && !$err) { show_error(13); $err = 1; } 
	if (!$err) { 
	$result = mysql_query("INSERT INTO ".$prefix."categories (category) VALUES ('".$newcat."')"); 
      echo "<meta http-equiv=Refresh content=0;URL='categories.php'>"; 
	} 
} elseif ($action == "edit") { 
	echo "<table><tr><td><form method='post' action='categories.php'><input type='hidden' name='action' value='update'><input type='hidden' name='cid' value='$cid'></td></tr></table>"; 
	echo "<b>Edit category</b><br><table>"; 
	$result = mysql_query("SELECT * FROM ".$prefix."categories WHERE cid='$cid'"); 
	while($row=mysql_fetch_object($result)) { 
		echo "<tr><td width=175>Category name</td><td><input size=50 name='newcat' type='text' value='$row->category'></td></tr>"; 
	} 
	echo " 
	<tr><td width=175><br>Delete this category</td><td><br><input type='checkbox' name='delete'></td></tr> 
	<tr><td width=175>Save changes</td><td><input type='submit' value='proceed'></td></tr> 
	</table>"; 
} elseif ($action == "update") { 
	if ($delete) { 
		$result = mysql_query("SELECT * FROM ".$prefix."categories WHERE category = 'general'"); 
		while($row=mysql_fetch_object($result)) { 
			$setcat = $row->cid; 
		} 
		$result = mysql_query("DELETE FROM ".$prefix."categories WHERE cid = '$cid'"); 
		$result = mysql_query("UPDATE ".$prefix."articles SET  
			category = '$setcat' 
			WHERE category = '$cid'"); 
		echo "<meta http-equiv=Refresh content=0;URL='categories.php'>"; 
	} else { 
	if (!$newcat && !$err) { echo $error[13]; $err = 1; } 
 
	if (!$err) { 
		$result = mysql_query("UPDATE ".$prefix."categories SET  
			category='$newcat' 
		WHERE cid = '$cid'"); 
	      echo "<meta http-equiv=Refresh content=0;URL='categories.php'>"; 
	} 
	} 
} 
} 
?> 
 
<?php }; $start = FALSE; include("system/include.php"); ?>