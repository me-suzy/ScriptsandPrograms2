<?
echo '<p>Are you sure you want to remove <b>'.$_GET['del'].'</b> from the database?</p>';
echo '<p><a href="index.php?page=umem" />Nooooo please take me back!</a>  |  <a href="index.php?page=delu&&deluser='.$_GET['del'].'" />Yes get rid of the fool!</a></p>';
//DELETE USER
	
	if (isset($_GET['deluser']))
			{
				// delete from users
	$userdel = "DELETE FROM users where username = '$_GET[deluser]'"; 
	$donedel = mysql_query($userdel, $db_conn) or die("query [$userdel] failed: ".mysql_error()); 
	$cdelete = "DELETE FROM characters where username = '$_GET[deluser]'"; 
	$cdel = mysql_query($cdelete, $db_conn) or die("query [$cdel] failed: ".mysql_error());
		}
		if (isset($donedel) && isset($cdel))
		//&& isset($frem))
			{
			header("Location: index.php?page=umem");
			}
?>