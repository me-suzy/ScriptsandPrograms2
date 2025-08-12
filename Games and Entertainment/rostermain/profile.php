<?
if (isset($_SESSION['valid_user']))
{
	$trialistsec = "SELECT rank FROM users WHERE username='$_SESSION[valid_user]'";
	$sec = mysql_query($trialistsec, $db_conn) or die("Query [sec] Failed: ".mysql_error());
	$rowsec = mysql_fetch_assoc($sec);
	if($rowsec['rank']!=3)
	{
echo '<div align="right"><a href="index.php?page=addchar" />Add a new character</a></div>';
	}
echo 'Profile for '.$_SESSION['valid_user'].'.<br />';
echo '<p>To edit your character details simply click on the value you wish to edit and fill in the resulting form.</p>';
echo '<br />';

$charquery = "SELECT * FROM characters WHERE username='$_SESSION[valid_user]'";
$charresult = mysql_query($charquery, $db_conn) or die("query [$charquery] failed: ".mysql_error()); 
echo '<table cellspacing="5" cellpadding="5" border="0"><tr>';
echo '<td><div class="log"><u>Character Name</u></div></td><td><div class="log"><u>Level</u></div></td><td><div class="log"><u>Class</u></div></td><td><div class="log"><u>Race</u></div></td><td><div class="log"><u>Main/Alt</u></div></td><td></td>';
echo '</tr>';
}
while ($row = mysql_fetch_assoc($charresult)) 
{
	echo '<tr>';
	echo '<td><div class="log"><a href="index.php?page=charedit&&nameedit='.$row['charactername'].'&&select='.$row['charactername'].'" />'.$row['charactername'].'</a></div></td>';
	echo '<td><div class="log"><a href="index.php?page=charedit&&lvledit='.$row['level'].'&&select='.$row['charactername'].'" />'.$row['level'].'</a></div></td>';
	echo '<td><div class="log"><a href="index.php?page=charedit&&classedit='.$row['charclass'].'&&select='.$row['charactername'].'" />'.$row['charclass'].'</a></div></td>';
	echo '<td><div class="log"><a href="index.php?page=charedit&&raceedit='.$row['race'].'&&select='.$row['charactername'].'" />'.$row['race'].'</a></div></td>';
	if ($row['main'] ==1)
	{
		echo '<td><div class="log">Main</div></td>';
	}
	else 
	{
		echo '<td><div class="log">Alt</div></td>';
	}
	if ($row['main'] ==0)
	{
	echo '<td><a href="index.php?page=delchar&&delch='.$row['charactername'].'" />Delete</a></td>';
	}
	echo '</tr>';
}
echo '</table>';
?>


