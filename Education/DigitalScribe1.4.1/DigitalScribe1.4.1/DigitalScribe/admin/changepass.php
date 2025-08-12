<?
require("checkpass2.php");
require("../teacher/checkpass.php");


require("../access.inc.php");

mysql_connect("$host","$login","$pass") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());

mysql_select_db("$db") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());

?>
<HTML><HEAD><TITLE>Administration</TITLE>
<LINK REL ="stylesheet" HREF="../style.css" TYPE="text/css">

<?
include("../header1.php");
echo '<span class=title>Change Your Password & E-mail Address</span><P>';
echo "<P><A HREF=admin.php>Go Back to Admin Home<A>";

IF (isset($HTTP_POST_VARS[submit])) {

	$HTTP_POST_VARS[pass] = MD5($HTTP_POST_VARS['pass']);

	mysql_query("UPDATE ".$conf['tbl']['teachers']." SET pass='$HTTP_POST_VARS[pass]' WHERE ID=$HTTP_POST_VARS[ID]");

	echo "<P>Your password has been changed.<P>";
}

IF (isset($HTTP_POST_VARS[chng_email])) {

	mysql_query("UPDATE ".$conf['tbl']['teachers']." SET email='$HTTP_POST_VARS[emailad]' WHERE ID=$HTTP_POST_VARS[ID]");

	echo "<P>Your e-mail has been changed.<P>";
}


$show = mysql_query("SELECT email FROM ".$conf['tbl']['teachers']." WHERE ID='$HTTP_SESSION_VARS[secure_id]' ");
		WHILE($work = mysql_fetch_array($show)) {

$emailad=$work['email'];

}


?>

<P>
<table border=1><tr><td>
<FORM METHOD=POST ACTION=changepass.php>
<BR>New Password: <INPUT TYPE=TEXT NAME=pass>
<INPUT TYPE=HIDDEN NAME=ID VALUE=<? echo "$HTTP_SESSION_VARS[secure_id]"; ?>>
<BR><INPUT TYPE=submit NAME=submit VALUE="Update">
</form></td></tr></table>


<P>
<table border=1><tr><td>
<FORM METHOD=POST ACTION=changepass.php>
<BR>New E-mail: <INPUT TYPE=TEXT NAME=emailad VALUE=<? echo "$emailad"; ?>>
<INPUT TYPE=HIDDEN NAME=ID VALUE=<? echo "$HTTP_SESSION_VARS[secure_id]"; ?>>
<BR><INPUT TYPE=submit NAME=chng_email VALUE="Update">
</form></td></tr></table>
<?


include("../footer.php");
?>