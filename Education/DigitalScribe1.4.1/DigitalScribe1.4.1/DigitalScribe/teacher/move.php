<?
/* Move a student's work from one project to another. */

require("checkpass.php");
?>
<?
require("../access.inc.php");

mysql_connect("$host","$login","$pass") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());

mysql_select_db("$db") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());


if(isset($HTTP_POST_VARS['Submit'])) {
	mysql_query("UPDATE ".$conf['tbl']['studentwork']." SET project=\"$HTTP_POST_VARS[newproject]\" WHERE id='$HTTP_POST_VARS[id]'");
	header("location:indepthadmin.php?ID=$HTTP_POST_VARS[ID]");
}

?>
<HTML><HEAD><TITLE>Move to Another Project</TITLE>
<LINK REL ="stylesheet" HREF="../style.css" TYPE="text/css">
<?
include("../header1.php"); 


if(isset($HTTP_SESSION_VARS['secure_ghost'])) {
$newID=$HTTP_SESSION_VARS['secure_ghost'];
}
else {
$newID=$HTTP_SESSION_VARS['secure_id'];
}


$showproject23 = mysql_query("SELECT ID FROM ".$conf['tbl']['teachers']." WHERE ID=$newID");
WHILE($pro = mysql_fetch_array($showproject23)) {
$user = "$pro[ID]";
}

echo "<FORM METHOD=POST ACTION=move.php>";

echo "<BR>Move to project: <SELECT NAME=newproject>";
$show = mysql_query("SELECT project FROM ".$conf['tbl']['projecttable']." WHERE TID = '$user'");
WHILE ($projecttable = mysql_fetch_array($show)) {

echo "<OPTION VALUE=\"$projecttable[project]\">";

echo deslash($projecttable['project'])."</OPTION>";
}
echo "</SELECT>";
echo "<INPUT TYPE=HIDDEN NAME=id VALUE=$HTTP_GET_VARS[id]>";
echo "<INPUT TYPE=HIDDEN NAME=ID VALUE=$HTTP_GET_VARS[ID]>";
echo "<BR><INPUT TYPE=Submit NAME=Submit VALUE=Move></FORM>";

include("../footer.php");
?>