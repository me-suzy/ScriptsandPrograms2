<?
require("checkpass2.php");
require("../teacher/checkpass.php");
?>
<HTML><HEAD><TITLE>Administration</TITLE>
<LINK REL ="stylesheet" HREF="../style.css" TYPE="text/css">

<?
include("../header1.php");
echo '<span class=title>Archive a Project</span><P>';
echo "<P><A HREF=admin.php>Go Back to Admin Home<A>";

require("../access.inc.php");

mysql_connect("$host","$login","$pass") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());

mysql_select_db("$db") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());


IF ($HTTP_GET_VARS[archive]==yes) {
	mysql_query("UPDATE ".$conf['tbl']['projecttable']." SET archive='yes' WHERE ID=$HTTP_GET_VARS[ID]");
}
IF ($HTTP_GET_VARS[archive]==no) {
	mysql_query("UPDATE ".$conf['tbl']['projecttable']." SET archive='no' WHERE ID=$HTTP_GET_VARS[ID]");
}




echo "<P><TABLE BORDER=1><TR><TD COLSPAN=6 ALIGN=CENTER><B>All Projects of Student Work</B></TD></TR>";
echo "<TR ALIGN=CENTER><TD>Project Name</TD><TD>Made By Teacher</TD><TD>Archive Status</TD></TR>";

$list54 = mysql_query("SELECT project,teachername,archive,ID from ".$conf['tbl']['projecttable']." ORDER BY teachername, archive");
WHILE ($arc = mysql_fetch_array($list54)) {

$arc['project'] = deslash($arc['project']);
$arc['teachername'] = deslash($arc['teachername']);

IF ($arc[archive]==yes) {
$larchive = "<A HREF=adminarchive.php?ID=$arc[ID]&archive=no>Remove from Archive</A>";
}

ELSE {
$larchive = "<A HREF=adminarchive.php?ID=$arc[ID]&archive=yes>Add to Archive</A>";
}

echo "<TR ALIGN=CENTER><TD>$arc[project]</TD><TD>$arc[teachername]</TD><TD>$larchive</TD></TR>";
}
echo "</TABLE>";

include("../footer.php");
?>