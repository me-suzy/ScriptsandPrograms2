<?
if (!isset($_GET['order']))
{
$alts = "SELECT * FROM characters WHERE main='0' ORDER BY username";
$altquery = mysql_query($alts, $db_conn) or die("query [$addchar] failed: ".mysql_error());
}
//altof descending
elseif ($_GET['order'] == 'userdesc')
{
	$alts = "SELECT * FROM characters WHERE main='0' ORDER BY username DESC";
$altquery = mysql_query($alts, $db_conn) or die("query [$addchar] failed: ".mysql_error());
}
//altof ascending
elseif ($_GET['order'] == 'userasc')
{
	$alts = "SELECT * FROM characters WHERE main='0' ORDER BY username ASC";
$altquery = mysql_query($alts, $db_conn) or die("query [$addchar] failed: ".mysql_error());
}
//charname descending
elseif ($_GET['order'] == 'chardesc')
{
	$alts = "SELECT * FROM characters WHERE main='0' ORDER BY charactername DESC";
$altquery = mysql_query($alts, $db_conn) or die("query [$addchar] failed: ".mysql_error());
}
//charname ascending
elseif ($_GET['order'] == 'charasc')
{
	$alts = "SELECT * FROM characters WHERE main='0' ORDER BY charactername ASC";
$altquery = mysql_query($alts, $db_conn) or die("query [$addchar] failed: ".mysql_error());
}
//class descending
elseif ($_GET['order'] == 'classdesc')
{
	$alts = "SELECT * FROM characters WHERE main='0' ORDER BY charclass DESC";
$altquery = mysql_query($alts, $db_conn) or die("query [$addchar] failed: ".mysql_error());
}
//class ascending
elseif ($_GET['order'] == 'classasc')
{
	$alts = "SELECT * FROM characters WHERE main='0' ORDER BY charclass ASC";
$altquery = mysql_query($alts, $db_conn) or die("query [$addchar] failed: ".mysql_error());
}
//lvl descending
elseif ($_GET['order'] == 'lvldesc')
{
	$alts = "SELECT * FROM characters WHERE main='0' ORDER BY level DESC";
$altquery = mysql_query($alts, $db_conn) or die("query [$addchar] failed: ".mysql_error());
}
//lvl ascending
elseif ($_GET['order'] == 'lvlasc')
{
	$alts = "SELECT * FROM characters WHERE main='0' ORDER BY level ASC";
$altquery = mysql_query($alts, $db_conn) or die("query [$addchar] failed: ".mysql_error());
}
//race descending
elseif ($_GET['order'] == 'racedesc')
{
	$alts = "SELECT * FROM characters WHERE main='0' ORDER BY race DESC";
$altquery = mysql_query($alts, $db_conn) or die("query [$addchar] failed: ".mysql_error());
}
//race ascending
elseif ($_GET['order'] == 'raceasc')
{
	$alts = "SELECT * FROM characters WHERE main='0' ORDER BY race ASC";
$altquery = mysql_query($alts, $db_conn) or die("query [$addchar] failed: ".mysql_error());
}
echo '<table cellspacing="5" cellpadding="5" border="0"><tr>';
echo '<td><div class="log"><b>Alt of</b><a href="index.php?page=alts&&order=userasc" /><img src="images/down.gif" border="0" /></a><a href="index.php?page=alts&&order=userdesc" /><img src="images/up.gif" border="0" /></a></div></td>';
echo '<td><div class="log"><b>Character name</b><a href="index.php?page=alts&&order=charasc" /><img src="images/down.gif" border="0" /></a><a href="index.php?page=alts&&order=chardesc" /><img src="images/up.gif" border="0" /></a></div></td>';
echo '<td><div class="log"><b>Level</b><a href="index.php?page=alts&&order=lvlasc" /><img src="images/down.gif" border="0" /></a><a href="index.php?page=alts&&order=lvldesc" /><img src="images/up.gif" border="0" /></a></div></td>';
echo '<td><div class="log"><b>Class</b><a href="index.php?page=alts&&order=classasc" /><img src="images/down.gif" border="0" /></a><a href="index.php?page=alts&&order=classdesc" /><img src="images/up.gif" border="0" /></a></div></td>';
echo '<td><div class="log"><b>Race</b><a href="index.php?page=alts&&order=raceasc" /><img src="images/down.gif" border="0" /></a><a href="index.php?page=alts&&order=racedesc" /><img src="images/up.gif" border="0" /></a></div></td>';
echo '</tr>';
while ($row = mysql_fetch_assoc($altquery)) 
{
	echo '<tr>';
	echo '<td><div class="log">'.$row['username'].'</div></td>';
	echo '<td><div class="log">'.$row['charactername'].'</div></td>';
	echo '<td><div class="log2">'.$row['level'].'</div></td>';
	echo '<td><div class="log">'.$row['charclass'].'</div></td>';
	echo '<td><div class="log">'.$row['race'].'</div></td>';
}
echo '</tr></table>';
?>