<div align="center">
Site Roster<br /><br />
<p>If you have registered and are not on the list, you have not yet been approved by admin.</p>
</div>
<?
if (!isset($_GET['order']))
{
$rosquery = "SELECT users.username,users.approved,users.rank,users.admin,characters.charactername,characters.username,characters.level,characters.charclass,characters.race,characters.main FROM users,characters WHERE users.username=characters.username ORDER BY users.username"; 
$roster = mysql_query($rosquery, $db_conn) or die('query failed');
}
//user descending
elseif ($_GET['order'] == 'userdesc')
{
	$rosquery = "SELECT users.username,users.approved,users.rank,users.admin,characters.charactername,characters.username,characters.level,characters.charclass,characters.race,characters.main FROM users,characters WHERE users.username=characters.username ORDER BY users.username DESC"; 
	$roster = mysql_query($rosquery, $db_conn) or die("query [$roster] failed: ".mysql_error());
}
//user ascending
elseif ($_GET['order'] == 'userasc')
{
	$rosquery = "SELECT users.username,users.approved,users.rank,users.admin,characters.charactername,characters.username,characters.level,characters.charclass,characters.race,characters.main FROM users,characters WHERE users.username=characters.username ORDER BY users.username ASC"; 
	$roster = mysql_query($rosquery, $db_conn) or die("query [$roster] failed: ".mysql_error());
}
//main descending
elseif ($_GET['order'] == 'maindesc')
{
	$rosquery = "SELECT users.username,users.approved,users.rank,users.admin,characters.charactername,characters.username,characters.level,characters.charclass,characters.race,characters.main FROM users,characters WHERE users.username=characters.username ORDER BY characters.charactername DESC"; 
	$roster = mysql_query($rosquery, $db_conn) or die("query [$roster] failed: ".mysql_error());
}
//main ascending
elseif ($_GET['order'] == 'mainasc')
{
	$rosquery = "SELECT users.username,users.approved,users.rank,users.admin,characters.charactername,characters.username,characters.level,characters.charclass,characters.race,characters.main FROM users,characters WHERE users.username=characters.username ORDER BY characters.charactername ASC"; 
	$roster = mysql_query($rosquery, $db_conn) or die("query [$roster] failed: ".mysql_error());
}
//level descending
elseif ($_GET['order'] == 'lvldesc')
{
	$rosquery = "SELECT users.username,users.approved,users.rank,users.admin,characters.charactername,characters.username,characters.level,characters.charclass,characters.race,characters.main FROM users,characters WHERE users.username=characters.username ORDER BY characters.level DESC"; 
	$roster = mysql_query($rosquery, $db_conn) or die("query [$roster] failed: ".mysql_error());
}
//level ascending
elseif ($_GET['order'] == 'lvlasc')
{
	$rosquery = "SELECT users.username,users.approved,users.rank,users.admin,characters.charactername,characters.username,characters.level,characters.charclass,characters.race,characters.main FROM users,characters WHERE users.username=characters.username ORDER BY characters.level ASC"; 
	$roster = mysql_query($rosquery, $db_conn) or die("query [$roster] failed: ".mysql_error());
}
//class descending
elseif ($_GET['order'] == 'classdesc')
{
	$rosquery = "SELECT users.username,users.approved,users.rank,users.admin,characters.charactername,characters.username,characters.level,characters.charclass,characters.race,characters.main FROM users,characters WHERE users.username=characters.username ORDER BY characters.charclass DESC"; 
	$roster = mysql_query($rosquery, $db_conn) or die("query [$roster] failed: ".mysql_error());
}
//class ascending
elseif ($_GET['order'] == 'classasc')
{
	$rosquery = "SELECT users.username,users.approved,users.rank,users.admin,characters.charactername,characters.username,characters.level,characters.charclass,characters.race,characters.main FROM users,characters WHERE users.username=characters.username ORDER BY characters.charclass ASC"; 
	$roster = mysql_query($rosquery, $db_conn) or die("query [$roster] failed: ".mysql_error());
}
//race descending
elseif ($_GET['order'] == 'racedesc')
{
	$rosquery = "SELECT users.username,users.approved,users.rank,users.admin,characters.charactername,characters.username,characters.level,characters.charclass,characters.race,characters.main FROM users,characters WHERE users.username=characters.username ORDER BY characters.race DESC"; 
	$roster = mysql_query($rosquery, $db_conn) or die("query [$roster] failed: ".mysql_error());
}
//race ascending
elseif ($_GET['order'] == 'raceasc')
{
	$rosquery = "SELECT users.username,users.approved,users.rank,users.admin,characters.charactername,characters.username,characters.level,characters.charclass,characters.race,characters.main FROM users,characters WHERE users.username=characters.username ORDER BY characters.race ASC"; 
	$roster = mysql_query($rosquery, $db_conn) or die("query [$roster] failed: ".mysql_error());
}
//rank descending
elseif ($_GET['order'] == 'rankdesc')
{
	$rosquery = "SELECT users.username,users.approved,users.rank,users.admin,characters.charactername,characters.username,characters.level,characters.charclass,characters.race,characters.main FROM users,characters WHERE users.username=characters.username ORDER BY users.rank DESC"; 
	$roster = mysql_query($rosquery, $db_conn) or die("query [$roster] failed: ".mysql_error());
}
//rank ascending
elseif ($_GET['order'] == 'rankasc')
{
	$rosquery = "SELECT users.username,users.approved,users.rank,users.admin,characters.charactername,characters.username,characters.level,characters.charclass,characters.race,characters.main FROM users,characters WHERE users.username=characters.username ORDER BY users.rank ASC"; 
	$roster = mysql_query($rosquery, $db_conn) or die("query [$roster] failed: ".mysql_error());
}
echo '<a href="index.php?page=alts" />Show all alternates</a>';
echo '<table cellspacing="5" cellpadding="5" border="0"><tr>';
echo '<td><div class="log"><b>Username</b><a href="index.php?page=roster&&order=userasc" /><img src="images/down.gif" border="0" /></a><a href="index.php?page=roster&&order=userdesc" /><img src="images/up.gif" border="0" /></a></div></td>';
echo '<td><div class="log"><b>Main Character</b><a href="index.php?page=roster&&order=mainasc" /><img src="images/down.gif" border="0" /></a><a href="index.php?page=roster&&order=maindesc" /><img src="images/up.gif" border="0" /></a></div></td>';
echo '<td><div class="log"><b>Level</b><a href="index.php?page=roster&&order=lvlasc" /><img src="images/down.gif" border="0" /></a><a href="index.php?page=roster&&order=lvldesc" /><img src="images/up.gif" border="0" /></a></div></td>';
echo '<td><div class="log"><b>Class</b><a href="index.php?page=roster&&order=classasc" /><img src="images/down.gif" border="0" /></a><a href="index.php?page=roster&&order=classdesc" /><img src="images/up.gif" border="0" /></a></div></td>';
echo '<td><div class="log"><b>Race</b><a href="index.php?page=roster&&order=raceasc" /><img src="images/down.gif" border="0" /></a><a href="index.php?page=roster&&order=racedesc" /><img src="images/up.gif" border="0" /></a></div></td>';
echo '<td><div class="log"><b>Rank</b><a href="index.php?page=roster&&order=rankasc" /><img src="images/down.gif" border="0" /></a><a href="index.php?page=roster&&order=rankdesc" /><img src="images/up.gif" border="0" /></a></div></td><td></td>';
echo '</tr>';
while ($row = mysql_fetch_assoc($roster)) 
{
	if ($row['rank'] == 1) 
   { 
      $rank = Officer; 
   } 
   if ($row['rank'] == 2) 
   { 
      $rank = Leader; 
   } 
   if ($row['rank'] == 0) 
   { 
      $rank = Member; 
   } 
   if ($row['rank'] == 3) 
   { 
      $rank = Trialist; 
   }
   if ($row['approved'] == 1 && $row['main'] == 1)
	{
echo '<tr><td><div class="log">'.$row['username'].'</div></td>'; 
echo '<td><div class="log">'.$row['charactername'].'</div></td>';
echo '<td><div class="log2">'.$row['level'].'</div></td>';
echo '<td><div class="log">'.$row['charclass'].'</div></td>';
echo '<td><div class="log">'.$row['race'].'</div></td>';
echo '<td><div class="log">'.$rank.'</div></td>'; 
echo '<td><div class="log"><a href="index.php?page=indivalts&&user='.$row['username'].'" />Alts</a></div></td>';
	}
}
echo '</tr></table>';
?>