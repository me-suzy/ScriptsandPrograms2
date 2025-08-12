<?
//admin menu file
if ($_SESSION['admin'] ==0)
{
header("Location: http://www.irealms.co.uk/crimson/index.php");
}
else if ($_SESSION['admin'] ==1)
{
echo '<table cellspacing="5" cellpassing="5" border="0"><tr>';
echo '<td><img src="buttons/admincp.jpg" border="0" /></td>';
echo '<td><a href="index.php?page=members" /><img src="buttons/members.jpg" border="0" /></a></td>';
echo '</tr></table>';
}
?>