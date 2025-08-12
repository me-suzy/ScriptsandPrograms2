<?
session_start();
require ('_.php');

require ('functions.php');
echo "<h4>your account has been activated but you have not yet been approved!</h4><br>Your only function till approval is changing your password";
echo"<br> change your password to something you can remember.<br>";
echo"after changing your password you will not be able to access any admin functions other than this page untill approval is granted by your <a href=".$config[site_admin_email]."> site administrator</a></p>";
echo" <a href=logout.php?logmeout accesskey=l>Logout</a>";
include('changepw.php');
	?>		
