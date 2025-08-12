<?
if (isset($_SESSION['valid_user']))
{
	echo 'Are you sure you want to delete '.$_GET['delch'].' ?';
	$delch = $_GET['delch'];
	echo '<form method="post" action="index.php?page=delchar2&&delete='.$delch.'">';
	echo '<input type="submit" name="deleteyes" value="Yes" style="font-size:10px;color:#FFFFFF;background-color:#9A0602;border: 0px;"></form>';
	echo '<a href="index.php?page=profile" />No take me back to my profile</a>';
}
?>