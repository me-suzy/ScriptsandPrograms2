<?
include "./config.php";
if ($inputpass!=$adminpass){
	echo "<center><form name='form1' method='get' action='setcookie.php'>Enter Administrator's Password: <input type='text' name='inputpass'><input type=submit value='Submit'><br></form></center>";
	exit;
}
setcookie("ckAdminPass", $adminpass, time()+313560000);
print "A cookie has been set in your browser giving you administration priviledges. Now, next to each listing you'll see an [X]. Click it to delete that item. :)<br><br>It may be a good idea to delete <b>setcookie.php</b> now.";
?>