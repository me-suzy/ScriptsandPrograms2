<?
session_start();
if($_GET['userID']){
	$_SESSION['userID'] = $_GET['userID'];
}
if(!session_is_registered($_SESSION['userID'])){	
	header("location: login.php");
}
session_destroy();
echo "<META HTTP-EQUIV=\"refresh\" content=\"0; URL=gallery.php\">";
?>