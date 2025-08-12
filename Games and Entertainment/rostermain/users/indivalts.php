<?
if (isset($_GET['user']))
{
$altuser = $_GET['user'];
}
echo '<p>Alts for '.$_GET['user'].'</p>';
if (!isset($_GET['order']))
{
$indivalts = "SELECT * FROM characters WHERE username='$_GET[user]' AND main='0' ORDER BY charactername";
$indivaltquery = mysql_query($indivalts, $db_conn) or die("query [$alts] failed: ".mysql_error());
}
//charname descending
elseif ($_GET['order'] == 'chardesc')
{
$indivalts = "SELECT * FROM characters WHERE username='$altuser' AND main='0' ORDER BY charactername DESC";
$indivaltquery = mysql_query($indivalts, $db_conn) or die("query [$alts] failed: ".mysql_error());
}
//char ascending
elseif ($_GET['order'] == 'charasc')
{
$indivalts = "SELECT * FROM characters WHERE username='$altuser' AND main='0' ORDER BY charactername ASC";
$indivaltquery = mysql_query($indivalts, $db_conn) or die("query [$alts] failed: ".mysql_error());
}
//level descending
elseif ($_GET['order'] == 'lvldesc')
{
$indivalts = "SELECT * FROM characters WHERE username='$altuser' AND main='0' ORDER BY level DESC";
$indivaltquery = mysql_query($indivalts, $db_conn) or die("query [$alts] failed: ".mysql_error());
}
//level ascending
elseif ($_GET['order'] == 'lvlasc')
{
$indivalts = "SELECT * FROM characters WHERE username='$altuser' AND main='0' ORDER BY level ASC";
$indivaltquery = mysql_query($indivalts, $db_conn) or die("query [$alts] failed: ".mysql_error());
}
//class descending
elseif ($_GET['order'] == 'classdesc')
{
$indivalts = "SELECT * FROM characters WHERE username='$altuser' AND main='0' ORDER BY charclass DESC";
$indivaltquery = mysql_query($indivalts, $db_conn) or die("query [$alts] failed: ".mysql_error());
}
//class ascending
elseif ($_GET['order'] == 'classasc')
{
$indivalts = "SELECT * FROM characters WHERE username='$altuser' AND main='0' ORDER BY charclass ASC";
$indivaltquery = mysql_query($indivalts, $db_conn) or die("query [$alts] failed: ".mysql_error());
}
//race descending
elseif ($_GET['order'] == 'racedesc')
{
$indivalts = "SELECT * FROM characters WHERE username='$altuser' AND main='0' ORDER BY race DESC";
$indivaltquery = mysql_query($indivalts, $db_conn) or die("query [$alts] failed: ".mysql_error());
}
//race ascending
elseif ($_GET['order'] == 'raceasc')
{
$indivalts = "SELECT * FROM characters WHERE username='$altuser' AND main='0' ORDER BY race ASC";
$indivaltquery = mysql_query($indivalts, $db_conn) or die("query [$alts] failed: ".mysql_error());
}
echo '<table cellspacing="5" cellpadding="5" border="0"><tr>';
echo '<td><div class="log"><b>Character name</b><a href="index.php?page=indivalts&&order=charasc&&user='.$altuser.'" /><img src="images/down.gif" border="0" /></a><a href="index.php?page=indivalts&&order=chardesc&&user='.$altuser.'" /><img src="images/up.gif" border="0" /></a></div></td>';
echo '<td><div class="log"><b>Level</b><a href="index.php?page=indivalts&&order=lvlasc&&user='.$altuser.'" /><img src="images/down.gif" border="0" /></a><a href="index.php?page=indivalts&&order=lvldesc&&user='.$altuser.'" /><img src="images/up.gif" border="0" /></a></div></td>';
echo '<td><div class="log"><b>Class</b><a href="index.php?page=indivalts&&order=classasc&&user='.$altuser.'" /><img src="images/down.gif" border="0" /></a><a href="index.php?page=indivalts&&order=classdesc&&user='.$altuser.'" /><img src="images/up.gif" border="0" /></a></div></td>';
echo '<td><div class="log"><b>Race</b><a href="index.php?page=indivalts&&order=raceasc&&user='.$altuser.'" /><img src="images/down.gif" border="0" /></a><a href="index.php?page=indivalts&&order=racedesc&&user='.$altuser.'" /><img src="images/up.gif" border="0" /></a></div></td>';
echo '</tr>';
while ($row = mysql_fetch_assoc($indivaltquery)) 
{
	echo '<tr>';
	echo '<td><div class="log">'.$row['charactername'].'</div></td>';
	echo '<td><div class="log2">'.$row['level'].'</div></td>';
	echo '<td><div class="log">'.$row['charclass'].'</div></td>';
	echo '<td><div class="log">'.$row['race'].'</div></td>';
	echo '</tr>';
}
echo '</table>';
?>